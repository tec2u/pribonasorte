<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\CareerUser;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\OrderPackage;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\Rede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NetworkController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function mytree($parameter)
    {
        $rede = Rede::where('user_id', $parameter)->first();

        // nova condição de verificação remover depois
        if ($rede) {

            $name = empty($rede->upline_id) ? "" : Rede::find($rede->upline_id)->user->login;
            $login = $rede->user->login;
            $redename = $rede->user->name . ' ' . $rede->user->last_name ?? '';
            $id = $rede->id;
            $qty = $rede->qty;
            $email = $rede->user->email;
            $volume = $rede->user->getVolume($rede->user->id);
            $tag = '';
            $pay = OrderPackage::where('user_id', $rede->user->id)->where('status', 1)->where('payment_status', 1)->first();
            $getadessao = $rede->user->getAdessao($rede->user->id);
            $getpackages = $rede->user->getPackages($rede->user->id);
            if (!$pay) {
                $tag = ["Inactive"];
            }
            if ($getadessao > 0) {
                $tag = ["PreRegistration"];
            }
            if ($getpackages > 0) {
                $tag = ["AllCards"];
            }
            $rede_users = Rede::where('upline_id', $id)->get()->count();
            if ($rede_users > 0) {
                $network = $this->getNetwork($rede->id);
                $networks[] = array(
                    "id" => "$id",
                    "login" => "$login",
                    "name" => "$redename",
                    "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                    "size" => ".$qty",
                    "referred" => $name,
                    "email" => $email,
                    "volume" => "Volume: $volume",
                    "tags" => $tag
                );
                $networks = array_merge($network, $networks);
            } else {
                $network = $this->getNetwork($rede->id);
                $networks = array(
                    array(
                        "id" => "$id",
                        "login" => "$login",
                        "name" => "$redename",
                        "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                        "size" => ".$qty",
                        "referred" => $name,
                        "volume" => "Volume: $volume",
                        "tags" => $tag
                    )
                );
            }

            $id_user = Auth::id();
            $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
            $countPackages = count($openProduct);
            //$networks += $this->getNetwork($rede->id);
            // {
            //     id: 1,
            //     name: "Amber McKenzie",
            //     img: "https://cdn.balkan.app/shared/empty-img-none.svg",
            //     size: 10,
            //     referred: "master"
            // },
            $networks = json_encode($networks);
            //$networks = str_replace(array("\n", "\r"), '', $networks);
            // dd($networks);
            return view('network.rede', compact('networks', 'countPackages'));
        } else {
            $id_user = Auth::id();
            $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
            $countPackages = count($openProduct);

            $networks = 0;

            return view('network.rede', compact('networks', 'countPackages'));
        }
    }
    public function mytreediferente($parameter)
    {
        $rede = Rede::where('user_id', $parameter)->first();
        $name = empty($rede->upline_id) ? "" : Rede::find($rede->upline_id)->user->login;
        $redename = $rede->user->login;
        $id = $rede->id;
        $network = $this->getNetworkDiferente($rede->id);
        if ($network != NULL) {
            $networks['tree'] = array($id => $network['tree']);
            $networks['params'] = array(
                $id => array(
                    'trad' => $redename . ' </br>',
                    'styles' => array(
                        'font-weight' => '600',
                        'font-size' => '18px',
                        'background-color' => '#f3f3f37a',
                        'color' => 'red',
                        'box-shadow' => '0 0 4px 1px #aeaeae',
                        'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                    )
                )
            );
            $networks['params'] = $network['params'] + $networks['params'];
        } else {
            $networks['tree'] = array($id => '');
            $networks['params'] = array(
                $id => array(
                    'trad' => $redename . ' </br>',
                    'styles' => array(
                        'font-weight' => '600',
                        'font-size' => '18px',
                        'background-color' => '#f3f3f37a',
                        'color' => 'red',
                        'box-shadow' => '0 0 4px 1px #aeaeae',
                        'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                    )
                )
            );
        }

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $tree = json_encode($networks['tree']);
        $params = json_encode($networks['params']);
        return view('network.rede_diferente', compact('tree', 'params', 'countPackages'));
    }
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function myreferrals()
    {
        $id_user = Auth::id();
        $rede = Rede::where('user_id', $id_user)->first();

        if ($rede) {
            $networks = Rede::where('upline_id', $rede->id)->get();
        } else {
            $networks = [];
        }

        // dd($networks);

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);


        return view('network.myreferrals', compact('networks', 'countPackages'));
    }
    public function getNetwork($id, $cont = '')
    {
        $cont = empty($cont) ? 1 : $cont;
        $rede_users = Rede::where('upline_id', $id)->get();
        $networks = array();
        foreach ($rede_users as $rede) {
            $name = empty(Rede::find($rede->upline_id)) ? "" : Rede::find($rede->upline_id)->first()->user->login;
            $login = $rede->user->login;
            $redename = $rede->user->name . ' ' . $rede->user->last_name ?? '';
            $id = $rede->id;
            $qty = $rede->qty;
            $upline = $rede->upline_id;
            $volume = $rede->user->getVolume($rede->user->id);
            $tag = '';
            $pay = OrderPackage::where('user_id', $rede->user->id)->where('status', 1)->where('payment_status', 1)->first();
            $getadessao = $rede->user->getAdessao($rede->user->id);
            $getpackages = $rede->user->getPackages($rede->user->id);
            if (!$pay) {
                $tag = ["Inactive"];
            }
            if ($getadessao > 0) {
                $tag = ["PreRegistration"];
            }
            if ($getpackages > 0) {
                $tag = ["AllCards"];
            }
            $email = $rede->user->email;
            $referral_rede = Rede::where('id', $upline)->first();
            $referral_user = User::where('id', $referral_rede->user_id)->first();
            $networks[] = array(
                "id" => "$id",
                "pid" => "$upline",
                "login" => "$login",
                "name" => "$redename",
                "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                "size" => "$qty",
                "referred" => $referral_user->login,
                "email" => $email,
                "volume" => "Volume: $volume ",
                "rede_id" => $rede->user->id,
                "btn" => "<a href='" . route('networks.mytree', ['parameter' => $rede->user->id]) . "'> More + </a>",
                "tags" => $tag
            );
            $cont++;
            if ($cont < 8) {
                $networks = array_merge($this->getNetwork($rede->id, $cont), $networks);
            }
        }
        //dd($networks);
        return $networks;
    }
    private function getNetworkDiferente($parameter)
    {
        $redes = Rede::where('upline_id', $parameter)->get();
        if ($redes == NULL)
            return NULL;
        $networks = array();
        foreach ($redes as $rede) {
            $redename = $rede->user->login;
            $id = $rede->id;
            $network = $this->getNetworkDiferente($rede->id);
            if ($network != NULL) {
                if (isset($networks['tree'])) {
                    $networks['tree'] = $networks['tree'] + array($id => $network['tree']);
                    $networks['params'] = $networks['params'] + array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('networks.mytree', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                } else {
                    $networks['tree'] = array($id => $network['tree']);
                    $networks['params'] = array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('networks.mytree', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                }
                $networks['params'] = $network['params'] + $networks['params'];
            } else {
                if (isset($networks['tree'])) {
                    $networks['tree'] = $networks['tree'] + array($id => '');
                    $networks['params'] = $networks['params'] + array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('networks.mytree', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                } else {
                    $networks['tree'] = array($id => '');
                    $networks['params'] = array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('networks.mytree', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                }
            }
        }
        return $networks;
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function associatesReport(Request $request)
    {
        $id_user = Auth::id();
        $rede = Rede::where('user_id', $id_user)->first();

        $now_month = date('m');
        $now_year = date('Y');

        $year = $request->year;
        $month = $request->month;

        // $networks = User::where('recommendation_user_id', $id_user)->get();

        // $meus_diretos = DB::select("SELECT count(distinct(user_id_from)) as total FROM historic_score where user_id = $id_user and level_from = 1");

        // dd($nova_rede, $meus_diretos);
        // exit();
        // $recommendation_user_id = 


        // if ($rede) {

        // $networks = Rede::where('upline_id', $rede->id)->get();
        // $new_rede = Rede::where('upline_id', $rede->id)->get();

        // dd($networks);
        // exit();

        // SELECT sum(score),user_id FROM historic_score where  user_id in (select id from users where recommendation_user_id=116091) 
        // and created_at like '%2024-01%' group by user_id;

        // $totalnet = DB::select("SELECT * FROM historic_score where user_id = $id_user and level_from = 1 and DATE_FORMAT(updated_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')");

        // $networks = array();

        // $totalnet = DB::select("SELECT sum(score),user_id FROM historic_score where user_id in (select id from users where recommendation_user_id = $id_user) group by user_id");

        // dd($totalnet, $nova_rede);
        // exit();

        // foreach ($totalnet as $list_user) {

        //     $user_array = User::where('id', $list_user->user_id)->first();
        //     array_push($networks, $user_array);
        // }

        //     $totalnot = DB::select("SELECT * FROM historic_score where user_id = $id_user and level_from > 1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')");

        //     $diretos_qr = DB::select("SELECT sum(score) as total FROM historic_score where user_id = $id_user and level_from = 1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')");

        //     $indiretos_qr = DB::select("SELECT sum(score) as total FROM historic_score where user_id = $id_user and level_from > 1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')");

        //     $totalScore = $totalnot;

        // } else {
        //     $networks = [];
        //     $totalScore = 0;
        // }

        if ($request->month && $request->year) {

            $networks = DB::select("SELECT * FROM users WHERE recommendation_user_id = $id_user");

            $openProduct = DB::select("SELECT * FROM orders_package WHERE EXTRACT(month from created_at) = $month AND EXTRACT(year from created_at) = $year");
            $countPackages = count($openProduct);
        } else {
            $networks = User::where('recommendation_user_id', $id_user)->get();

            $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
            $countPackages = count($openProduct);
        }

        return view('network.associatesReport', compact('networks', 'countPackages', 'id_user', 'month', 'year'));
    }

    public function pesquisa(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->login;

            // $rede = DB::select('SELECT users.login as login,
            //         users.country as country,
            //         users.id as user_id,
            //         users.email as email,
            //         users.name as name,
            //         user2.login as patrocinador,
            //         -- score1.score as score,
            //         rede.id as id
            //     FROM rede
            //     JOIN users on rede.user_id = users.id
            //     JOIN rede rede2 on rede2.id = rede.upline_id
            //     JOIN users user2 on rede2.user_id = user2.id
            //     -- JOIN historic_score score1 on rede.user_id = score1.user_id
            //     WHERE rede.upline_id = ?',
            //     [$id]
            // );

            $rede = User::where('recommendation_user_id', $id)->get();

            if (empty(sizeof($rede))) {
                return response()->json(['error' => '0 results found']);
            } else {
                $rede = collect($rede)->unique('id')->values();

                foreach ($rede as $value) {
                    $iduser = $value->id;
                    $year = $request->year;
                    $month = $request->month;

                    $volume = DB::select("SELECT sum(score) as total FROM historic_score where user_id=$iduser AND YEAR(created_at) = $year AND MONTH(created_at) = $month");
                    $volume = isset($volume[0]->{'total'}) ? $volume[0]->{'total'} : 0;

                    $value->volume = $volume;

                    $pat = User::where('id', $value->recommendation_user_id)->first();
                    if (isset($pat)) {
                        $value->patrocinador = $pat->login;
                    } else {
                        $value->patrocinador = '';
                    }
                }
                return response()->json($rede);
            }
        }
        return view('network.associatesReport');
    }

    public function IndicationEcomm()
    {
        $indication = EcommRegister::where("recommendation_user_id", Auth::id())->get();
        $array_user = array();

        foreach ($indication as $data) {

            $my_qv = 0;
            $qvs = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

            foreach ($qvs as $payment) {
                $my_qv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('qv');
            }

            $data_user = [
                'id' => $data->id,
                'name' => $data->name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'phone' => $data->phone,
                'register' => $data->created_at,
                'qv' => $my_qv,

            ];

            if (!in_array($data_user, $array_user)) {

                array_push($array_user, $data_user);
            }
        }

        return view('network.IndicationEcomm', compact('indication', 'array_user'));
    }

    public function IndicationEcommOrders($id)
    {
        $indication = EcommRegister::where("recommendation_user_id", Auth::id())
            ->where('id', $id)
            ->first();

        $ordersIds = \DB::table('ecomm_orders')
            ->select(\DB::raw('MIN(id) as id'))
            ->where('id_user', $id)
            ->groupBy('number_order')
            ->pluck('id');

        $orders = EcommOrders::whereIn('id', $ordersIds)
            ->orderBy('updated_at', 'DESC')
            ->paginate(9);

        foreach ($orders as $value) {
            $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
            $value['payment'] = $payment->status;
            $value['t_qv'] = DB::table('ecomm_orders')
                ->where('number_order', $value->number_order)
                ->sum('qv');

            $value['total'] = $payment->total_price;
        }

        return view('network.IndicationEcommOrders', compact('indication', 'orders'));
    }

    public function IndicationEcommOrdersFilter($id)
    {
        $indication = EcommRegister::where("recommendation_user_id", Auth::id())
            ->where('id', $id)
            ->first();

        $ordersIds = DB::table('ecomm_orders')
            ->select(DB::raw('MIN(id) as id'))
            ->where('id_user', $id)
            ->groupBy('number_order')
            ->pluck('id');

        $orders = EcommOrders::whereIn('id', $ordersIds)
            ->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', (date('m')))
            ->orderBy('updated_at', 'DESC')
            ->paginate(9);

        foreach ($orders as $value) {
            $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
            $value['payment'] = $payment->status;
            $value['total'] = $payment->total_price;
        }

        return view('network.IndicationEcommOrders', compact('indication', 'orders'));
    }

    public function IndicationEcommOrdersDetail($order)
    {
        $orderDetails = EcommOrders::where('number_order', $order)->get();

        $payment = PaymentOrderEcomm::where('id', $orderDetails[0]->id_payment_order)->first();
        $orderDetails[0]['payment'] = $payment->status;

        foreach ($orderDetails as $value) {
            $product = Product::where('id', $value->id_product)->first();
            $value['name_product'] = $product->name;
            $value['image_product'] = $product->img_1;
        }

        return view('network.IndicationEcommOrdersDetail', compact('orderDetails'));
    }

    public function mycareer()
    {
        $careers = Career::all();
        $latestCareerIds = CareerUser::selectRaw('MAX(id) as max_id')
            ->where('user_id', Auth::id())
            ->groupBy('career_id')
            ->pluck('max_id');

        $careerAchieved = CareerUser::whereIn('id', $latestCareerIds)
            ->get();

        foreach ($careers as $carreira) {
            foreach ($careerAchieved as $conquistada) {
                if ($carreira->id == $conquistada->career_id) {
                    $carreira->achieved = true;
                    $carreira->dt_achieved = $conquistada->created_at;
                }
            }
        }

        // dd($careers);
        $data_atual = date('Y-m');
        // $data_atual = "2023-09";

        $logado = Auth::id();
        $id_logado = Auth::id();
        $directs = 0;
        $sum = 0;
        $conta = 0;
        $users_lista = [];

        ####PEGA A PONTUACAO DA DATA ATUAL DAS COMPRAS DE SI PROPRIO
        // $minha_pontuacao = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(score) as pontos_ac_m FROM historic_score WHERE user_id = '{$id_logado}' AND created_at LIKE '%{$data_atual}%' and
        // level_from=0 "));

        $minha_pontuacao = DB::table('historic_score')
            ->where('user_id', $id_logado)
            ->where('level_from', 0)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = '$data_atual'")
            ->sum('score');

        //         $PersonalVolumeExternal = mysqli_fetch_array(mysqli_query($con, "select sum(qv) as total from ecomm_orders  where
// id_user in (select id_user from ecomm_registers where recommendation_user_id='$id_logado') and created_at like '%" . date('Y-m') . "%' and number_order in (select number_order from payments_order_ecomms where status='paid')"));

        $PersonalVolumeExternal = 0;
        $directCustomers = DB::select("SELECT id FROM ecomm_registers WHERE recommendation_user_id = $id_logado");

        if (count($directCustomers) > 0) {
            foreach ($directCustomers as $value) {
                $idEcomm = $value->id;
                $qvEcomm = DB::select("SELECT SUM(qv) AS total FROM ecomm_orders WHERE id_user=$idEcomm AND client_backoffice = 0 AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND number_order IN (SELECT number_order FROM payments_order_ecomms WHERE status = 'paid')");
                $PersonalVolumeExternal += isset($qvEcomm[0]->{'total'}) ? $qvEcomm[0]->{'total'} : 0;
            }
        }

        $meus_pontos = $minha_pontuacao + $PersonalVolumeExternal;

        // $carreira_atual = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM career_users WHERE user_id = '{$id_logado}' and created_at like '%" . date('Y-m') . "%' ORDER BY career_id DESC LIMIT 1 "));
        // if ($carreira_atual['career_id'] == null) {
        //     $ordem = 1;
        // } else {
        //     $ordem = $carreira_atual['career_id'] + 1;
        // }

        $carreira_atual = CareerUser::where('user_id', $id_logado)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = '$data_atual'")
            ->orderByDesc('career_id')
            ->limit(1)
            ->first();

        if (!isset($carreira_atual)) {
            $ordem = 1;
        } else {
            $ordem = $carreira_atual->career_id + 1;
        }

        // $carreiras = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM career WHERE id = '{$ordem}' "));

        $carreiras = Career::where('id', $ordem)->first();

        if (isset($carreiras)) {
            $verifica_diretos = User::where('recommendation_user_id', $id_logado)->get();

            foreach ($verifica_diretos as $array_verifica_diretos) {
                $id_indi_diretos = $array_verifica_diretos->id;


                $verifica_pontuacao_rede = DB::table('historic_score')
                    ->where('user_id', $id_indi_diretos)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = '$data_atual'")
                    ->sum('score');

                if ($verifica_pontuacao_rede >= $carreiras->MaximumVolumeD) {
                    $valor_aproveitado = $carreiras->MaximumVolumeD;
                } else {
                    $valor_aproveitado = $verifica_pontuacao_rede;
                }

                if ($carreiras->bonus <= $verifica_pontuacao_rede) {
                    $directs++;
                }

                $sum += $valor_aproveitado;
                $conta++;

                $users_lista[$id_indi_diretos] = [
                    "name" => $array_verifica_diretos->name,
                    "last_name" => $array_verifica_diretos->last_name,
                    "aproveitado" => $valor_aproveitado,
                    "total" => $verifica_pontuacao_rede ?? 0,
                ];
            }

            $quantDiretos = $conta;
            $soma = $sum + $meus_pontos;
        }

        if ($ordem == 1) {
            $proximaCarreira = Career::where('id', $ordem + 1)->first();
        } else {
            $proximaCarreira = Career::where('id', $ordem)->first();
            if (!isset($proximaCarreira)) {
                $proximaCarreira = $carreira_atual;
            }
        }

        return view('network.myCareer', compact('careers', 'users_lista', 'soma', 'quantDiretos', 'meus_pontos', 'proximaCarreira', 'careerAchieved'));
    }

    public function IndicationEcommFilter(Request $request)
    {
        $date_in = str_replace("/", "-", $request->date_in);
        $date_out = str_replace("/", "-", $request->date_out);

        $indication = EcommRegister::where("recommendation_user_id", Auth::id())->get();
        $array_user = array();

        foreach ($indication as $data) {

            $file_date = $data->created_at;
            $file_date = explode(' ', $file_date);
            // $file_date = explode('-', $file_date[0]);

            $newdata = $file_date[0];

            if ($newdata >= $date_in and $newdata <= $date_out) {

                $my_qv = 0;
                $qvs = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

                foreach ($qvs as $payment) {
                    $my_qv += DB::table('ecomm_orders')
                        ->where('number_order', $payment->number_order)
                        ->sum('qv');
                }

                $data_user = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'last_name' => $data->last_name,

                    'email' => $data->email,
                    'phone' => $data->phone,
                    'register' => $data->created_at,
                    'qv' => $my_qv,
                ];

                if (!in_array($data_user, $array_user)) {

                    array_push($array_user, $data_user);
                }
            }
        }

        return view('network.IndicationEcomm', compact('indication', 'array_user'));
    }

    public function IndicationEcommFilterMonth()
    {
        $month_date = date('m');

        $indication = EcommRegister::where("recommendation_user_id", Auth::id())->get();
        $array_user = array();

        foreach ($indication as $data) {

            $file_date = $data->created_at;
            $file_date = explode(' ', $file_date);
            $file_date = explode('-', $file_date[0]);

            $monthdata = $file_date[1];

            if (intval($monthdata) == intval($month_date)) {

                $my_qv = 0;
                $qvs = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

                foreach ($qvs as $payment) {
                    $my_qv += DB::table('ecomm_orders')
                        ->where('number_order', $payment->number_order)
                        ->sum('qv');
                }

                $data_user = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'last_name' => $data->last_name,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'register' => $data->created_at,
                    'qv' => $my_qv,

                ];

                if (!in_array($data_user, $array_user)) {

                    array_push($array_user, $data_user);
                }
            }
        }

        return view('network.IndicationEcomm', compact('indication', 'array_user'));
    }
}
