<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\ConfigBonus;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\IpAccessApi;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\Rede;
use App\Models\SmartshippingPaymentsRecurring;
use App\Models\User;
use App\Traits\ApiReports;
use App\Traits\ApiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    use ApiUser;
    use ApiReports;
    public function report(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["downline_volume"] = $this->getDownlineVolume($user);
            $data["personal_volume"] = $this->getPersonalVolume($user);
            $data["downline_volume_last_month"] = $this->getDownlineVolumeLastMonth($user);
            $data["personal_volume_last_month"] = $this->getPersonalVolumeLastMonth($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function enrollments(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["new_enrollments"] = $this->getNewEnrollments($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function mydirects(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["my_directs"] = $this->getMyDirectsWithQV($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function myOrdersSmartshipping(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["my_orders_smartshipping"] = $this->getMyOrdersSmart($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function newRankAdvancement(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["new_rank_Advancement"] = $this->getNewRankAdvancement($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function reportsHome(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];
            $data["my_directs"] = $this->getMyDirectsWithQV($user);
            $data["my_orders_smartshipping"] = $this->getMyOrdersSmart($user);
            $data["new_enrollments"] = $this->getNewEnrollments($user);
            $data["new_rank_Advancement"] = $this->getNewRankAdvancement($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function orderhistory(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports/orderhistory";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];
            $data["orders_history"] = $this->ordersProduct($user);
            $data["packages_history"] = $this->ordersPackage($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function myComission(Request $request)
    {
        try {
            $user = $this->getUser($request);
            $fdate = $request->first_date ?? null;
            $sdate = $request->second_date ?? null;

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports/comission";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            if ((empty($fdate) and !empty($sdate)) || (!empty($fdate) and empty($sdate))) {
                return response()->json(['error' => "Date is required"]);
            }

            $data = [];
            $data["comissions"] = $this->comissions($request, $user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function mysmartshipping(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports/smartshipping";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];
            $data["smartshipping"] = $this->smartshipping($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function newsmartshipping(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/newsmartshipping";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $products = Product::where('activated', 1)->get();

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

            foreach ($products as $p) {
                $p->img_1 = asset("/img/products/" . $p->img_1);
            }

            $ip_order = $_SERVER['REMOTE_ADDR'];
            // $ip_order = '194.156.125.61';
            $url = "http://ip-api.com/json/{$ip_order}?fields=country";

            $response = file_get_contents($url);
            $countryIpOrder = json_decode($response, true);
            $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
            $countryIp = ["country" => $countryIpOrder];
            $countryIp = $user->country;

            return response()->json(['products' => $products, 'countryIp' => $countryIp]);
        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function mySmartshippingCancel(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $validatedData = Validator::make($request->all(), [
                'number_order' => 'required|numeric',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/reports/smartshipping";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $pedido = EcommOrders::where('number_order', $request->number_order)->get();

            if (count($pedido) < 1) {
                return response()->json(['error' => "Order not found"]);
            }

            if ($pedido[0]->smartshipping == 0) {
                return response()->json(['error' => "Smartshipping alredy cancelled"]);
            }

            foreach ($pedido as $order) {
                $order->smartshipping = 0;
                $order->save();

            }
            $log = new CustomLog;
            $log->content = json_encode($requestFormated);
            $log->user_id = $user->id;
            $log->operation = "Cancel smartshipping by app " . $request->number_order;
            $log->controller = "app/controller/Api/ReportsController";
            $log->http_code = 200;
            $log->route = ".mySmartshippingCancel";
            $log->status = "SUCCESS";
            $log->save();



            $data = [];
            $data["cancelled"] = [
                "number_order" => $request->number_order,
            ];
            $data["smartshipping"] = $this->smartshipping($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function myComissionPerMonth(Request $request)
    {
        try {
            //code...
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $dados = Banco::with('user')
                ->where('user_id', $user->id)
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

            return response()->json([
                "total" => $total,
                "data" => $commissions
            ]);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }

    }

    public function myreferrals(Request $request)
    {
        try {
            //code...
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $rede = Rede::where('user_id', $user->id)->first();

            if ($rede) {
                $networks = Rede::where('upline_id', $rede->id)->get();
            } else {
                $networks = [];
            }

            foreach ($networks as $network) {

                $id_user = $network->user_id;

                $directVolume = HistoricScore::where('user_id', $id_user)
                    ->where('level_from', 1)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')")
                    ->sum('score');

                $indirectVolume = HistoricScore::where('user_id', $id_user)
                    ->where('level_from', '>', 1)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')")
                    ->sum('score');

                $totalVolume = $indirectVolume + $directVolume;

                $personalVolume = HistoricScore::where('user_id', $id_user)
                    ->where('level_from', 0)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')")
                    ->sum('score');


                $directCustomers = EcommRegister::where('recommendation_user_id', $id_user)->get();
                $personalVolumeEcomm = 0;

                if (count($directCustomers) > 0) {
                    foreach ($directCustomers as $value) {
                        $idEcomm = $value->id;
                        // $qvEcomm = Illuminate\Support\Facades\DB::select("SELECT SUM(qv) AS total FROM ecomm_orders WHERE id_user=$idEcomm AND client_backoffice = 0 AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND number_order IN (SELECT number_order FROM payments_order_ecomms WHERE status = 'paid')");

                        $qvEcomm = EcommOrders::where('id_user', $idEcomm)
                            ->where('client_backoffice', 0)
                            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')")
                            ->whereIn('number_order', function ($query) {
                                $query->select('number_order')
                                    ->from('payments_order_ecomms')
                                    ->where('status', 'paid');
                            })
                            ->sum('qv');

                        $personalVolumeEcomm += $qvEcomm;
                    }
                }

                $personalVolume = $personalVolume + $personalVolumeEcomm;


                if (!empty($network->user->image_path)) {
                    $network->image = asset('storage/' . $network->user->image_path);
                } else {
                    $network->image = "../../../assetsWelcome/images/favicon.jpeg";
                }

                $network->volume = number_format($totalVolume, 2, ',', '.');
                $network->name = $network->user->name . " " . $network->user->last_name;
                $network->login = $network->user->login;
                $network->email = $network->user->email;
                $network->cell = $network->user->cell;
                $network->personalVolume = number_format($personalVolume, 2, ',', '.');

                if ($network->getTypeActivated($network->id) == 'AllCards') {
                    $network->status = "Active";
                } else if ($network->getTypeActivated($network->id) == 'PreRegistration') {
                    $network->status = "Pre-Registration";
                } else {
                    $network->status = "Inactive";
                }

            }

            return response()->json($networks);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function enrolledcustomers(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $indication = EcommRegister::where("recommendation_user_id", $user->id)->get();
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

            return response()->json($array_user);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function newrecruits(Request $request)
    {
        try {
            //code...
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $id_session = $user->id;

            if (isset($request->month) && isset($request->year)) {
                $newrecruits = User::whereMonth('created_at', $request->month)
                    ->whereYear('created_at', $request->year)
                    ->where('recommendation_user_id', $id_session)
                    ->get();
            } else {
                $newrecruits = User::where('created_at', '>', now()->subMonth())
                    ->where('recommendation_user_id', $id_session)
                    ->get();
            }


            $array_user = array();

            foreach ($newrecruits as $data) {

                $my_qv = 0;
                $my_cv = 0;

                $payments = PaymentOrderEcomm::where('id_user', $data->id)->where('status', 'paid')->get();

                foreach ($payments as $payment) {
                    $my_qv += EcommOrders::where('number_order', $payment->number_order)
                        ->sum('qv');

                    $my_cv += EcommOrders::where('number_order', $payment->number_order)
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
                    'qv' => number_format($my_qv, 2, ',', '.'),
                    'cv' => number_format($my_cv, 2, ',', '.')
                ];

                if (!in_array($data_user, $array_user)) {

                    array_push($array_user, $data_user);
                }
            }

            return response()->json($array_user);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function costumerrecruits(Request $request)
    {
        try {
            //code...
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $costumerrecruits = EcommRegister::where('recommendation_user_id', $user->id)->where('created_at', '>', now()->subMonth())->get();

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

            return response()->json($array_user);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function smartshippeople(Request $request)
    {
        try {
            //code...
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $id_session = $user->id;

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

            return response()->json([
                "ditributors" => $smartshippeople,
                "customers" => $costumer
            ]);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function volumereport(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $id_user = $user->id;

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
                ->get();


            $total_qv = 0;
            $total_ordens = 0;

            foreach ($scores as $scrv) {
                $total_qv = $total_qv + $scrv->qv;
                $total_ordens = $total_ordens + $scrv->total;

                $user = User::find($scrv->id_user);
                if (isset($user)) {
                    $scrv->login = $user->login;
                } else {
                    $scrv->login = "Not indentified";
                }
            }

            return response()->json([
                "total_qv" => $total_qv,
                "total_orders" => $total_ordens,
                "data" => $scores
            ]);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function volumereportfilter(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $id_user = $user->id;

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

            $total_qv = 0;
            $total_ordens = 0;

            foreach ($scores_date as $scr) {
                $total_qv = $total_qv + $scr->qv;
                $total_ordens = $total_ordens + $scr->total;

                $user = User::find($scr->id_user);
                if (isset($user)) {
                    $scr->login = $user->login;
                } else {
                    $scr->login = "Not indentified";
                }

            }

            return response()->json([
                "total_qv" => $total_qv,
                "total_orders" => $total_ordens,
                "data" => $scores_date
            ]);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function bonuslist(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $user_id = $user->id;
            $data_ini = $request->first_date;
            $data_fim = $request->second_date;
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
            $query->orderBy('description', 'ASC');

            $bonusGroups = $query->get();
            $configBonus = ConfigBonus::all();
            $bonusTotal = [];


            foreach ($bonusGroups as $bonus) {
                $description_ids = array_keys($bonusTotal);
                $key = intval($bonus->description);

                $user = User::find($bonus->user_id);
                if (isset($user)) {
                    $bonus->user_name = $user->name;
                    $bonus->user_lastname = $user->last_name;
                } else {
                    $bonus->user_name = "";
                    $bonus->user_lastname = "";
                }

                $name_bonus = ConfigBonus::find($bonus->description);
                if (isset($name_bonus)) {
                    $bonusTotal[$key]['name'] = $name_bonus->description;
                } else {
                    if ($bonus->description == 99) {
                        $bonusTotal[$key]['name'] = "Payment order";
                    } else if ($bonus->description == 98) {
                        $bonusTotal[$key]['name'] = "Withdraw";
                    }
                }


                if (in_array($bonus->description, $description_ids)) {
                    $bonusTotal[$key]['total'] += $bonus->price;
                    $bonusTotal[$key]['count'] += 1;
                    $bonusTotal[$key]['bonus_list'][] = $bonus;
                } else {
                    $bonusTotal[$key]['total'] = $bonus->price;
                    $bonusTotal[$key]['count'] = 1;
                    $bonusTotal[$key]['bonus_list'][] = $bonus;
                }
            }

            $users_temp = [];

            if (isset($bonusTotal[11]['bonus_list'])) {
                foreach ($bonusTotal[11]['bonus_list'] as $bonus_order) {
                    $users_temp[] = $bonus_order->order_id;
                }
            }


            return response()->json(compact('bonusTotal', 'configBonus', 'data_ini', 'data_fim'));
            // return view('report.bonus-group', compact('bonusTotal', 'configBonus', 'data_ini', 'data_fim', 'users_11'));

        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function teamorders(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $new_orders = EcommOrders::all();

            $id_session = $user->id;


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


                $team_id = $item->id;
                // $orders_total = Illuminate\Support\Facades\DB::select("SELECT * FROM payments_order_ecomms WHERE id_user = '$team_id' AND status = 'paid'");
                $orders_total = PaymentOrderEcomm::where("id_user", $team_id)->get();
                $total_values = 0;
                $qv = 0;
                $cv = 0;

                foreach ($orders_total as $values_price) {
                    $total_values += $values_price->total_price;

                    $qv += DB::table('ecomm_orders')
                        ->where('number_order', $values_price->number_order)
                        ->sum('qv');

                    $cv += DB::table('ecomm_orders')
                        ->where('number_order', $values_price->number_order)
                        ->sum('cv');
                }

                $item->total = $total_values;
                $item->qv = $qv;
                $item->cv = $cv;

            }

            return response()->json(['data' => $all_resgister]);

        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
        }
    }
}
