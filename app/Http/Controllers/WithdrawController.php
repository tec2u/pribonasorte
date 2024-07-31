<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\PayoutAdminController;
use App\Models\Banco;
use App\Models\User;
use App\Models\WithdrawRequest;
use App\Models\OrderPackage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Alert;
use App\Models\Wallet;
use App\Http\Requests\RequestValidateWithdraw;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawRequests()
    {
        $currentDate = Carbon::now();
        $dayThreshold = 15;
        $cardAvailable = false;
        $subMonth = $currentDate->subMonth()->month;
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($subMonth >= 2) {
            $subMonth = $subMonth - 1;
            $currentYear = $currentYear;
        } else {
            $subMonth = 12;
            $currentYear = $currentYear - 1;
        }

        if ($subMonth < 9) {
            $subMonth = "0" . $subMonth;
        } else {
            $subMonth = $subMonth;
        }

        $farst_day = date("t", mktime(0, 0, 0, $subMonth, '01', $currentYear));

        $dateComplete = $currentYear . "-" . $subMonth . "-" . $farst_day . " 23:59:59";
        $dateComplete2 = $currentDate->year . "-" . $currentMonth . "-" . $farst_day . " 23:59:59";


        if ($currentDate->day >= $dayThreshold) {
            $cardAvailable = true;
            $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
            $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';


            $saldo = DB::table('banco')
                ->where('user_id', User::find(Auth::id())->id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete2)
                ->sum('price');

        } else {

            $saldo = DB::table('banco')
                ->where('user_id', User::find(Auth::id())->id)
                ->where('price', '>', 0)
                ->whereMonth('created_at', '<=', $dateComplete)
                ->sum('price');
        }

        $retiradasTotais = DB::table('banco')
            ->where('user_id', User::find(Auth::id())->id)
            ->where('price', '<', 0) // Considera apenas valores negativos
            ->sum('price');

        $retiradasTotais = -$retiradasTotais;

        if ($retiradasTotais >= $saldo) {
            $saldo = 0;
        } else {
            $saldo = $saldo - $retiradasTotais;
        }



        $id_user = Auth::id();

        $user = User::find($id_user);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $withdraws = WithdrawRequest::orderBy('id', 'DESC')->where('user_id', $id_user)->where('type', 'like', '%Withdraw CC%')->paginate(9);

        return view('withdraw.withdrawrequest', compact('withdraws', 'user', 'countPackages', 'saldo'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawLog()
    {
        $id_user = Auth::id();

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $withdraws = WithdrawRequest::orderBy('id', 'DESC')->where('user_id', $id_user)->where('status', 1)->paginate(9);

        return view('withdraw.withdrawhistory', compact('withdraws', 'countPackages'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawBonus()
    {
        $id_user = Auth::id();

        $user = User::find($id_user);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $withdraws = WithdrawRequest::orderBy('id', 'DESC')->where('user_id', $id_user)->where('type', 'like', '%Withdraw Comission%')->paginate(9);

        return view('withdraw.withdrawbonus', compact('withdraws', 'user', 'countPackages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentDate = Carbon::now();
        $dayThreshold = 15;
        $cardAvailable = false;
        $subMonth = $currentDate->subMonth()->month;
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($subMonth >= 2) {
            $subMonth = $subMonth - 1;
            $currentYear = $currentYear;
        } else {
            $subMonth = 12;
            $currentYear = $currentYear - 1;
        }

        if ($subMonth < 9) {
            $subMonth = "0" . $subMonth;
        } else {
            $subMonth = $subMonth;
        }

        $farst_day = date("t", mktime(0, 0, 0, $subMonth, '01', $currentYear));

        $dateComplete = $currentYear . "-" . $subMonth . "-" . $farst_day . " 23:59:59";
        $dateComplete2 = $currentDate->year . "-" . $currentMonth . "-" . $farst_day . " 23:59:59";


        if ($currentDate->day >= $dayThreshold) {
            $cardAvailable = true;
            $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
            $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';


            $saldo = DB::table('banco')
                ->where('user_id', User::find(Auth::id())->id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete2)
                ->sum('price');

        } else {

            $saldo = DB::table('banco')
                ->where('user_id', User::find(Auth::id())->id)
                ->where('price', '>', 0)
                ->whereMonth('created_at', '<=', $dateComplete)
                ->sum('price');
        }

        $retiradasTotais = DB::table('banco')
            ->where('user_id', User::find(Auth::id())->id)
            ->where('price', '<', 0) // Considera apenas valores negativos
            ->sum('price');

        $retiradasTotais = -$retiradasTotais;

        if ($retiradasTotais >= $saldo) {
            $saldo = 0;
        } else {
            $saldo = $saldo - $retiradasTotais;
        }

        $id_user = Auth::id();

        // $request->validated();

        $value = $request->get('value');

        if ($value > $saldo) {
            Alert::warning(__('not enough commission'));
            return redirect()->route('withdraws.withdrawRequests', compact('saldo'))->with('error', 'not enough commission');
        }

        // dd($request);
        $tx = 3.5;

        $valuetx = $value - $tx;


        $user = User::find($id_user);

        $wallet = Wallet::where('user_id', $user->id)->first();

        $fdate = date('Y-m-d') . " 00:00:00";
        $sdate = date("Y-m-d") . " 23:59:59";
        $findvalue = WithdrawRequest::where('user_id', $user->id)
            ->where('created_at', '>=', $fdate)
            ->where('created_at', '<=', $sdate)
            ->where('payment_code', '!=', 'updated mannually via admin')
            ->count();

        if ($findvalue === 1) {
            Alert::warning(__('You can only make 1 withdrawal per day'));
            return redirect()->route('withdraws.withdrawRequests', compact('saldo'));
        }

        $validate = WithdrawRequest::where('user_id', $user->id)
            ->where('status', 1)
            ->count();

        if ($validate === 1) {
            return redirect()->route('withdraws.withdrawRequests', compact('saldo'));
        }

        $withdraw_store = new WithdrawRequest;
        $withdraw_store->user_id = $user->id;
        $withdraw_store->value = $valuetx;
        $withdraw_store->status = 1;
        $withdraw_store->processing_user = $user->id;
        $withdraw_store->type = "Withdraw CC";
        // $withdraw_store->account_name = $request->account_name;
        // $withdraw_store->address = $request->address;
        // $withdraw_store->account_number = $request->account_number;
        // $withdraw_store->bank_name = $request->bank_name;
        // $withdraw_store->iban = $request->iban;
        // $withdraw_store->swift = $request->swift;
        $withdraw_store->method = $request->method;
        $withdraw_store->save();

        if ($request->method == 'ipayout') {
            $ipayoutController = new PayoutAdminController;
            $ipayoutController->createUser($user->id);
        }

        $data = [
            "order_id" => random_int('10000000', '99999999'),
            "price" => -$valuetx,
            "description" => "98",
            "status" => 0,
            "level_from" => 0,
        ];

        $banco = $user->banco()->create($data);

        //  $datawithdraw = [
        //     "value"       => $valuetx,
        //     "status"      => 0,
        //     "type"        => 'Withdraw CC'
        //  ];

        // //  $withdraw = $user->withdraw()->create($datawithdraw);

        if (!isset($wallet)) {
            $wllets = new Wallet;
            $wllets->user_id = $user->id;
            $wllets->wallet = "Withdraw CC";
            $wllets->description = "Teste";

            $wllets->save();
        }

        Alert::success(__('backoffice_alert.withdraw_request_created'));
        return redirect()->route('withdraws.withdrawRequests', compact('saldo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bonus(Request $request)
    {
        $id_user = Auth::id();
        $value = $request->get('value');
        $tx = $value * 0.05;
        $valuetx = $value + $tx;
        $user = User::find($id_user);
        $wallet = Wallet::where('user_id', $user->id)->first();


        if (empty($wallet)) {
            Alert::warning(__('backoffice_alert.please_register_wallet'));
            return redirect()->route('withdraws.withdrawBonus');
        }
        if ($valuetx > $user->getTotalBancoIndicacao() + $user->getTotalBancoILevel()) {
            Alert::warning(__('backoffice_alert.dont_have_enough_balance'));
            return redirect()->route('withdraws.withdrawBonus');
        }


        try {

            $data = [
                "price" => -$valuetx,
                "description" => "98",
                "status" => 0

            ];

            $banco = $user->banco()->create($data);

            $datawithdraw = [
                "value" => $value,
                "status" => 0,
                "type" => 'Withdraw Comission'
            ];


            $withdraw = $user->withdraw()->create($datawithdraw);

            Alert::success(__('backoffice_alert.withdraw_bonus_request_created'));
            return redirect()->route('withdraws.withdrawBonus');
        } catch (Exception $e) {
            Alert::error(__('backoffice_alert.withdraw_bonus_request_not_created'));
            return redirect()->route('withdraws.withdrawBonus');
        }
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
}
