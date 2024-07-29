<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\User;
use App\Models\CareerUser;
use App\Models\WithdrawRequest;
use App\Models\Investment;
use App\Models\HistoricScore;
use App\Models\PaymentOrderEcomm;
use App\Models\EcommRegister;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OrderPackage;
use Khill\Lavacharts\Lavacharts;
use App\Models\ChatMessage;
use App\Models\Chat;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\EcommOrders;

class HomeAdminController extends Controller
{
   //  /**
   //   * Create a new controller instance.
   //   *
   //   * @return void
   //   */
   //  public function __construct()
   //  {
   //      $this->middleware('auth');
   //  }

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function indexAdmin()
   {
      $users = User::orderBy('id', 'DESC')->where('activated', true)->paginate(7);

      $usersCont      = User::count();
      $CountCustomers = EcommRegister::count();
      $SSDistributor  = User::where('smartshipping', 1)->count();
      $SSCustomers    = EcommOrders::where('smartshipping', 1)->count();

      $ecommOrders = PaymentOrderEcomm::whereRaw('LOWER(status) = ?', ['paid'])->sum('total_price');


      $from = Carbon::now()->startOfMonth();
      $to   = Carbon::now()->endOfMonth();

      $ecommOrdersThisMonth = PaymentOrderEcomm::whereRaw('LOWER(status) = ?', ['paid'])
         ->whereBetween('created_at', [$from, $to])
         ->sum('total_price');


      $SomaTodosBonus = Banco::where('price', '>', 0)
         ->selectRaw('sum(price) as total')
         ->first();


      $poll = HistoricScore::where('description', "9")->sum('score');

      $specialCommision = HistoricScore::where('description', "8")->sum('score');

      $userpay = OrderPackage::where('status', 1)->where('payment_status', 1)->distinct()->get(['user_id'])->count();



      if (empty($SomaTodosBonus)) {
         $totalbanco1 = 0;
      } else {
         $totalbanco1 = $SomaTodosBonus->total;
      }

      $NewRank = CareerUser::whereBetween('created_at', [$from, $to])->count();

      $commissionSum = $totalbanco1;

      $orderpackages = OrderPackage::orderBy('id', 'DESC')->paginate(20);

      $lava = new Lavacharts;
      $popularity = $lava->DataTable();
      $data = DB::table('users')
         ->select(DB::raw('country , count(country) as countcountry'))
         ->groupBy('country')
         ->get()->toArray();

      $countcountry = [];
      foreach ($data as $item) {
         array_push($countcountry, [$item->country, $item->countcountry]);
      }

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

        $data_now      = date('Y-m-d');
        $support       = Chat::where('status', 0)->get();
        $count_support = 0;

        foreach ($support as $chat) {

            $moment  = $chat->created_at;
            $explode = explode(' ', $moment);

            if ($explode[0] == $data_now) {

                $count_support = $count_support + 1;
            }
        }

        $user_register = User::all();
        $count_user    = 0;

        foreach ($user_register as $register) {

            $moments = $register->created_at;
            $explods = explode(' ', $moments);

            if ($explods[0] == $data_now) {
                $count_user = $count_user + 1;
            }
        }

        $stok_register = Stock::all();
        $count_stok    = 0;

        foreach ($stok_register as $report_stok) {

            $momentx = $report_stok->created_at;
            $explodx = explode(' ', $momentx);

            if ($explodx[0] == $data_now) {
                $count_stok = $count_stok + 1;
            }
        }

      return view('admin.homeAdmin', ['lava' => $lava])->with(compact('users', 'ecommOrders', 'NewRank', 'SSCustomers', 'ecommOrdersThisMonth', 'usersCont', 'SSDistributor', 'commissionSum', 'orderpackages', 'lava', 'poll', 'CountCustomers', 'userpay', 'count_support', 'count_user', 'count_stok'));
   }
}
