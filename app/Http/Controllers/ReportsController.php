<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\CartOrder;
use App\Models\ConfigBonus;
use App\Models\PaymentOrderEcomm;
use App\Models\ProductByCountry;
use App\Models\ShippingPrice;
use App\Models\SmartshippingPaymentsRecurring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CareerUser;
use App\Models\User;
use App\Models\OrderPackage;
use App\Models\HistoricScore;
use App\Models\OrderEcomm;
use App\Models\EcommRegister;
use App\Models\EcommOrders;
use App\Models\Product;
use App\Models\Tax;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupcommission()
    {
        $id_user = Auth::id();

        $scores = DB::table('historic_score')
            ->where('user_id', $id_user)
            ->where('level_from', '>', 0)
            ->where('level_from', '<', 6)
            ->join('ecomm_orders', 'historic_score.orders_package_id', '=', 'ecomm_orders.number_order')
            ->select(
                'ecomm_orders.id',
                'ecomm_orders.qv',
                'ecomm_orders.total',
                'ecomm_orders.id_product',
                'ecomm_orders.number_order',
                'ecomm_orders.id_user',
                'ecomm_orders.created_at',
                'historic_score.level_from',
            )
            ->paginate(9);

        $scores_date = DB::table('historic_score')
            ->where('user_id', $id_user)
            ->where('level_from', '>', 0)
            ->where('level_from', '<', 6)
            ->join('ecomm_orders', 'historic_score.orders_package_id', '=', 'ecomm_orders.number_order')
            ->select(
                'ecomm_orders.id',
                'ecomm_orders.qv',
                'ecomm_orders.total',
                'ecomm_orders.id_product',
                'ecomm_orders.number_order',
                'ecomm_orders.id_user',
                'ecomm_orders.created_at',
                'historic_score.level_from',
            )
            ->get();

        $total_qv = 0;
        $total_ordens = 0;

        foreach ($scores_date as $scrv) {

            $total_qv = $total_qv + $scrv->qv;
            $total_ordens = $total_ordens + $scrv->total;
        }

        $first_date = DB::table('ecomm_orders')->orderBy('created_at', 'asc')->first();
        $exp_first = date('Y-m-d', strtotime($first_date->created_at));

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.signupcommission', compact('scores', 'countPackages', 'total_qv', 'total_ordens', 'exp_first'));
    }

    public function signupcommission_filter(Request $request)
    {
        $id_user = Auth::id();
        $month = $request->month;
        $year = $request->year;

        $scores_date = DB::table('historic_score')
            ->where('user_id', $id_user)
            ->where('level_from', '>', 0)
            ->where('level_from', '<', 6)
            ->whereYear('ecomm_orders.created_at', $year)
            ->whereMonth('ecomm_orders.created_at', $month)
            ->join('ecomm_orders', 'historic_score.orders_package_id', '=', 'ecomm_orders.number_order')
            ->select(
                'ecomm_orders.id',
                'ecomm_orders.qv',
                'ecomm_orders.total',
                'ecomm_orders.id_product',
                'ecomm_orders.number_order',
                'ecomm_orders.id_user',
                'ecomm_orders.created_at',
                'historic_score.level_from',
            )
            ->get();

        $scores = array();
        $total_qv = 0;
        $total_ordens = 0;

        foreach ($scores_date as $scr) {

            $date = $scr->created_at;
            $exp_date = explode(' ', $date);

            $total_qv = $total_qv + $scr->qv;
            $total_ordens = $total_ordens + $scr->total;

            array_push($scores, $scr);

        }

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $filter = "on";

        return view('report.signupcommission', compact('scores', 'countPackages', 'filter', 'month', 'year', 'total_qv', 'total_ordens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rankReward()
    {
        $id_user = Auth::id();
        $career_users = CareerUser::where('user_id', $id_user)->orderBy('id', 'desc')->paginate(9);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.rankreward', compact('career_users', 'countPackages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function levelIncome()
    {
        $id_user = Auth::id();
        $scores = User::find($id_user)->banco()->where('description', 2)->paginate(9);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.levelincome', compact('scores', 'countPackages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function stakingRewards()
    {
        $id_user = Auth::id();
        $scores = HistoricScore::where('user_id', $id_user)->where('description', 7)->orderBy('id', 'desc')->paginate(9);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.stackingreward', compact('scores', 'countPackages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlyCoins()
    {
        $id_user = Auth::id();
        $monthly_coins = User::find($id_user)->banco()->where('description', 3)->paginate(9);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.monthlycoins', compact('monthly_coins', 'countPackages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions(Request $request)
    {
        // dd($request);
        $id_user = Auth::id();
        $bonus = ConfigBonus::all();
        $filter = $request->filter ?? null;
        $date = $request->date ?? null;

        if (isset($filter)) {
            if (isset($date)) {
                $transactions = User::find($id_user)->banco()->where('description', $filter)->where('created_at', 'LIKE', "%" . $date . "%")->orderBy('id', 'DESC')->paginate(100);
            } else {
                $transactions = User::find($id_user)->banco()->where('description', $filter)->orderBy('id', 'DESC')->paginate(100);
            }
        } else {
            if (isset($date)) {
                $transactions = User::find($id_user)->banco()->where('description', '<>', '98')->where('created_at', 'LIKE', "%" . $date . "%")->orderBy('id', 'DESC')->paginate(100);
            } else {
                $transactions = User::find($id_user)->banco()->where('description', '<>', '98')->orderBy('id', 'DESC')->paginate(100);
            }
        }

        $totalTrans = PaymentOrderEcomm::where('id_user', $id_user)->sum('total_price');

        foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
                $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }
        }
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.transaction', compact('transactions', 'countPackages', 'bonus', 'filter', 'date', 'totalTrans'));
    }

    public function getDateTransactions(Request $request)
    {
        // dd($request);
        $id_user = Auth::id();
        $bonus = ConfigBonus::all();
        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";
        $filter = $request->filter ?? null;
        $date = $request->date ?? null;

        if (!empty($fdate) and !empty($sdate)) {
            $transactions = User::find($id_user)->banco()->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', '<>', '98')->paginate(9);

            $totalTrans = 0;

            foreach ($transactions as $vlr) {
                $user_value = $vlr->order_id;
                $user__total = PaymentOrderEcomm::where('number_order', $user_value)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->sum('total_price');

                $totalTrans = $totalTrans + $user__total;
            }
        }

        foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
                $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }
        }

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.transaction', compact('transactions', 'countPackages', 'bonus', 'filter', 'date', 'totalTrans'));
    }

    public function searchuser(Request $request)
    {
        $id_user = Auth::id();
        $bonus = ConfigBonus::all();
        $user = $request->search ?? null;
        $filter = $request->filter ?? null;
        $date = $request->date ?? null;

        $user_id = User::where('login', $user)->first();

        $transactions = User::find($user_id->id)->banco()->orderBy('id', 'desc')->where('description', '<>', '98')->paginate(9);
        $totalTrans = PaymentOrderEcomm::where('id_user', $user_id->id)->sum('total_price');

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.transaction', compact('transactions', 'countPackages', 'bonus', 'filter', 'date', 'totalTrans'));

    }

    public function transactionsFilters(Request $request)
    {
        $id_user = Auth::id();
        $bonus = ConfigBonus::all();
        $user = $request->search ?? null;
        $filter = $request->filter ?? null;
        $date = $request->date ?? null;

        $user_id = User::where('login', $user)->first();

        $transactions = User::find($id_user)->banco()->orderBy('id', 'desc')->where('description', $filter)->paginate(9);
        // $totalTrans   = PaymentOrderEcomm::where('status', 'paid')->where('id_user', $id_user)->sum('total_price');

        // dd($transactions);
        // exit();
        $totalTrans = 0;

        foreach ($transactions as $vlr) {
            $user_value = $vlr->order_id;
            $user__total = PaymentOrderEcomm::where('number_order', $user_value)->sum('total_price');

            $totalTrans = $totalTrans + $user__total;
        }

        foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
                $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }
        }

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.transaction', compact('transactions', 'countPackages', 'bonus', 'filter', 'date', 'totalTrans'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function poolcommission()
    {
        $id_user = Auth::id();
        $scores = User::find($id_user)->banco()->where('description', 5)->paginate(9);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('report.poolcommission', compact('scores', 'countPackages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //ttttt
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function newrecruits()
    {
        $id_session = Auth::id();

        $newrecruits = DB::select("SELECT * FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND recommendation_user_id = $id_session");
        $array_user = array();

        foreach ($newrecruits as $data) {

            $my_qv = 0;
            $my_cv = 0;

            $payments = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

            foreach ($payments as $payment) {
                $my_qv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('qv');

                $my_cv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('cv');
            }

            $data_user = [
                'id' => $data->id,
                'name' => $data->name,
                'last_name' => $data->last_name,
                'login' => $data->login,
                'email' => $data->email,
                'cell' => $data->cell,
                'city' => $data->city,
                'country' => $data->country,
                'created_at' => $data->created_at,
                'qv' => $my_qv,
                'cv' => $my_cv,
            ];

            if (!in_array($data_user, $array_user)) {

                array_push($array_user, $data_user);
            }
        }

        return view('report.newrecruits', compact('newrecruits', 'array_user'));
    }

    public function newrecruitsDate(Request $request)
    {

        $month = $request->month;
        $year = $request->year;

        $id_session = Auth::id();

        $newrecruits = DB::select("SELECT * FROM users WHERE MONTH(created_at) = $month AND YEAR(created_at) = $year AND recommendation_user_id = $id_session");
        $array_user = array();

        foreach ($newrecruits as $data) {

            $my_qv = 0;
            $my_cv = 0;

            $payments = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

            // dd($payments);
            foreach ($payments as $payment) {
                $my_qv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('qv');

                $my_cv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('cv');
            }

            $data_user = [
                'id' => $data->id,
                'name' => $data->name,
                'last_name' => $data->last_name,
                'login' => $data->login,
                'email' => $data->email,
                'cell' => $data->cell,
                'city' => $data->city,
                'country' => $data->country,
                'created_at' => $data->created_at,
                'qv' => $my_qv,
                'cv' => $my_cv,
            ];

            if (!in_array($data_user, $array_user)) {

                array_push($array_user, $data_user);
            }
        }

        return view('report.newrecruits', compact('newrecruits', 'array_user', 'month', 'year'));
    }

    public function costumerrecruits()
    {
        $id_session = Auth::id();

        $costumerrecruits = DB::select("SELECT * FROM ecomm_registers WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND recommendation_user_id = $id_session");

        $array_user = array();

        foreach ($costumerrecruits as $data) {

            $my_qv = 0;
            $payments = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

            foreach ($payments as $payment) {
                $my_qv += DB::table('ecomm_orders')
                    ->where('number_order', $payment->number_order)
                    ->sum('qv');
            }

            $data_user = [
                'id' => $data->id,
                'name' => $data->name,
                'last_name' => $data->last_name,
                'username' => $data->username,
                'email' => $data->email,
                'phone' => $data->phone,
                'city' => $data->city,
                'country' => $data->country,
                'created_at' => $data->created_at,
                'qv' => $my_qv,
            ];

            if (!in_array($data_user, $array_user)) {

                array_push($array_user, $data_user);
            }
        }

        return view('report.costumerrecruits', compact('costumerrecruits', 'array_user'));
    }

    public function smartshippeople()
    {
        $id_session = Auth::id();

        $smartshippeople = DB::select("SELECT * FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND smartshipping = 1 AND recommendation_user_id = $id_session");

        foreach ($smartshippeople as $item) {
            $pedidoSmartDistributor = \DB::table('ecomm_orders')
                ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
                ->where('ecomm_orders.smartshipping', 1)
                ->where('ecomm_orders.id_user', $item->id)
                ->where('payments_order_ecomms.status', 'paid')
                ->select('payments_order_ecomms.*')
                ->latest('created_at')
                ->first();

            if (isset($pedidoSmartDistributor)) {

                $exists = SmartshippingPaymentsRecurring::where('number_order', $pedidoSmartDistributor->number_order)->latest('created_at')
                    ->first();

                $ativo = 'Active';
                if (isset($exists)) {
                    $proximaCobranca = Carbon::parse($exists->created_at)->addMonth();
                    if ($exists->status == 'paid') {
                        $ativo = 'Active';
                    } else {
                        $ativo = 'Not Active';
                    }
                } else {
                    $proximaCobranca = Carbon::parse($pedidoSmartDistributor->created_at)->addMonth();
                }

                $item->cobranca = $proximaCobranca;
                $item->ativo = $ativo;
            }
        }


        $costumer = DB::select("SELECT * FROM ecomm_registers WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND smartshipping = 1 AND recommendation_user_id = '$id_session'");

        foreach ($costumer as $item) {
            $pedidoSmartDistributor = \DB::table('ecomm_orders')
                ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
                ->where('ecomm_orders.smartshipping', 1)
                ->where('ecomm_orders.id_user', $item->id)
                ->where('payments_order_ecomms.status', 'paid')
                ->select('payments_order_ecomms.*')
                ->latest('created_at')
                ->first();

            if (isset($pedidoSmartDistributor)) {
                $exists = SmartshippingPaymentsRecurring::where('number_order', $pedidoSmartDistributor->number_order)->latest('created_at')
                    ->first();

                $ativo = 'Active';
                if (isset($exists)) {
                    $proximaCobranca = Carbon::parse($exists->created_at)->addMonth();
                    if ($exists->status == 'paid') {
                        $ativo = 'Active';
                    } else {
                        $ativo = 'Not Active';
                    }
                } else {
                    $proximaCobranca = Carbon::parse($pedidoSmartDistributor->created_at)->addMonth();
                }

                $item->cobranca = $proximaCobranca;
                $item->ativo = $ativo;
            }
        }

        // dd($smartshippeople, $costumer);

        return view('report.smartshippeople', compact('smartshippeople', 'costumer'));
    }

    public function smartshipping_check_cancel($id)
    {
        $id_session = Auth::id();

        $smartshippings = EcommOrders::where('id_user', $id_session)->where('smartshipping', 1)->get();

        $ordens = array();
        $smarts = [];
        $produts = [];
        $data = [];

        foreach ($smartshippings as $smart) {

            $new_ordem = $smart->number_order;

            if (!in_array($new_ordem, $ordens)) {

                array_push($ordens, $new_ordem);
            }
        }

        foreach ($ordens as $order) {

            $total_ecomm = PaymentOrderEcomm::where('number_order', $order)->sum('total_price');
            $total_qv = EcommOrders::where('number_order', $order)->where('smartshipping', 1)->sum('qv');
            $total_prodt = EcommOrders::where('number_order', $order)->where('smartshipping', 1)->get();

            foreach ($total_prodt as $prod) {
                array_push($produts, $prod->id);
            }

            $data = [
                'products' => $produts,
                'order' => $order,
                'total' => $total_ecomm,
                'qv' => $total_qv,
            ];

            array_push($smarts, $data);
        }

        $action_box = $id;

        return view('report.smartshipping', compact('smartshippings', 'action_box', 'smarts'));
    }

    public function smartshipping()
    {
        $id_session = Auth::id();

        $smartshippings = EcommOrders::where('id_user', $id_session)->where('smartshipping', 1)->get();

        $ordens = array();
        $smarts = [];
        $produts = [];
        $data = [];

        foreach ($smartshippings as $smart) {

            $new_ordem = $smart->number_order;

            if (!in_array($new_ordem, $ordens)) {

                array_push($ordens, $new_ordem);
            }
        }

        foreach ($ordens as $order) {

            $total_ecomm = PaymentOrderEcomm::where('number_order', $order)->sum('total_price');
            $total_qv = EcommOrders::where('number_order', $order)->where('smartshipping', 1)->sum('qv');
            $total_prodt = EcommOrders::where('number_order', $order)->where('smartshipping', 1)->get();

            foreach ($total_prodt as $prod) {
                array_push($produts, $prod->id);
            }

            $data = [
                'products' => $produts,
                'order' => $order,
                'total' => $total_ecomm,
                'qv' => $total_qv,
            ];

            array_push($smarts, $data);
        }

        return view('report.smartshipping', compact('smartshippings', 'smarts'));
    }

    public function smartshipping_cancel($id)
    {
        $id_session = Auth::id();

        // dd($id);

        $smartshipping = EcommOrders::where('number_order', $id)->where('smartshipping', 1)->get();

        foreach ($smartshipping as $smart) {

            $smart->smartshipping = 0;
            $smart->save();

            // $smartshipp = EcommOrders::find($smart->id);
            // $smartshipp->update([
            //     'smartshipping' => 0,
            // ]);
        }

        // $smartshippings = EcommOrders::where('id_user', $id_session)->where('smartshipping', 1)->get();

        return redirect()->route('reports.smartshipping_report');
    }

    public function teamorders()
    {
        $umMesAtras = now()->subMonth(); // Obtém a data de um mês atrás

        // $all_resgister = EcommOrders::where('created_at', '>', $umMesAtras)->get();

        $new_orders = EcommOrders::all();

        // $new_orders = DB::table('historic_score')
        //     ->join('ecomm_orders', 'historic_score.user_id_from', '=', 'ecomm_orders.id_user')
        //     ->select('ecomm_orders.*', 'historic_score.*')
        //     ->where('historic_score.user_id', '=', $id_session)
        //     ->where('historic_score.level_from', '>', 0)
        //     ->where('historic_score.level_from', '<', 6)
        //     ->get();

        $id_session = Auth::id();

        // $all_resgister = DB::select("SELECT * FROM ecomm_orders WHERE id_user IN ( SELECT user_id_from FROM historic_score WHERE user_id = $id_session AND level_from > 0 AND level_from < 6) ORDER BY id DESC");
        // $count_result = count($all_resgister);


        // $all_registers = array();
        // $array_ids     = array();

        // foreach ($all_resgister as $all) {

        //     if (!in_array($all->id_user, $array_ids)) {

        //         array_push($array_ids, $all->id_user);

        //         $user  = User::where('id', $all->id_user)->first();
        //         $users = User::where('id', $all->id_user)->get();
        //         $total = 0;
        //         $qv    = 0;
        //         $cv    = 0;

        //         if ($user) {

        //             foreach ($users as $value) {

        //                 $total = $total + $value->total;
        //                 $qv    = $qv + $value->qv;
        //                 $cv    = $cv + $value->cv;
        //             }

        //             $date = [
        //                 'id'        => $user->id,
        //                 'name'      => $user->name,
        //                 'last_name' => $user->last_name,
        //                 'username'  => $user->login,
        //                 'e-mail'    => $user->email,
        //                 'country'   => $user->country,
        //                 'state'     => $user->state,
        //                 'city'      => $user->city,
        //                 'total'     => $total,
        //                 'qv'        => $qv,
        //                 'cv'        => $cv,
        //                 'date'      => $user->created_at,
        //             ];

        //             array_push($all_registers, $date);
        //         }
        //     }
        // }

        // dd($all_registers, $array_ids);
        // exit();

        $reult_news = array();
        foreach ($new_orders as $orders) {

            $new_score = HistoricScore::where('user_id_from', $orders->id_user)->where('user_id', $id_session)->first();
            if (isset($new_score)) {
                if (isset($new_score->level_from)) {

                    if ($new_score->level_from > 0 and $new_score->level_from < 6) {

                        if (!in_array($new_score->id, $reult_news)) {

                            array_push($reult_news, $new_score->id);
                        }
                    }
                }
            }
        }

        $all_resgister = array();

        foreach ($reult_news as $team) {

            $result_team = HistoricScore::where('id', $team)->first();

            $send_user = User::find($result_team->user_id_from);

            if (isset($send_user) and !empty($send_user)) {

                array_push($all_resgister, $send_user);
            }
        }

        // foreach ($all_resgister as $order) {
        //     $order->total_price = $order->total + $order->total_vat + $order->total_shipping;
        // }

        $all_states = array();

        if (count($all_resgister) > 0) {

            foreach ($all_resgister as $statesx) {

                if (isset($statesx->country) and !empty($statesx->country)) {

                    if (!in_array($statesx->country, $all_states)) {

                        array_push($all_states, $statesx->country);
                    }
                }
            }
        }

        foreach ($all_resgister as $item) {
            $order = EcommOrders::where('id_user', $item->id)
                ->latest('created_at')
                ->first();

            if (isset($order)) {
                $item->date = $order->created_at;
            }
        }

        return view('report.teamorders', compact('all_resgister', 'id_session', 'all_states'));
    }

    public function DetailOrdersTeam($id, $month = null, $year = null)
    {
        $indication = User::where("id", $id)->first();

        if (isset($year) && isset($month)) {
            $ordersIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('number_order')
                ->pluck('id');

            $orders = EcommOrders::whereIn('id', $ordersIds)
                ->orderBy('updated_at', 'DESC')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->paginate(9);
        } else {
            $ordersIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $id)
                ->groupBy('number_order')
                ->pluck('id');

            $orders = EcommOrders::whereIn('id', $ordersIds)
                ->orderBy('updated_at', 'DESC')
                ->paginate(9);
        }


        foreach ($orders as $value) {
            $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
            $value['payment'] = $payment->status;
            $value['total'] = $payment->total_price;
        }

        return view('network.IndicationEcommOrders', compact('indication', 'orders'));
    }

    public function teamordersFilterDate(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $id_session = Auth::id();
        // dd($month, $year);


        $all_resgister = DB::table('ecomm_orders')
            ->whereIn('id_user', function ($query) use ($id_session, $year, $month) {
                $query->select('user_id')
                    ->from('historic_score')
                    ->where('user_id', $id_session)
                    ->where('level_from', '>', 0)
                    ->where('level_from', '<', 6);
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'DESC')
            ->get();

        $count_result = count($all_resgister);

        $new_orders = EcommOrders::whereRaw('YEAR(created_at) = ? AND MONTH(created_at) = ?', [$year, $month])->get();

        $reult_news = array();
        foreach ($new_orders as $orders) {

            $new_score = HistoricScore::whereRaw('YEAR(created_at) = ? AND MONTH(created_at) = ?', [$year, $month])->where('user_id_from', $orders->id_user)->where('user_id', $id_session)->first();
            if (isset($new_score)) {
                if (isset($new_score->level_from)) {

                    if ($new_score->level_from > 0 and $new_score->level_from < 6) {

                        if (!in_array($new_score->id, $reult_news)) {

                            array_push($reult_news, $new_score->id);
                        }
                    }
                }
            }
        }

        $all_resgister = array();

        foreach ($reult_news as $team) {

            $result_team = HistoricScore::where('id', $team)->whereRaw('YEAR(created_at) = ? AND MONTH(created_at) = ?', [$year, $month])->first();

            $send_user = User::where('id', $result_team->user_id_from)->first();

            if (isset($send_user) and !empty($send_user)) {
                array_push($all_resgister, $send_user);
            }
        }


        $all_states = array();

        if (count($all_resgister) > 0) {

            foreach ($all_resgister as $statesx) {

                if (isset($statesx->country) and !empty($statesx->country)) {

                    if (!in_array($statesx->country, $all_states)) {

                        array_push($all_states, $statesx->country);
                    }
                }
            }
        }

        foreach ($all_resgister as $item) {
            $order = EcommOrders::where('id_user', $item->id)
                ->whereRaw('YEAR(created_at) = ? AND MONTH(created_at) = ?', [$year, $month])
                ->latest('created_at')
                ->first();

            if (isset($order)) {
                $item->date = $order->created_at;
            }
        }

        return view('report.teamorders', compact('all_resgister', 'id_session', 'all_states', 'month', 'year'));
    }
    public function teamordersFilter(Request $request)
    {
        $umMesAtras = now()->subMonth(); // Obtém a data de um mês atrás

        // $all_resgister = EcommOrders::where('created_at', '>', $umMesAtras)->get();

        $id_session = Auth::id();

        $all_resgister = DB::select("SELECT * FROM ecomm_orders WHERE id_user IN ( SELECT user_id FROM historic_score WHERE user_id = $id_session AND level_from > 0 AND level_from < 6) ORDER BY id DESC");
        $count_result = count($all_resgister);

        // $new_orders = EcommOrders::join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
        //     ->whereRaw('LOWER(payments_order_ecomms.status) = ?', ['paid'])
        //     ->get();

        $new_orders = EcommOrders::all();

        $reult_news = array();
        foreach ($new_orders as $orders) {

            $new_score = HistoricScore::where('user_id_from', $orders->id_user)->where('user_id', $id_session)->first();
            if (isset($new_score)) {
                if (isset($new_score->level_from)) {
                    if ($new_score->level_from > 0 and $new_score->level_from < 6) {
                        if (!in_array($new_score->id, $reult_news)) {
                            array_push($reult_news, $new_score->id);
                        }
                    }
                }
            }
        }

        $all_resgister = array();
        $all_resgister2 = array();

        foreach ($reult_news as $team) {

            $result_team = HistoricScore::where('id', $team)->first();

            $userQuery = User::where('id', $result_team->user_id_from)
                ->where('name', 'like', '%' . $request->name . '%')
                ->where('last_name', 'like', '%' . $request->lastname . '%');

            if (!empty($request->states)) {
                $userQuery->where('country', $request->states);
            }

            $send_user = $userQuery->first();

            if (isset($send_user) and !empty($send_user)) {

                array_push($all_resgister, $send_user);
            }
        }

        foreach ($reult_news as $team) {

            $result_team = HistoricScore::where('id', $team)->first();

            $send_user2 = User::where('id', $result_team->user_id_from)->first();

            if (isset($send_user2) and !empty($send_user2)) {

                array_push($all_resgister2, $send_user2);
            }
        }

        $all_states = array();

        if (count($all_resgister2) > 0) {

            foreach ($all_resgister2 as $statesx) {

                if (isset($statesx->country) and !empty($statesx->country)) {

                    if (!in_array($statesx->country, $all_states)) {

                        array_push($all_states, $statesx->country);
                    }
                }
            }
        }

        foreach ($all_resgister as $item) {
            $order = EcommOrders::where('id_user', $item->id)
                ->latest('created_at')
                ->first();

            if (isset($order)) {
                $item->date = $order->created_at;
            }
        }

        return view('report.teamorders', compact('all_resgister', 'id_session', 'all_states'));
    }

    public function detailorder($id)
    {
        $orders = EcommOrders::findOrFail($id);
        $user = EcommRegister::where('id', $orders->id_user)->first();
        $product = Product::where('id', $orders->id_product)->first();

        return view('report.detal_orders_team', compact('orders', 'user', 'product'));
    }

    public function teamranks()
    {
        $id_session = Auth::id();

        $rankteam = DB::select("SELECT * FROM users WHERE recommendation_user_id = $id_session");

        return view('report.teamranks', compact('rankteam'));
    }

    public function teamrankscurrent()
    {
        $id_session = Auth::id();
        $rankteam = DB::select("SELECT * FROM users WHERE recommendation_user_id = $id_session");

        return view('report.teamrankscurrent', compact('rankteam'));
    }

    public function newsmartshipping()
    {
        $user = User::find(Auth::id());
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {

            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->whereIn('id', $productsByCountry)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->get();

        } else {
            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->get();
        }

        foreach ($products as $product_info) {

            $stock = $product_info->Stock($product_info);

            if ($stock > 0 && $product_info->kit == 1) {
                $stock = 1;
            }

            $product_info['stock'] = $stock;
        }


        $ip_order = $_SERVER['REMOTE_ADDR'];
        // $ip_order = '194.156.125.61';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];

        $user = User::where('id', Auth::id())->first();
        $countryIp = $user->country;

        return view('report.newsmartshipping', compact('products', 'countryIp'));
    }

    public function smartshippingSumTotal(Request $request)
    {
        if ($request->ajax()) {

            $amount = $request->amount;
            $total = $request->valor;
            $price = $request->price;

            $calcule = ($amount * $price) + $total;
            $data = $calcule;

            return $data;
        }
    }

    public function smartshippingSum(Request $request)
    {
        // if (Auth::id() != 1) {
        //     return $this->newsmartshipping();
        // }        

        $count_form = 0;
        $array_impts = array();

        $user = User::where('id', Auth::id())->first();
        $countryIp = $user->country;
        $lastIdProduct = Product::orderBy('id', 'desc')->first();

        for ($i = 0; $i <= $lastIdProduct->id; $i++) {

            $product = 'product_' . $i;
            $amount = 'amount_' . $i;
            $price = 'price_' . $i;

            $tax = Tax::where('product_id', $request->$product)->where('country', $countryIp)->first();

            if (isset($tax)) {
                $product_vat = $tax->value;
            } else {
                $product_vat = 0.00;
            }

            $subtotal = $request->$price * $request->$amount;
            if ($product_vat > 0) {
                $vat_value = $subtotal * $product_vat / 100;
            } else {
                $vat_value = 0;
            }

            if (!empty($request->$amount)) {

                $array_push = [
                    'product' => $request->$product,
                    'amount' => $request->$amount,
                    'price' => $request->$price,
                    'subtotal' => $subtotal + $vat_value,
                    'vat' => $vat_value,
                ];

                array_push($array_impts, $array_push);
            }
        }

        // dd($array_push);

        $total = 0;

        foreach ($array_impts as $inputs) {

            $total = $total + $inputs['subtotal'];
        }

        // dd($array_impts, $total);
        // exit();
        session()->put('new_order', $array_impts);

        $user = User::find(Auth::id());
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {

            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->whereIn('id', $productsByCountry)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->get();

        } else {
            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->get();
        }

        foreach ($products as $product_info) {
            $stock = 1;
            if ($product_info->kit != 0) {

                $parts = explode('|', $product_info->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                    $separado[$id_produto] = $quantidade;
                }

                foreach ($separado as $key => $value) {
                    $temStock = DB::table('stock')
                        ->where('product_id', $key)
                        ->sum('amount');

                    if ($temStock < $value) {
                        $stock = 0;
                        break;
                    }

                }

            } else {

                $stock = DB::table('stock')
                    ->where('product_id', $product_info->id)
                    ->sum('amount');
            }

            $product_info['stock'] = $stock;
        }

        return view('report.newsmartshipping', compact('total', 'products', 'array_impts', 'countryIp'));
    }

    public function smartshippingOrder()
    {
        $id_session = Auth::id();
        $session_order = session('new_order');

        // $controllerProduct = new ProductController;
        // $number_order = $controllerProduct->genNumberOrder();

        // dd($session_order);
        if (count($session_order) > 0) {
            foreach ($session_order as $order) {
                $this->buyProduct($order["amount"], $order["product"]);
            }

            return redirect()->route('packages.cart_buy', 'smart');
        } else {
            return redirect()->back();
        }
        // exit();
    }

    public function smartshippingOrderDetail($id)
    {
        $user = User::find(Auth::id());
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {

            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->whereIn('id', $productsByCountry)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->paginate(9);

        } else {
            $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->paginate(9);
        }
        $product_box = Product::find($id);

        $ip_order = $_SERVER['REMOTE_ADDR'];
        // $ip_order = '194.156.125.61';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];
        $user = User::where('id', Auth::id())->first();
        $countryIp = $user->country;

        return view('report.newsmartshipping', compact('products', 'countryIp', 'product_box'));
    }

    public function smartshippingOrderBack()
    {
        return redirect()->back();
    }

    public function buyProduct($quant_product, $id)
    {
        $product = Product::find($id);
        // Verificar produto
        if ($product->kit != 0) {

            $parts = explode('|', $product->kit_produtos); // Divide a string pelo caractere "|"

            foreach ($parts as $part) {
                list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                $separado[$id_produto] = $quantidade;
            }

            foreach ($separado as $key => $value) {
                $temStock = DB::table('stock')
                    ->where('product_id', $key)
                    ->sum('amount');

                if ($temStock < $value) {
                    $stock = 0;
                    break;
                }

                if ($product->kit == 1) {

                    $controllerProduct = new ProductController;
                    $controllerProduct->addProductInToCart($key, $value);
                }
            }
            if ($product->kit == 1) {
                return;
            } else {
                $stock = DB::table('stock')
                    ->where('product_id', $product->id)
                    ->sum('amount');
            }
        } else {

            $stock = DB::table('stock')
                ->where('product_id', $product->id)
                ->sum('amount');
        }

        $quantProduct = $quant_product;

        if ($quantProduct > $stock) {
            $quantProduct = $stock;
        }


        $debit_price = $product->backoffice_price;


        $totalBuy = $quantProduct * $debit_price;
        $user = User::find(Auth::id())->id;

        // adicionar ao carrinho
        $cart = new CartOrder;
        $cart->id_user = $user;
        $cart->id_product = $id;
        $cart->name = $product->name;
        $cart->img = $product->img_1;
        $cart->price = $product->backoffice_price;
        $cart->amount = $quantProduct;
        $cart->total = $totalBuy;

        // função de verificação de quantidade
        $check_product = DB::select("SELECT * FROM cart_orders WHERE id_user = '$user' AND id_product = '$id'");

        if ($check_product) {

            $id_card = $check_product[0]->id;
            $update_quant = CartOrder::find($id_card);
            $price_product = $update_quant->price;

            $n_quant = $update_quant->amount + $quantProduct;

            if ($n_quant > $stock) {
                $n_quant = $stock;
            }

            $update_quant->update([
                'amount' => $n_quant,
                'total' => $update_quant->total + $totalBuy,
            ]);
        } else {
            $cart->save();
        }


        return;
    }

    public function reportBonusGroup(Request $request)
    {
        $user_id = Auth::id();
        $data_ini = $request['fdate'];
        $data_fim = $request['sdate'];
        $data_i = $data_ini ? Carbon::createFromFormat('Y-m-d', $data_ini)->startOfDay() : 0;
        $data_f = $data_fim ? Carbon::createFromFormat('Y-m-d', $data_fim)->endOfDay() : 0;
        $query = Banco::with('ecommOrder.user')
            ->where('user_id', $user_id);

        if (!empty($data_i) && !empty($data_f)) {
            $query->whereBetween('created_at', [$data_i, $data_f]);
        } elseif (!empty($data_i)) {
            $query->where('created_at', '>=', $data_i);
        } elseif (!empty($data_f)) {
            $query->where('created_at', '<=', $data_f);
        }

        $bonusGroups = $query->get();
        $configBonus = ConfigBonus::all();
        $bonusTotal = [];

        foreach ($bonusGroups as $bonus) {
            $description_ids = array_keys($bonusTotal);
            $key = intval($bonus->description);

            if (in_array($bonus->description, $description_ids)) {
                $bonusTotal[$key]['total'] += $bonus->price;
                $bonusTotal[$key]['bonus_list'][] = $bonus;
            } else {
                $bonusTotal[$key]['total'] = $bonus->price;
                $bonusTotal[$key]['bonus_list'][] = $bonus;
            }
        }
        $users_temp = [];

        if (isset($bonusTotal[11]['bonus_list'])) {
            foreach ($bonusTotal[11]['bonus_list'] as $bonus_order) {
                $users_temp[] = $bonus_order->order_id;
            }
        }

        $users = User::whereIn('id', $users_temp)->get();

        $users_11 = [];
        foreach ($users as $user) {
            $users_11[$user->id][0] = $user->name;
            $users_11[$user->id][1] = $user->last_name;
        }

        return view('report.bonus-group', compact('bonusTotal', 'configBonus', 'data_ini', 'data_fim', 'users_11'));
    }

    public function commissionMonth()
    {
        $user_id = Auth::id();

        $dados = Banco::with('user')
            ->where('user_id', $user_id)
            ->where('description', '<>', 99)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m');
            });

        $commissions = [];
        $total = 0;
        foreach ($dados as $month => $grouped) {
            $totalPrice = $grouped->sum('price');
            $commissions[$month] = $totalPrice;

            $total += $totalPrice;
        }
        // // $res = array_keys($commissions[],);
        return view('report.commissionsMonth', compact('commissions', 'total'));
    }
}
