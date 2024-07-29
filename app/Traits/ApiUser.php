<?php

namespace App\Traits;

use App\Http\Controllers\HomeController;
use App\Models\Banco;
use App\Models\BestDatePaymentSmartshipping;
use App\Models\Career;
use App\Models\CareerUser;
use App\Models\CartOrder;
use App\Models\ConfigBonus;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\PaymentOrderEcomm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

trait ApiUser
{
    private function getUser($request)
    {
        try {

            $token = $request->bearerToken();

            $tkn = PersonalAccessToken::where('token', $token)->first();

            $agora = Carbon::now();
            $expiracao = Carbon::parse($tkn->expires_at);

            if (!$tkn) {
                return false;
            }

            if ($agora->gt($expiracao)) {
                return false;
            }

            $user = User::where('id', $tkn->tokenable_id)->first();

            if (!$user) {
                return false;
            }

            $tkn->last_used_at = Carbon::now()->format('Y-m-d H:i:s');
            $tkn->save();

            if ($user->activated == 0) {
                return response()->json(['error' => 'Account awaiting approval'], 401);
            }

            return $user;

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error in process token'], 401);
        }
    }

    private function getTotalComission(User $user)
    {

        $currentDate = Carbon::now();
        $subMonth = $currentDate->subMonth()->month;
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($subMonth >= 2) {
            $subMonth = $subMonth - 1;
        } else {
            $subMonth = 12;
            $currentYear = $currentYear - 1;
        }

        if ($subMonth < 9) {
            $subMonth = '0' . $subMonth;
        }

        $farst_day = date('t', mktime(0, 0, 0, $subMonth, '01', $currentYear));

        $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        $totalComission = Banco::where('user_id', $user->id)
            ->where('price', '>', 0)
            ->where('created_at', '<=', $dateComplete2)
            ->sum('price');

        if (isset($totalComission) && $totalComission > 0) {
            return number_format($totalComission, 2, '.', '');
        } else {
            return number_format(0, 2, '.', '');
        }
    }

    private function getTotalComissionAvailable(User $user)
    {
        $currentDate = Carbon::now();
        $dayThreshold = 15;
        $cardAvailable = false;
        $subMonth = $currentDate->subMonth()->month;
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($subMonth >= 2) {
            $subMonth = $subMonth - 1;

        } else {
            $subMonth = 12;
            $currentYear = $currentYear - 1;
        }

        if ($subMonth < 9) {
            $subMonth = '0' . $subMonth;
        }
        $farst_day = date('t', mktime(0, 0, 0, $subMonth, '01', $currentYear));

        $dateComplete = $currentYear . '-' . $subMonth . '-' . $farst_day . ' 23:59:59';

        $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        if ($currentDate->day >= $dayThreshold) {
            $cardAvailable = true;
            $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
            $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

            $availableComission = DB::table('banco')
                ->where('user_id', $user->id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete2)
                ->sum('price');
        } else {
            $availableComission = DB::table('banco')
                ->where('user_id', $user->id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete)
                ->sum('price');
        }

        // dd('Se for 16 ou mais ' . $dateComplete2, 'Menor que 16 ' . $dateComplete);

        $retiradasTotais = DB::table('banco')
            ->where('user_id', $user->id)
            ->where('price', '<', 0) // Considera apenas valores negativos
            ->sum('price');

        $retiradasTotais = -$retiradasTotais;

        if ($retiradasTotais >= $availableComission) {
            $availableComission = 0;
        } else {
            $availableComission = $availableComission - $retiradasTotais;
        }

        $nomeMesAtual = Carbon::now()->monthName;
        $mesAnterior = Carbon::now()->subMonth()->monthName;
        $mesAntesDoAnterior = Carbon::now()->subMonths(2)->monthName;

        if ($cardAvailable) {
            return [
                "month_available" => $mesAnterior,
                "month_not_available" => $nomeMesAtual,
                "value" => number_format($availableComission, 2, '.', '')
            ];
        } else {
            return [
                "month_available" => $mesAntesDoAnterior,
                "month_not_available" => "$nomeMesAtual, $mesAnterior",
                "value" => number_format($availableComission, 2, '.', '')
            ];
        }
    }
    private function getDownlineVolume(User $user)
    {
        $user_id = $user->id;

        $indirectVolume = HistoricScore::where('user_id', $user_id)
            ->where('level_from', '>', 1)
            ->whereMonth('created_at', now()->format('m'))
            ->whereYear('created_at', now()->format('Y'))
            ->sum('score');

        $directVolume = HistoricScore::where('user_id', $user_id)
            ->where('level_from', 1)
            ->whereMonth('created_at', now()->format('m'))
            ->whereYear('created_at', now()->format('Y'))
            ->sum('score');

        $totalVolume = $indirectVolume + $directVolume;

        return number_format($totalVolume, 2, '.', '');
    }

    private function getPersonalVolume(User $user)
    {
        $directCustomers = EcommRegister::where('recommendation_user_id', $user->id)->get('id');

        $firstDayOfLastMonth = Carbon::now()->startOfMonth();

        $lastDayOfLastMonth = Carbon::now()->endOfMonth();

        $personalVolumelast = HistoricScore::where('user_id', $user->id)
            ->where('level_from', 0)
            ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->sum('score');

        $personalVolumelast = isset($personalVolumelast[0]->{'total'}) ? $personalVolumelast[0]->{'total'} : 0;

        $personalVolumelastEcomm = 0;

        if (count($directCustomers) > 0) {
            foreach ($directCustomers as $value) {
                $idEcomm = $value->id;
                $qvEcomm = EcommOrders::where('id_user', $idEcomm)
                    ->where('client_backoffice', 0)
                    ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
                    ->whereIn('number_order', function ($query) {
                        $query->select('number_order')
                            ->from('payments_order_ecomms')
                            ->where('status', 'paid');
                    })
                    ->sum('qv');

                $personalVolumelastEcomm += $qvEcomm;
            }
        }

        $personalVolumelast = $personalVolumelast + $personalVolumelastEcomm;

        return number_format($personalVolumelast, 2, '.', '');
    }
    private function getTotalIndirect(User $user)
    {
        $indiretos = HistoricScore::where('user_id', $user->id)
            ->where('level_from', '>', 1)
            ->distinct('user_id_from')
            ->count('user_id_from');

        return $indiretos ?? 0;
    }
    private function getTotalDistributor(User $user)
    {
        return $this->getTotalIndirect($user) + $this->getTotalDirectDistributor($user);
    }
    private function getTotalDirectDistributor(User $user)
    {
        $diretos = HistoricScore::where('user_id', $user->id)
            ->where('level_from', 1)
            ->distinct('user_id_from')
            ->count('user_id_from');

        return $diretos ?? 0;
    }

    private function getDownlineVolumeLastMonth(User $user)
    {
        $user_id = $user->id;

        $firstDayOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastDayOfLastMonth = Carbon::now()->subMonth()->endOfMonth();


        $indirectVolume = HistoricScore::where('user_id', $user_id)
            ->where('level_from', '>', 1)
            ->whereMonth('created_at', now()->format('m'))
            ->whereYear('created_at', now()->format('Y'))
            ->sum('score');

        $directVolume = HistoricScore::where('user_id', $user_id)
            ->where('level_from', 1)
            ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->sum('score');

        $totalVolume = $indirectVolume + $directVolume;

        return number_format($totalVolume, 2, '.', '');
    }

    private function getPersonalVolumeLastMonth(User $user)
    {
        $directCustomers = EcommRegister::where('recommendation_user_id', $user->id)->get('id');

        $firstDayOfLastMonth = Carbon::now()->subMonth()->startOfMonth();

        $lastDayOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $personalVolumelast = HistoricScore::where('user_id', $user->id)
            ->where('level_from', 0)
            ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->sum('score');

        $personalVolumelast = isset($personalVolumelast[0]->{'total'}) ? $personalVolumelast[0]->{'total'} : 0;

        $personalVolumelastEcomm = 0;

        if (count($directCustomers) > 0) {
            foreach ($directCustomers as $value) {
                $idEcomm = $value->id;
                $qvEcomm = EcommOrders::where('id_user', $idEcomm)
                    ->where('client_backoffice', 0)
                    ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
                    ->whereIn('number_order', function ($query) {
                        $query->select('number_order')
                            ->from('payments_order_ecomms')
                            ->where('status', 'paid');
                    })
                    ->sum('qv');

                $personalVolumelastEcomm += $qvEcomm;
            }
        }

        $personalVolumelast = $personalVolumelast + $personalVolumelastEcomm;

        return number_format($personalVolumelast, 2, '.', '');
    }

    private function getStatus(User $user)
    {
        $active = PaymentOrderEcomm::where('id_user', $user->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'paid')
            ->sum('total_price');

        return $active >= 100 ? 'Active' : 'Inactive';
    }

    private function getGreatestCareer(User $user)
    {
        $greatest_career = CareerUser::where('user_id', $user->id)->orderBy('career_id', 'desc')->limit(1)->get();

        if (count($greatest_career) > 0) {
            $id_greatest_career = $greatest_career[0]->career_id;

            $greatest_career_user = Career::where('id', $id_greatest_career)->first();
        } else {
            $greatest_career_user = null;
        }

        $data = null;

        if (isset($greatest_career_user) && isset($greatest_career_user->id)) {
            $created_at = '';
            if (isset($greatest_career_user->created_at)) {
                $created_at = date('Y-m-d', strtotime($greatest_career_user->created_at));
            }

            $data = [
                "img" => asset("/images/Badges/" . $greatest_career_user->id . ".png"),
                "name" => $greatest_career_user->name,
                "created_at" => $created_at,
            ];
        } else {
            $data = [
                "img" => asset("/images/nolimitslogo.png"),
                "name" => null,
                "created_at" => null,
            ];
        }

        return $data;
    }

    private function getPerformanceCareer(User $user)
    {
        try {
            //code...

            $controllerHome = new HomeController;
            $career = $controllerHome->careerProgress($user->id);
            // return $career;

            $proximaCarreira = $career['proximaCarreira'];
            $percentProgress = $career['percentProgress'];
            $soma = $career['soma'];

            $id_greatest_career = CareerUser::where('user_id', $user->id)->orderBy('id', 'desc')->limit(1)->get();
            $data_final = date('Y-m');

            if (count($id_greatest_career) > 0) {
                $id_greatest_career = $id_greatest_career[0]->career_id;

                $greatest_career_user = Career::where('id', $id_greatest_career)->first();

                $id_career_user = CareerUser::where('user_id', $user->id)
                    ->where('created_at', 'like', '%' . $data_final . '%')
                    ->orderBy('career_id', 'DESC')
                    ->limit(1)
                    // ->pluck('career_id')
                    ->first();


                if (isset($id_career_user)) {
                    $id_career_user = $id_career_user->career_id;

                    $career_user = Career::where('id', $id_greatest_career)->first();

                } else {
                    $career_user = '';
                }


            } else {
                $greatest_career_user = '';
                $career_user = '';
            }

            $data = null;

            if (isset($career_user) && isset($career_user->id)) {
                $created_at = '';
                if (isset($career_user->created_at)) {
                    $created_at = date('Y-m-d', strtotime($career_user->created_at));
                }
                $data = [
                    "img" => asset("/images/Badges/" . $greatest_career_user->id . ".png"),
                    "name" => $career_user->name,
                    "created_at" => $created_at,
                    "percent_progress" => $percentProgress
                ];
            } else {
                $data = [
                    "img" => asset("/images/nolimitslogo.png"),
                    "name" => null,
                    "created_at" => null,
                    "percent_progress" => null
                ];
            }

            return $data;
        } catch (\Throwable $th) {
            return [
                "img" => asset("/images/nolimitslogo.png"),
                "name" => null,
                "created_at" => null,
                "percent_progress" => null
            ];
        }
    }

    private function getMyDirectsWithQV(User $user)
    {
        try {
            //code...

            $usersDirects = User::where('recommendation_user_id', $user->id)->get();

            $quantQv = 0;
            $users = [];
            foreach ($usersDirects as $userr) {
                $my_qv = 0;

                $payments = PaymentOrderEcomm::where('id_user', $userr->id)->where('status', 'paid')->get();

                foreach ($payments as $payment) {
                    $my_qv += DB::table('ecomm_orders')
                        ->where('number_order', $payment->number_order)
                        ->sum('qv');
                }

                if ($my_qv > 0) {
                    $quantQv++;

                    array_push($users, [
                        "name" => $userr->name . " " . $userr->last_name ?? '',
                        "login" => $userr->login,
                        "qv" => number_format($my_qv, 2, '.', ''),
                    ]);
                }

                $userr->qv = $my_qv;

            }

            return [
                "quant_directs" => count($usersDirects),
                "quant_directs_qv" => count($users),
                "directs_qv" => $users,
            ];
        } catch (\Throwable $th) {
            return [
                "quant_directs" => null,
                "quant_directs_qv" => null,
                "directs_qv" => null,
            ];
        }
    }

    private function getMyOrdersSmart(User $user)
    {
        try {
            $id_user = $user->id;
            $controllerHome = new HomeController;

            $pedidosSmart = \DB::table('ecomm_orders')
                ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
                ->where('ecomm_orders.smartshipping', 1)
                ->where('payments_order_ecomms.status', 'paid')
                ->where('payments_order_ecomms.id_user', $id_user)
                ->select('payments_order_ecomms.*')
                ->distinct()
                ->get();

            $orders = [];
            foreach ($pedidosSmart as $pedido) {
                $nextDayPay = null;

                $pedido->qv = EcommOrders::where('number_order', $pedido->number_order)
                    ->sum('qv');

                $diaPreferido = BestDatePaymentSmartshipping::where('number_order', $pedido->number_order)->first();

                if (isset($diaPreferido)) {
                    $diaEscolhido = $diaPreferido->day;
                    $quandoEscolheu = $diaPreferido->updated_at;
                    $currentMonth = date('m');
                    $currentDate = now();
                    $mesesDiferenca = $currentDate->diffInMonths($quandoEscolheu);


                    if ($mesesDiferenca >= 1) { // no mes seguinte após ter feito a escolha
                        // dd($diaPreferido);
                        $segundoPagamento = $controllerHome->existeSegundoPagamento($pedido->number_order);

                        if ($segundoPagamento != false) { // se existir no minimo uma segunda cobrança
                            $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                            $nextDayPay = $umMesDepois->day($diaEscolhido);
                        } else {

                            $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();
                            $nextDayPay = $umMesDepois->day($diaEscolhido);
                        }
                    } else {
                        $segundoPagamento = $controllerHome->existeSegundoPagamento($pedido->number_order);

                        if ($segundoPagamento != false) {
                            $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                            $nextDayPay = $umMesDepois;

                        } else {
                            $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();
                            $nextDayPay = $umMesDepois;
                        }
                    }

                } else {
                    $segundoPagamento = $controllerHome->existeSegundoPagamento($pedido->number_order);

                    if ($segundoPagamento != false) {
                        $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                        $nextDayPay = $umMesDepois;
                    } else {
                        $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();

                        $nextDayPay = $umMesDepois;
                    }
                }

                $pedido->nextDayPay = $nextDayPay;

                array_push($orders, [
                    "order" => $pedido->number_order,
                    "created_at" => date('d/m/Y', strtotime($pedido->created_at)),
                    "next_payment" => date('d/m/Y', strtotime($nextDayPay)),
                    "price" => $pedido->total_price,
                    "QV" => $pedido->qv,
                ]);

            }


            return [
                "quant" => count($orders),
                "orders" => $orders,
            ];
        } catch (\Throwable $th) {
            return [
                "quant" => null,
                "orders" => null,
            ];
        }
    }

    private function getNewEnrollments(User $user)
    {
        try {
            $currentDate = Carbon::now();
            $firstDayLastMonth = $currentDate->subMonth()->startOfMonth()->startOfDay()->toDateString();
            $lastDayLastMonth = $currentDate->copy()->endOfMonth()->endOfDay()->toDateString();

            $pontosPorUser = HistoricScore::with('userFrom')->where('user_id', $user->id)
                ->where('created_at', '>=', "$firstDayLastMonth 00:00:00")
                ->where('created_at', '<=', "$lastDayLastMonth 23:59:59")
                ->where('score', '<>', 0)
                ->where('level_from', '>', 0)->where('level_from', '<', 10)
                ->whereHas('userFrom', function ($query) use ($firstDayLastMonth, $lastDayLastMonth) {
                    $query->where('created_at', '>=', "$firstDayLastMonth 00:00:00")
                        ->where('created_at', '<=', "$lastDayLastMonth 23:59:59");
                })
                ->selectRaw('sum(score) as total, user_id_from')
                ->groupBy('user_id_from')
                ->get();

            $data = [];

            foreach ($pontosPorUser as $item) {
                $name = $item->userFrom->name ?? '';
                $lastName = $item->userFrom->last_name ?? '';

                $fullName = $name . " " . $lastName;
                $tt = isset($item->total) ? $item->total : 0;

                array_push($data, [
                    "direct" => $fullName,
                    "direct_qv" => number_format($tt, 2, '.', '')
                ]);
            }

            return $data;

        } catch (\Throwable $th) {
            return null;
        }

    }

    private function bonusList(User $user)
    {
        try {

            $seisMesesAtras = Carbon::now()->subMonths(6)->startOfMonth();
            $now = Carbon::now()->startOfMonth();

            $bonus = Banco::select(
                DB::raw('sum(price) as total'),
                DB::raw("CONCAT(MONTH(created_at), '/', YEAR(created_at)) as date"),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
                ->where('user_id', $user->id)
                ->whereDate('created_at', '>=', $seisMesesAtras)
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'), DB::raw("CONCAT(MONTH(created_at), '/', YEAR(created_at))"))
                ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
                ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
                ->get();

            foreach ($bonus as $bn) {
                $bn->total = number_format($bn->total, 2);
                $dateObj = \DateTime::createFromFormat('!m', $bn->month);
                $bn->month_name = $dateObj->format('M');
            }

            return [
                "date" => [
                    "start_month" => date('m/Y', strtotime($seisMesesAtras)),
                    "end_month" => date('m/Y', strtotime($now)),
                ],
                "bonus" => $bonus
            ];
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return [];
        }
    }
    private function getNewRankAdvancement(User $user)
    {
        try {
            $rankAdvancement = DB::select("SELECT name, last_name, created_at, career, id,
            (SELECT COUNT(*)
            FROM career_users C2
            WHERE C2.user_id = subquery.user_id
            AND C2.career_id = subquery.career_id) AS count_career_id
        FROM (
            SELECT U.id AS user_id, U.name, U.last_name, C.created_at, C1.name AS career, C1.id,
                ROW_NUMBER() OVER (PARTITION BY U.id ORDER BY C.career_id DESC) AS row_num,
                C.career_id
            FROM users U
            LEFT JOIN career_users C ON U.id = C.user_id
            LEFT JOIN career C1 ON C1.id = C.career_id
            WHERE U.recommendation_user_id = ? AND C.career_id > 2
        ) AS subquery
        WHERE row_num = 1", [$user->id]);

            $ranks = [];

            foreach ($rankAdvancement as $item) {
                if (isset($item->count_career_id) && $item->count_career_id == 1) {
                    $name = $item->userFrom->name ?? '';
                    $lastName = $item->userFrom->last_name ?? '';

                    $fullName = $name . " " . $lastName;

                    array_push($ranks, [
                        "name" => $fullName,
                        "ranking" => isset($item->career) ?? ''
                    ]);
                }
            }

            return $ranks;

        } catch (\Throwable $th) {
            return null;
        }


    }

    private function getQuantCart(User $user)
    {
        $cart = CartOrder::where('id_user', $user->id)->get();
        return count($cart);
    }

    private function clearCart(User $user)
    {
        $exists = CartOrder::where('id_user', $user->id)->get();
        if (count($exists) > 0) {
            DB::table('cart_orders')->where('id_user', $user->id)->delete();
        }
    }

    private function sendPostBonificacaoLogin(User $user, $prod = 1)
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
            "idusuario" => $user->id
        ];

        $url = 'https://lifeprosper.eu/public/compensacao/bonificacao.php';

        try {

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
                $log->user_id = $user->id;
                $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idusuario'];
                $log->controller = "app/controllers/api/ApiApp";
                $log->http_code = 200;
                $log->route = "login_app";
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
