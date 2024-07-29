<?php

namespace App\Http\Middleware;

use App\Models\CartOrder;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->rule === 'RULE_USER') {
            $user = User::find(auth()->user()->id);

            $dominio = $request->getHost();
            if (strtolower($dominio) == 'lifeprosper.eu') {
                $prod = 1;
            } else {
                // $prod = 0;
                $prod = 1;
            }

            $this->sendPostBonificacaoLogin($user->id, $prod);
            $this->clearCart($user->id);

            if ($user->getAdessao($user->id) >= 1) {
                return redirect()->route('home.home');
            } else {
                return redirect()->route('home.home');
                // return redirect()->route('packages.index_products');
            }

        }
        return $next($request);
    }

    public function clearCart($id_user)
    {
        $exists = CartOrder::where('id_user', $id_user)->get();
        if (count($exists) > 0) {
            DB::table('cart_orders')->where('id_user', $id_user)->delete();
        }
    }

    public function sendPostBonificacaoLogin($id_user, $prod)
    {
        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        $data = [
            "type" => "bonificacao",
            "param" => "GeraCarreira",
            "prod" => $prod,
            "idusuario" => "$id_user"
        ];

        $url = 'https://lifeprosper.eu/public/compensacao/bonificacao.php';

        try {
            //code...

            $resposta = $client->post($url, [
                'form_params' => $data,
                // 'headers' => $headers,

            ]);

            $statusCode = $resposta->getStatusCode();
            $body = $resposta->getBody()->getContents();

            parse_str($body, $responseData);

            try {
                //code...
                $log = new CustomLog;
                $log->content = json_encode($responseData);
                $log->user_id = $id_user;
                $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idusuario'];
                $log->controller = "app/middleware/isAdmin";
                $log->http_code = 200;
                $log->route = "login_backoffice";
                $log->status = "SUCCESS";
                $log->save();
            } catch (\Throwable $th) {
                return;
            }

        } catch (\Throwable $th) {
            return;
        }

        return $responseData;
    }
}