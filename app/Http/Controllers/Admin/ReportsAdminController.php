<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SearchRequest;
use App\Models\CancelSmart;
use App\Models\ConfigBonus;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\HistoricScore as Score;
use App\Http\Controllers\Controller;
use App\Models\PaymentOrderEcomm;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Banco;
use App\Models\User;
use App\Models\CareerUser;
use App\Models\HistoricScore;
use App\Traits\CustomLogTrait;
use Illuminate\Support\Facades\DB;
use App\Models\EcommRegister;
use App\Models\EditSmarthipping;
use App\Models\ReportCommission;

// use App\Exports\CommissionExport;
// use Maatwebsite\Excel\Facades\Excel;

class ReportsAdminController extends Controller
{
   use CustomLogTrait;
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function signupcommission()
   {
      $scores = Banco::where('description', 8)->paginate(9);

      //dd($scores);

      return view('admin.reports.signupCommission', compact('scores'));
   }

   public function searchSignup(SearchRequest $request)
   {

      try {
         $data = $request->search;
         $scores = DB::table('banco')
            ->join('users', 'banco.user_id', '=', 'users.id')
            ->join('config_bonus', 'banco.description', '=', 'config_bonus.id')
            ->where('banco.description', 1)
            ->where('users.name', 'like', '%' . $data . '%')->paginate(9);
         flash(__('admin_alert.userfound'))->success();
         return view('admin.reports.signupCommission', compact('scores'));
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);
         flash(__('admin_alert.usernotfound'))->error();
         return redirect()->back();
      }
   }

   public function getDateSignup(Request $request)
   {

      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      $scores = Banco::where('description', 1)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

      return view('admin.reports.signupCommission', compact('scores'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function levelIncome()
   {

      $scores = Banco::where('description', 2)->paginate(9);

      return view('admin.reports.levelIncome', compact('scores'));
   }

   public function searchLevelIncome(SearchRequest $request)
   {
      try {
         $data = $request->search;

         $scores = DB::table('banco')
            ->join('users', 'banco.user_id', '=', 'users.id')
            ->join('config_bonus', 'banco.description', '=', 'config_bonus.id')
            ->where('banco.description', 2)
            ->where('users.name', 'like', '%' . $data . '%')->paginate(9);

         return view('admin.reports.levelIncome', compact('scores'));
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);

         return redirect()->back();
      }
   }

   public function getDateLevelIncome(Request $request)
   {

      try {
         $fdate = $request->fdate . " 00:00:00";
         $sdate = $request->sdate . " 23:59:59";

         $scores = Banco::where('description', 2)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);
         flash(__('admin_alert.pkgfound'))->success();
         return view('admin.reports.levelIncome', compact('scores'));
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);
         flash(__('admin_alert.pkgnotfound'))->error();
         return redirect()->back();
      }
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

   public function stakingRewards()
   {


      $scores = HistoricScore::orderBy('id', 'desc')->where('description', 7)->paginate(9);

      return view('admin.reports.stakingRewards', compact('scores'));
   }

   public function searchstakingRewards(SearchRequest $request)
   {

      try {
         $data = $request->search;
         $scores = DB::table('historic_score')
            ->join('users', 'historic_score.user_id', '=', 'users.id')
            ->where('users.name', 'like', '%' . $data . '%')->where('historic_score.description', 7)->paginate(9);
         flash(__('admin_alert.userfound'))->success();
         return view('admin.reports.stakingRewards', compact('scores'));
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);
         flash(__('admin_alert.usernotfound'))->error();
         return redirect()->back();
      }
   }

   public function getstakingRewards(Request $request)
   {

      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      $scores = HistoricScore::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->where('description', 7)->paginate(9);

      return view('admin.reports.stakingRewards', compact('scores'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function rankReward()
   {

      $career_users = CareerUser::orderBy('id', 'desc')->paginate(9);

      return view('admin.reports.rankReward', compact('career_users'));
   }

   public function searchrankReward(SearchRequest $request)
   {

      try {
         $data = $request->search;
         $career_users = DB::table('career_users')
            ->join('users', 'career_users.user_id', '=', 'users.id')
            ->join('career', 'career_users.career_id', '=', 'career.id')
            ->where('career.name', 'like', '%' . $data . '%')
            ->where('users.name', 'like', '%' . $data . '%')->paginate(9);
         flash(__('admin_alert.userfound'))->success();
         return view('admin.reports.rankReward', compact('career_users'));
      } catch (Exception $e) {
         flash(__('admin_alert.usernotfound'))->error();
         return redirect()->back();
      }
   }

   public function getDaterankReward(Request $request)
   {

      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      $career_users = CareerUser::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

      return view('admin.reports.rankReward', compact('career_users'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function monthlyCoins()
   {
      $monthly_coins = Banco::where('description', 3, )->paginate(9);


      return view('admin.reports.monthlyCoins', compact('monthly_coins'));
   }

   public function searchMonthly(SearchRequest $request)
   {

      try {
         $data = $request->search;
         $monthly_coins = DB::table('banco')
            ->join('users', 'banco.user_id', '=', 'users.id')
            ->join('config_bonus', 'banco.description', '=', 'config_bonus.id')
            ->where('banco.description', 3)
            ->where('users.name', 'like', '%' . $data . '%')->paginate(9);
         flash(__('admin_alert.userfound'))->success();
         return view('admin.reports.monthlyCoins', compact('monthly_coins'));
      } catch (Exception) {
         flash(__('admin_alert.pkgnotfound'))->error();
         return redirect()->back();
      }
   }


   public function getDateMonthly(Request $request)
   {

      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      $monthly_coins = Banco::where('description', 3)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

      return view('admin.reports.monthlyCoins', compact('monthly_coins'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function transactions(Request $request)
   {

        $bonus = ConfigBonus::all();


        $filter = $request->filter ?? null;
        $date1  = $request->date1 ?? null;
        $date2  = $request->date2 ?? null;
        $usern  = $request->user_name ?? null;

        if (isset($usern) AND isset($filter) AND isset($date1) AND isset($date2)) {

            $users = User::where('login', 'like', '%'.$usern.'%')->get();

            $usersIDs = $users->pluck('id')->toArray();

            $exp1     = explode('/', $date1);
            $exp2     = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $transactions = Banco::orderBy('id', 'asc')
                ->whereIn('user_id', $usersIDs)
                ->where('description', $filter)
                ->where('updated_at', '>', $newdate1)
                ->where('updated_at', '<', $newdate2)
                // ->paginate(50);
                ->get();

            $totalTrans = Banco::whereIn('user_id', $usersIDs)
            ->where('description', $filter)
            ->where('updated_at', '>', $newdate1)
            ->where('updated_at', '>', $newdate1)
            ->sum('price');
        }

        elseif (isset($filter) AND isset($date1) AND isset($date2)) {

            $exp1     = explode('/', $date1);
            $exp2     = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $transactions = Banco::orderBy('id', 'asc')
                ->where('description', $filter)
                ->where('updated_at', '>', $newdate1)
                ->where('updated_at', '<', $newdate2)
                ->get();

            $totalTrans = Banco::where('description', $filter)
            ->where('updated_at', '>', $newdate1)
            ->where('updated_at', '>', $newdate1)
            ->sum('price');
        }

        elseif (isset($usern) AND isset($date1) AND isset($date2)) {
            $users = User::where('login', 'like', '%'.$usern.'%')->get();

            $usersIDs = $users->pluck('id')->toArray();

            $exp1     = explode('/', $date1);
            $exp2     = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $transactions = Banco::orderBy('id', 'asc')
                ->whereIn('user_id', $usersIDs)
                ->where('updated_at', '>', $newdate1)
                ->where('updated_at', '<', $newdate2)
                ->get();

            $totalTrans = Banco::where('description', '<>', '98')
            ->whereIn('user_id', $usersIDs)
            ->where('updated_at', '>', $newdate1)
            ->where('updated_at', '>', $newdate1)
            ->sum('price');
        }
        else if(isset($date1) AND isset($date2)) {

            $exp1     = explode('/', $date1);
            $exp2     = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $transactions = Banco::orderBy('id', 'asc')
                ->where('updated_at', '>', $newdate1)
                ->where('updated_at', '<', $newdate2)
                ->get();

            $totalTrans = Banco::where('description', '<>', '98')
            ->where('updated_at', '>', $newdate1)
            ->where('updated_at', '>', $newdate1)
            ->sum('price');
        }

        else {
            $transactions = Banco::orderBy('id', 'desc')
            ->where('description', '<>', '98')
            ->where('updated_at', 'like', "%" . date('Y-m') . "%") //temporario
            ->get();

            $totalTrans = Banco::where('description', '<>', '98')
            ->where('updated_at', 'like', "%" . date('Y-m') . "%") //temporario
            ->sum('price');

        }

        foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();




            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
                $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }

            $config_bonus = ConfigBonus::where('id', $item->description)->first();
            if ($item->description != 99 && $item->description != 98) {
                $item->config_bonus_description = $config_bonus->description;
            } elseif ($item->description == 99) {
                $item->config_bonus_description = 'Payment order';
            }

        }

        return view('admin.reports.transactions', compact('transactions', 'bonus', 'filter', 'totalTrans', 'usern'));
   }

   public function searchTransactions(SearchRequest $request)
   {
      $bonus = ConfigBonus::all();
      try {
         $data = $request->search;
         $transactions = DB::table('banco')
            ->select('banco.id as banco_id', 'banco.created_at as banco_created', 'banco.*', 'users.*')
            ->join('users', 'banco.user_id', '=', 'users.id')
            // ->join('config_bonus', 'banco.description', '=', 'config_bonus.id')
            ->where('users.name', 'like', '%' . $data . '%')
            ->where('banco.description', '<>', '98')
            ->orderBy('banco.id', 'desc')
            ->paginate(9);

         // dd($transactions);

         foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $config_bonus = ConfigBonus::where('id', $item->description)->first();
            if ($item->description != 99 && $item->description != 98) {
               $item->config_bonus_description = $config_bonus->description;
            } elseif ($item->description == 99) {
               $item->config_bonus_description = 'Payment order';
            }

            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
               $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
               $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }
         }

         // dd($transactions);
         return view('admin.reports.transactions', compact('transactions', 'bonus'));
      } catch (Exception) {
         // $transactions = Banco::where('description', '<>', 3)->paginate(9);
         $transactions = Banco::orderBy('id', 'desc')->where('description', '<>', '98')->paginate(9);
         foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
               $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
               $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }
         }
         return view('admin.reports.transactions', compact('transactions', 'bonus'));
      }
   }


   public function getDateTransactions(Request $request)
   {
      $bonus = ConfigBonus::all();
      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      // $transactions = Banco::where('description', '<>', 3)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);
      $transactions = Banco::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', '<>', '98')->paginate(9);

      $totalTrans = Banco::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->where('description', '<>', '98')->sum('price');

      foreach ($transactions as $item) {
         $order = EcommOrders::where('number_order', $item->order_id)->get();
         $item->qv = 0;
         $item->cv = 0;
         if (count($order) > 0) {
            $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
            $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
         }
      }
      return view('admin.reports.transactions', compact('transactions', 'bonus', 'totalTrans'));
   }

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function poolcommission()
   {
      $scores = Banco::where('description', 5)->paginate(9);

      //dd($scores);

      return view('admin.reports.poolCommission', compact('scores'));
   }

   public function searchPool(SearchRequest $request)
   {

      try {
         $data = $request->search;
         $scores = DB::table('banco')
            ->join('users', 'banco.user_id', '=', 'users.id')
            ->join('config_bonus', 'banco.description', '=', 'config_bonus.id')
            ->where('banco.description', 5)
            ->where('users.name', 'like', '%' . $data . '%')->paginate(9);
         flash(__('admin_alert.userfound'))->success();
         return view('admin.reports.signupCommission', compact('scores'));
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);
         flash(__('admin_alert.usernotfound'))->error();
         return redirect()->back();
      }
   }

   public function getDatePool(Request $request)
   {

      $fdate = $request->get('fdate') . " 00:00:00";
      $sdate = $request->get('sdate') . " 23:59:59";

      $scores = Banco::where('description', 5)->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

      return view('admin.reports.signupCommission', compact('scores'));
   }


   public function smartShippingCustomers()
   {
      $SSCustomers = DB::table('ecomm_orders')
         ->join('users', 'ecomm_orders.id_user', '=', 'users.id')
         ->join('products', 'ecomm_orders.id_product', '=', 'products.id')
         ->select('ecomm_orders.*', 'users.login', 'products.name')
         ->where('ecomm_orders.smartshipping', 1)->paginate(9);

      return view('admin.reports.smartshippingCustomers', compact('SSCustomers'));
   }

   public function smartShippingCustomersFilter(Request $request)
   {
      $value = $request->search;

      $SSCustomers = DB::table('ecomm_orders')
         ->join('users', 'ecomm_orders.id_user', '=', 'users.id')
         ->join('products', 'ecomm_orders.id_product', '=', 'products.id')
         ->select('ecomm_orders.*', 'users.login', 'products.name')
         ->where('ecomm_orders.smartshipping', 1)
         ->where('users.login', $value)
         ->paginate(9);

      return view('admin.reports.smartshippingCustomers', compact('SSCustomers'));
   }

   public function disableSmartshipping(Request $request)
   {
      // dd($request);
      try {

         $order = $request->order;
         $ecommOrders = EcommOrders::where('number_order', $order)->first();

         if (!isset($ecommOrders)) {
            return redirect()->back();
         }

         EcommOrders::where('number_order', $order)->update(['smartshipping' => 0]);
         if ($ecommOrders->client_backoffice == 1) {
            $user = User::where('id', $ecommOrders->id_user)->first();
            $user->smartshipping = 0;
            $user->save();
         } else {
            $user = EcommRegister::where('id', $ecommOrders->id_user)->first();
            $user->smartshipping = 0;
            $user->save();
         }

         $user_id = $user->id;

         $nCancel = new CancelSmart;
         $nCancel->user_id = $user_id;
         $nCancel->number_order = $order;
         $nCancel->reason = "Cancelled by admin";
         $nCancel->save();

         $log = new CustomLog;
         $log->content = "Cancel smartshipping by admin - user disabled = $user_id, order = $order";
         $log->user_id = auth()->user()->id;
         $log->operation = "cancel order $order";
         $log->controller = "ReportsAdminController";
         $log->http_code = 200;
         $log->route = "admin.reports.disableSmartshipping";
         $log->status = "success";
         $log->save();

      } catch (\Throwable $th) {

         return redirect()->back();

      }
      return redirect()->back();

   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      //
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
      //te
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

   public function getDatereportBonus(Request $request)
   {
      // dd();
      $bonus = ConfigBonus::all();
      $date = $request->date ?? date('Y-m');

      $transactions = DB::table('banco')
         ->join('users', 'users.id', '=', 'banco.user_id')
         ->whereIn('banco.order_id', function ($query) use ($date) {
            $query->select('number_order')
               ->from('payments_order_ecomms')
               ->where('created_at', 'LIKE', "%" . $date . "%");
         })
         ->where('price', '>', 0)
         ->groupBy('user_id')
         ->select('users.id', 'users.login', DB::raw('SUM(price) as total_price'))
         ->paginate(50);

      foreach($transactions as $v){
        $totalcomm[$v->{'id'}] = $v->total_price;
        // dd();
        $level_bonus[$v->{'id'}] = DB::select("SELECT sum(price) AS total FROM banco
            WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=1 and
            banco.user_id=".$v->{'id'});
         $generation_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=2 and
            banco.user_id=".$v->{'id'});
         $customer_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (3,4) and
            banco.user_id=".$v->{'id'});
         $personal_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=5 and
            banco.user_id=".$v->{'id'});
         $power3_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (6,7) and
            banco.user_id=".$v->{'id'});
         $lifestyle_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=8 and
            banco.user_id=".$v->{'id'});
         $leader_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=9 and
            banco.user_id=".$v->{'id'});
         $faststart_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description = 10 and
            banco.user_id=".$v->{'id'});
         $faststart_team[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description = 11 and
            banco.user_id=".$v->{'id'});
         $firstorder_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (12) and
            banco.user_id=".$v->{'id'});
         $advancement_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (13,14) and
            banco.user_id=".$v->{'id'});

        $totalcomm[$v->{'id'}] += $faststart_bonus[$v->{'id'}] ? $faststart_bonus[$v->{'id'}][0]->total : 0;
        $totalcomm[$v->{'id'}] += $faststart_team[$v->{'id'}] ? $faststart_team[$v->{'id'}][0]->total : 0;
        $totalcomm[$v->{'id'}] += $advancement_bonus[$v->{'id'}] ? $advancement_bonus[$v->{'id'}][0]->total : 0;
         ####VOLUME

         $personal_volume[$v->{'id'}] = DB::select("SELECT sum(score) as total FROM historic_score where level_from=0 and created_at like '%$date%' and user_id=".$v->{'id'});

         $PersonalVolumeExternal[$v->{'id'}] = DB::select("SELECT sum(qv) AS total FROM ecomm_orders WHERE id_user in (SELECT id from ecomm_registers WHERE recommendation_user_id='".$v->{'id'}."') and created_at like '%$date%' and number_order in (select number_order from payments_order_ecomms where status='paid')");

         $team_volume[$v->{'id'}] = DB::select("SELECT sum(score) as total FROM historic_score where level_from>0 and created_at like '%$date%' and user_id=".$v->{'id'});

         $career[$v->{'id'}] = DB::select("SELECT * FROM career_users
         join career on career.id=career_users.career_id
         where career_users.user_id = ".$v->{'id'}."
          and career_users.created_at like '%$date%' order by career_id desc limit 1;");


      }

      return view('admin.reports.bonus', compact('bonus', 'date', 'transactions','advancement_bonus','PersonalVolumeExternal','personal_volume','team_volume','career','level_bonus','generation_bonus','customer_bonus','personal_bonus','power3_bonus','lifestyle_bonus','leader_bonus','faststart_bonus','faststart_team','firstorder_bonus','totalcomm'));
   }

   public function reportBonus(Request $request)
   {
      $bonus = ConfigBonus::all();
      $date = date('Y-m');

        $transactions = DB::table('banco')
         ->join('users', 'users.id', '=', 'banco.user_id')
         ->whereIn('banco.order_id', function ($query) use ($date) {
            $query->select('number_order')
               ->from('payments_order_ecomms')
               ->where('created_at', 'LIKE', "%" . $date . "%");
         })
         ->where('price', '>', 0)
         ->groupBy('user_id')
         ->select('users.id', 'users.login', DB::raw('SUM(price) as total_price'))
         ->paginate(50);

      foreach($transactions as $v){
        $totalcomm[$v->{'id'}] = $v->total_price;
        // dd();
        $level_bonus[$v->{'id'}] = DB::select("SELECT sum(price) AS total FROM banco
            WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=1 and
            banco.user_id=".$v->{'id'});
         $generation_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=2 and
            banco.user_id=".$v->{'id'});
         $customer_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (3,4) and
            banco.user_id=".$v->{'id'});
         $personal_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=5 and
            banco.user_id=".$v->{'id'});
         $power3_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description in (6,7) and
            banco.user_id=".$v->{'id'});
         $lifestyle_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=8 and
            banco.user_id=".$v->{'id'});
         $leader_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description=9 and
            banco.user_id=".$v->{'id'});
         $faststart_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
            JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
            banco.price>0 and
            banco.description = 10 and
            banco.user_id=".$v->{'id'});

        $faststart_team[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
        JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
        banco.price>0 and
        banco.description = 11 and
        banco.user_id=".$v->{'id'});

        $firstorder_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
        JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
        banco.price>0 and
        banco.description in (12) and
        banco.user_id=".$v->{'id'});

        $advancement_bonus[$v->{'id'}] = DB::select("SELECT users.id, users.login, sum(price) AS total FROM banco
        JOIN users ON users.id = banco.user_id WHERE banco.created_at like '%$date%' and
        banco.price>0 and
        banco.description in (13,14) and
        banco.user_id=".$v->{'id'});

        $totalcomm[$v->{'id'}] += $faststart_bonus[$v->{'id'}] ? $faststart_bonus[$v->{'id'}][0]->total : 0;
        $totalcomm[$v->{'id'}] += $faststart_team[$v->{'id'}] ? $faststart_team[$v->{'id'}][0]->total : 0;
        $totalcomm[$v->{'id'}] += $advancement_bonus[$v->{'id'}] ? $advancement_bonus[$v->{'id'}][0]->total : 0;

         $PersonalVolumeExternal[$v->{'id'}] = DB::select("SELECT sum(qv) AS total FROM ecomm_orders WHERE id_user in (SELECT id from ecomm_registers WHERE recommendation_user_id='".$v->{'id'}."') and created_at like '%$date%' and number_order in (select number_order from payments_order_ecomms where status='paid')");

         ####VOLUME

         $personal_volume[$v->{'id'}] = DB::select("SELECT sum(score) as total FROM historic_score where level_from=0 and created_at like '%$date%' and user_id=".$v->{'id'});

         $team_volume[$v->{'id'}] = DB::select("SELECT sum(score) as total FROM historic_score where level_from>0 and created_at like '%$date%' and user_id=".$v->{'id'});

         $career[$v->{'id'}] = DB::select("SELECT * FROM career_users
         join career on career.id=career_users.career_id
         where career_users.user_id = ".$v->{'id'}."
          and career_users.created_at like '%$date%' order by career_id desc limit 1;");
      }
      return view('admin.reports.bonus', compact('bonus', 'date', 'transactions','advancement_bonus','personal_volume','team_volume','career','level_bonus','generation_bonus','customer_bonus','personal_bonus','power3_bonus','lifestyle_bonus','leader_bonus','faststart_bonus','faststart_team','firstorder_bonus', 'PersonalVolumeExternal','totalcomm'));
   }

   public function ordersPayed(Request $request)
   {
      $orders = null;
      $date = null;

      if ($request->has('date')) {
         $date = Carbon::createFromFormat('Y-m', $request->query('date'));

         $orders = PaymentOrderEcomm::whereRaw('LOWER(status) = ?', ['paid'])
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->orderBy('id', 'DESC')
            ->get();

         $date = $request->query('date');
         // dd($orders);
      } else {
         $orders = PaymentOrderEcomm::whereRaw('LOWER(status) = ?', ['paid'])
            ->orderBy('id', 'DESC')
            ->get();
      }

      foreach ($orders as $order) {
         $ecomm = EcommOrders::where('number_order', $order->number_order)->first();
         $user = null;
         $username = null;
         $login = null;

         if ($ecomm->client_backoffice == 1) {
            $user = User::where('id', $order->id_user)->first();
            $username = $user->name . ' ' . $user->last_name ?? '';
            $login = $user->login ?? '';
         } else {
            $user = EcommRegister::where('id', $order->id_user)->first();
            $username = $user->name . ' ' . $user->last_name ?? '';
            $login = '';
         }

         $order->username = $username;
         $order->login = $login;
      }

      return view('admin.reports.ordersProductPayed', compact('orders', 'date'));
   }

   public function monthlyCommissions()
   {

        $commissions = DB::select("SELECT users.id, users.name,users.login, SUM(banco.price) as total FROM banco
        JOIN users ON users.id = banco.user_id GROUP BY users.id, users.login, users.name");


        $reportCommission = ReportCommission::all();

        if (count($reportCommission) > 0) {

            foreach ($reportCommission as $commis) {
                ReportCommission::find($commis->id)->delete();
            }

            foreach ($commissions as $com) {
                $comm = new ReportCommission;
                $comm->id_commission = $com->id;
                $comm->user_commission = $com->login;
                $comm->total_commission = $com->total;

                $comm->save();
            }
        } else {
            foreach ($commissions as $com) {

                $comm = new ReportCommission;
                $comm->id_commission = $com->id;
                $comm->user_commission = $com->login;
                $comm->total_commission = $com->total;

                $comm->save();
            }
        }

        return view("admin.reports.monthlyCommissions", compact('commissions'));
   }

   public function monthlyCommissionsFilter(Request $request)
   {
        $data_in = $request['fdate'];
        $data_out = $request['sdate'];
        $name = $request['name'];
        $lastname = $request['lastname'];
        $filtros = "WHERE 0=0";
        if($data_in && $data_out)
        {
            $filtros .= " AND banco.created_at BETWEEN '$data_in' AND '$data_out'";
        }
        elseif ($data_in)
        {
            $filtros .= " AND banco.created_at >= '$data_in'";
        }
        elseif ($data_out)
        {
            $filtros .= " AND banco.created_at <= '$data_out'";
        }

        if($name){
            $filtros .= " AND users.name LIKE '%$name%'";
        }

        if($lastname){
            $filtros .= " AND users.last_name LIKE '%$lastname%'";
        }

        $commissions = DB::select("SELECT users.id, users.name, users.login, sum(banco.price) AS total FROM banco
        JOIN users ON users.id = banco.user_id $filtros GROUP BY users.id, users.login, users.name");
        return view("admin.reports.monthlyCommissions", compact('commissions', 'name', 'lastname', 'data_in', 'data_out'));
   }

   public function monthlyCommissionsExcel()
   {
      //   return Excel::download(new CommissionExport, 'commissions.xlsx');
   }

   public function monthlyCommissionsDetail($id)
   {
      $user_comm = User::where('id', $id)->first();
      $commissions = DB::select("SELECT * FROM banco WHERE banco.created_at LIKE '%2023-12%' AND user_id = '$id' AND price != 0");

      return view('admin.reports.monthlyCommissionDetail', compact('commissions', 'user_comm'));
   }

   public function smartshippingHistory(Request $request){
        $user_id = Auth::id();
        $name = $request['name'];
        $lastname = $request['lastname'];
        $smartshipping =   EditSmarthipping::with(['user', 'product'])
            ->whereHas('user', function($query) use ($name, $lastname) {
                $query->where('name', 'LIKE', "%$name%")
                ->where('last_name', 'LIKE', "%$lastname%");
            })
            ->get();
        return view('admin.reports.smartshipping-history', compact('smartshipping','name','lastname'));
   }
}
