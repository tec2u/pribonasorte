<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\Banco;
use App\Models\BestDatePaymentSmartshipping;
use App\Models\EcommOrders;
use App\Models\HistoricScore;
use App\Models\Package;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\ShippingPrice;
use App\Models\SmartshippingPaymentsRecurring;
use App\Models\Tax;
use App\Models\User;
use App\Models\Popup;
use App\Models\OrderPackage;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Alert;
use App\Models\Career;
use App\Models\CareerUser;
use Khill\Lavacharts\Lavacharts;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    // public function editaTaxa()
    // {

    //     $country = "XI";
    //     $value = 20;

    //     $pais = ShippingPrice::where('country_code', $country)->first();

    //     $taxs = Tax::where('country', $pais->country)->whereIn('product_id', [21, 24, 25, 26, 27])->get();


    //     foreach ($taxs as $item) {
    //         $item->value = $value;
    //         $item->save();
    //     }

    //     dd($taxs);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // $cron = new SmartshippingPaymentsRecurringController;
        // $cron->index();
        // dd('');

        $CareerUserVerify = new CareerUser();

        $verify = $CareerUserVerify->where('user_id', Auth::user()->id)->first();
        if ($verify) {
            $verify->update([
                'user_id' => Auth::user()->id,
                'career_id' => $CareerUserVerify->getStage(),
            ]);
        } else {
            $CareerUserVerify->insert([
                'user_id' => Auth::user()->id,
                'career_id' => $CareerUserVerify->getStage(),
            ]);
        }

        $id_user = Auth::id();
        $packages = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        // $orderpackages = OrderPackage::where('user_id', $id_user)->orderBy('id', 'DESC')->limit(5)->get();
        $user = User::where('id', $id_user)->first();
        $current_package = OrderPackage::where('user_id', $id_user)->first();
        $pacote = $user->orderPackage->first();

        $products = Product::inRandomOrder()->limit(10)->get();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        // session para liberar aba de produtos
        // if(!session('openProduct')){

        //     $countPackages = count($packages);
        //     session()->put('openProduct', $countPackages);

        // } else{
        //     $session_id_user = session('openProduct')[0]->user_id;

        //     if ($session_id_user != $id_user) {

        //         session()->forget('openProduct');

        //         session()->put('openProduct', $packages);
        //     }
        // }

        //   $career = CareerUser::where('user_id', $user->id)->max('career_id');
        //   //dd($career);
        //   if (isset($career)) {
        //      $carrer = Career::find($career);
        //   } else {
        //      $carrer = Career::find(1);
        //   }

        $carrer = new CareerUser();
        $recomendation = User::where('recommendation_user_id', $user->id)->where('activated', 0)->get();

        $inactiverights = count($recomendation);

        if (empty($pacote)) {
            $name = '';
        } else {
            $name = $pacote->reference;
        }

        $from = date('Y-m-d');
        $to = date('Y-m-d', strtotime("-30 days", strtotime($from)));


        $bonususer = Banco::where('user_id', $user->id)
            ->whereIn('description', [1, 2, 4, 5])
            ->where('created_at', '>=', "$to 00:00:00")
            ->where('created_at', '<=', "$from 23:59:59")
            ->selectRaw('sum(price) as total')
            ->first();

        if (empty($bonususer)) {
            $totalbanco = 0;
        } else {
            $totalbanco = $bonususer->total;
        }

        $bonusdaily = Banco::where('user_id', $user->id)
            ->whereIn('description', [1, 2, 4, 5])
            ->where('created_at', '>=', "$to 00:00:00")
            ->where('created_at', '<=', "$from 23:59:59")
            ->groupBy('user_id')
            ->selectRaw('sum(price) as total, user_id')
            ->first();

        if (empty($bonusdaily)) {
            $bonusdaily = 0;
        } else {
            $bonusdaily = $bonusdaily->total;
        }

        $pontos = HistoricScore::where('user_id', $user->id)
            ->where('created_at', '>=', "$to 00:00:00")
            ->where('created_at', '<=', "$from 23:59:59")
            ->selectRaw('sum(score) as total')
            ->first();

        $currentDate = Carbon::now();

        // Obtém o primeiro dia do mês passado
        $firstDayLastMonth = $currentDate->subMonth()->startOfMonth()->startOfDay()->toDateString();

        // Obtém o último dia do mês passado
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

        $rankAdvancement = DB::select("
            SELECT
                subquery.name,
                subquery.last_name,
                subquery.created_at,
                subquery.career,
                subquery.id,
                (SELECT COUNT(*)
                 FROM career_users C2
                 WHERE C2.user_id = subquery.user_id
                 AND C2.career_id = subquery.career_id) AS count_career_id
            FROM (
                SELECT
                    U.id AS user_id,
                    U.name,
                    U.last_name,
                    C.created_at,
                    C1.name AS career,
                    C1.id,
                    ROW_NUMBER() OVER (PARTITION BY U.id ORDER BY C.career_id DESC) AS row_num,
                    C.career_id
                FROM users U
                LEFT JOIN career_users C ON U.id = C.user_id
                LEFT JOIN career C1 ON C1.id = C.career_id
                WHERE U.recommendation_user_id = ? AND C.career_id > 2
            ) AS subquery
            WHERE subquery.row_num = 1
        ", [$user->id]);


        // dd($rankAdvancement);

        if (empty($pontos)) {
            $pontos = 0;
        } else {
            $pontos = $pontos->total;
        }

        $data = array();
        $datasaida = array();
        $label = array();

        $from = date('Y-m-d');
        $toinicio = date('Y-m-d', strtotime("-30 days", strtotime($from)));
        $saque = 0;
        for ($i = 1; $i < 31; $i++) {

            $to = date('Y-m-d', strtotime("+$i days", strtotime($toinicio)));
            $bonususer = Banco::where('user_id', $user->id)
                ->whereIn('description', [1, 2, 4, 5])
                ->where('created_at', '>=', "$to 00:00:00")
                ->where('created_at', '<=', "$to 23:59:59")
                ->groupBy('created_at')
                ->selectRaw('sum(price) as total, DATE_FORMAT(created_at, "%Y-%m-%d") as created_at')
                ->orderby('created_at')
                ->first();

            $bonussaida = Banco::where('user_id', $user->id)
                ->where('created_at', '>=', "$to 00:00:00")
                ->where('created_at', '<=', "$to 23:59:59")
                ->where('description', '=', 99)
                ->groupBy('created_at')
                ->selectRaw('sum(price) as total, DATE_FORMAT(created_at, "%Y-%m-%d") as created_at')
                ->orderby('created_at')
                ->first();

            if (empty($bonususer)) {
                $total = 0;
            } else {
                $total = $bonususer->total;
            }

            if (empty($bonussaida)) {
                $totalsaida = 0;
            } else {
                $totalsaida = abs($bonussaida->total);
            }

            $saque += $totalsaida;

            $labelbonus = array(
                date('m-d-Y', strtotime($to))
            );

            $databonus = array(
                $total
            );

            $databonussaida = array(
                $totalsaida
            );

            $data = array_merge($data, $databonus);
            $datasaida = array_merge($datasaida, $databonussaida);
            $label = array_merge($labelbonus, $label);
        }
        $datasaida = json_encode($datasaida);
        $label = json_encode(array_reverse($label));
        $data = json_encode($data);

        Alert::success(__('backoffice_alert.home_welcome') . " " . $user->login . "!");

        $popup = Popup::where('id', 1)->first();

        // if ($user->contact_id == NULL) {
        //    $complete_registration = "Please complete your registration:<br>";
        //    $array_att = array('last_name' => 'Last Name', 'address1' => 'Address 1', 'address2' => 'Address 2', 'postcode' => 'Postcode', 'state' => 'State', 'wallet' => 'Wallet');
        //    foreach ($user->getAttributes() as $key => $value) {
        //       if ($value == NULL && array_search($key, array('last_name', 'address1', 'address2', 'postcode', 'state', 'wallet'))) {
        //          $complete_registration .= "&nbsp;&nbsp;&bull;" . $array_att[$key] . "<br>";
        //       }
        //    }
        //    $complete_registration .= "<a style='color:#000'href='/users/users'>Click here to go to Your Info Page</a><br>";
        //    flash($complete_registration)->error();
        // }

        //     $message_home = "<h3>NOTICE</h3><br/>
        //   Our USDT package payment has been through instability on the last few days. It has been completely solved. Thank you for all your support and for all your cooperation with our amazing project. Soon, on April we are going to launch some amazing news and upgrades for all of you. <br/>
        //   Thank you for all of you sharing videos on the cards arriving at your houses. We will soon share it here with everyone. <br/>
        //   Stay tuned for more news.";

        // flash($message_home)->success();

        $kycstatus = 'Pending';
        $countOP = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->count();
        if ($countOP > 0) {
            $kycstatus = 'Approved';
        }


        $lava = new Lavacharts;
        $popularity = $lava->DataTable();
        $dataDiretos = DB::table('users')
            ->select(DB::raw('country , count(country) as countcountry'))
            ->groupBy('country')
            ->where('recommendation_user_id', '=', Auth::user()->id)
            ->get()->toArray();

        $dataIndiretos = $this->getIndirectCountriesCount(Auth::user()->id);

        $countcountry = [];

        foreach ($dataDiretos as $item) {
            array_push($countcountry, [$item->country, $item->countcountry]);
        }

        foreach ($dataIndiretos as $item) {
            array_push($countcountry, [$item['country'], $item['countcountry']]);
        }

        $agrupado = collect($countcountry)->groupBy(0);
        $resultado = [];

        // Loop pelos grupos e calcular a soma
        foreach ($agrupado as $chave => $grupo) {
            $soma = $grupo->sum(function ($item) {
                return $item[1];
            });
            $resultado[] = [$chave, $soma];
        }

        $countcountry = $resultado;

        $popularity->addStringColumn('Country')
            ->addNumberColumn('Users')
            ->addRows($countcountry);


        $lava->GeoChart('Popularity', $popularity, [
            'colorAxis' => ['minValue' => 0, 'colors' => ['#460c52', '#480d54']],
            'displayMode' => 'auto',
            'enableRegionInteractivity' => true,
            'keepAspectRatio' => true,
            'region' => 'world',
            'resolution' => 'countries',
            'sizeAxis' => null,
            'legend' => 'none',
        ]);

        $OpenMenu = OrderPackage::where('user_id', $id_user)
            ->where('payment_status', 1)
            ->where('status', 1)
            ->whereDate('updated_at', '>=', Carbon::now()->subMonth())
            ->get();

        $OpenMenuPackage = OrderPackage::where('user_id', $id_user)
            ->where('payment_status', 1)
            ->where('status', 1)
            ->whereDate('updated_at', '>=', Carbon::now()->subMonth())
            ->orderBy('id', 'desc')
            ->first();

        // $OpenMenuPackage = null;
        // $OpenMenu = null;
        $diasFaltantes = null;

        if (isset($OpenMenuPackage)) {
            $dataAtual = Carbon::now();
            $dataAtualizacao = $OpenMenuPackage->updated_at;

            $dataUmMesDepois = $dataAtualizacao->addMonth();

            $diasFaltantes = $dataAtual->diffInDays($dataUmMesDepois);
        }
        set_time_limit(240);

        $usersDirects = User::where('recommendation_user_id', $id_user)->get();

        $quantQv = 0;
        foreach ($usersDirects as $user) {
            $my_qv = 0;

            $payments = PaymentOrderEcomm::where('id_user', $user->id)->where('status', 'paid')->get();

            foreach ($payments as $payment) {
                $my_qv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('qv');
            }

            if ($my_qv > 0) {
                $quantQv++;
            }

            $user->qv = $my_qv;

        }

        $pedidosSmart = \DB::table('ecomm_orders')
            ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
            ->where('ecomm_orders.smartshipping', 1)
            ->where('payments_order_ecomms.status', 'paid')
            ->where('payments_order_ecomms.id_user', $id_user)
            // ->where('payments_order_ecomms.number_order', '20519568')
            ->select('payments_order_ecomms.*')
            ->distinct()
            ->get();


        foreach ($pedidosSmart as $pedido) {
            $nextDayPay = null;

            $pedido->qv = DB::table('ecomm_orders')
                ->where('number_order', $pedido->number_order)
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
                    $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);

                    if ($segundoPagamento != false) { // se existir no minimo uma segunda cobrança
                        $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                        $nextDayPay = $umMesDepois->day($diaEscolhido);
                    } else {

                        $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();
                        $nextDayPay = $umMesDepois->day($diaEscolhido);
                    }
                } else {
                    $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);

                    if ($segundoPagamento != false) {
                        $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                        $nextDayPay = $umMesDepois;

                    } else {
                        $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();
                        $nextDayPay = $umMesDepois;
                    }
                }

            } else {
                $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);

                if ($segundoPagamento != false) {
                    $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();
                    $nextDayPay = $umMesDepois;
                } else {
                    $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();

                    $nextDayPay = $umMesDepois;
                }
            }

            $pedido->nextDayPay = $nextDayPay;
        }

        $career = $this->careerProgress();
        $proximaCarreira = $career['proximaCarreira'];
        $percentProgress = $career['percentProgress'];
        $soma = $career['soma'];

        return view('home', ['lava' => $lava])->with(compact('products','soma', 'percentProgress', 'proximaCarreira', 'pedidosSmart', 'quantQv', 'usersDirects', 'pontosPorUser', 'rankAdvancement', 'OpenMenu', 'diasFaltantes', 'packages', 'name', 'user', 'data', 'label', 'datasaida', 'totalbanco', 'bonusdaily', 'pontos', 'saque', 'carrer', 'inactiverights', 'popup', 'kycstatus', 'countPackages'));
    }

    public function careerProgress($user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = Auth::id();
        }

        $careers = Career::all();
        $latestCareerIds = CareerUser::selectRaw('MAX(id) as max_id')
            ->where('user_id', $user_id)
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

        $logado = $user_id;
        $id_logado = $user_id;
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

        if ($soma > 0) {
            $atual = $soma;
        } else {
            $atual = 1;
        }

        $obj = 1;


        $porcentagemCompleta = ($atual / $obj) * 100;

        // dd($porcentagemCompleta);

        if ($porcentagemCompleta > 100) {
            $porcentagemCompleta = 100;
        }

        $porcentagemCompleta = floor($porcentagemCompleta);

        return [
            "proximaCarreira" => $proximaCarreira,
            "percentProgress" => $porcentagemCompleta,
            "soma" => $soma
        ];
    }

    public function existeSegundoPagamento($order)
    {
        $exists = SmartshippingPaymentsRecurring::where('number_order', $order)->latest('created_at')
            ->first();
        if (isset($exists)) {
            return $exists;
        } else {
            return false;
        }
    }

    private function getIndirectCountriesCount($userId)
    {
        $indirectCountries = DB::table('users')
            ->select('country', DB::raw('count(*) as countcountry'))
            ->where('recommendation_user_id', $userId)
            ->groupBy('country')
            ->get();

        $allIndirectCountries = [];

        foreach ($indirectCountries as $countryData) {
            $allIndirectCountries[] = [
                'country' => $countryData->country,
                'countcountry' => $countryData->countcountry,
            ];

            // Chamada recursiva para obter os países dos indiretos dos indiretos
            $allIndirectCountries = array_merge(
                $allIndirectCountries,
                $this->getIndirectCountriesCount($countryData->country)
            );
        }

        return $allIndirectCountries;
    }

    public function welcome(Request $request)
    {
        $packages = Package::where('type', 'packages')->where('activated', 1)->orderBy('price')->get();
        $products = Product::orderBy('id', 'ASC')->get();

        // return view('welcome.welcome', compact('packages'));

        if ($request->session()->has('redirect_buy')) {
            $valor = $request->session()->get('redirect_buy');
            $request->session()->remove('redirect_buy');
            if ($valor == 'admin') {
                return redirect()->route('login');
            } else {
                return redirect()->route('page.login.ecomm');
            }

        } else {
            return view('newsite.newsite', compact('packages', "products"));
        }

    }

    public function newsite(Request $request)
    {
        $packages = Package::where('type', 'packages')->where('activated', 1)->orderBy('price')->get();
        $products = Product::orderBy('id', 'ASC')->get();

        // return response()->json($packages);
        return view('newsite.newsite', compact('packages', "products"));


    }



    public function fees()
    {
        $packages = Package::where('type', 'packages')->where('activated', 1)->orderBy('price')->get();
        return view('welcome.fees', compact('packages'));
    }
}
