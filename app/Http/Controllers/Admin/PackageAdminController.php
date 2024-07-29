<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ClubSwanController;
use App\Http\Requests\Admin\SearchRequest;
use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\ConfigBonus;
use App\Models\ConfigBonusunilevel;
use App\Models\HistoricScore;
use App\Models\InvoicesFakturoid;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\ShippingPrice;
use App\Models\SubjectsFakturoid;
use App\Models\TaxPackage;
use App\Models\User;
use App\Traits\CustomLogTrait;
use App\Traits\OrderBonusTrait;
use App\Traits\PaymentLogTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Fakturoid\Client as FakturoidClient;

class PackageAdminController extends Controller
{
    use CustomLogTrait, PaymentLogTrait, OrderBonusTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::orderBy('id', 'DESC')->paginate(9);

        return view('admin.packages.packages', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = new Package;
        $package->name = $request->name;
        $package->price = $request->price;
        $package->commission = $request->commission;
        $package->activated = $request->activated;
        $package->long_description = $request->long_description;
        $package->description_fees = $request->description_fees;
        $package->plan_id = "tag";

        try {
            // new upload image
            if ($request->hasFile('img') && $request->file('img')->isValid()) {

                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/packages'), $imageName);
                $package->img = $imageName;
            }

            $package->save();

            return redirect()->route('admin.packages.index');

        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotcreate'))->error();
            return redirect()->back();
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
        $package = Package::find($id);

        return view('admin.packages.edit', compact('package'));
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
        try {
            $data = $request->only([
                'name',
                'price',
                'commission',
                'activated',
                'type',
                'long_description',
                'description_fees',
                'plan_id'
            ]);

            $package = package::find($id);

            // new upload image
            if ($request->hasFile('img') && $request->file('img')->isValid()) {

                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/packages'), $imageName);
                $data['img'] = $imageName;
            }

            $package->update($data);

            $this->createLog('Package updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.pkgupdate'))->success();
            return redirect()->route('admin.packages.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotupdate'))->error();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderupdate(Request $request, $id)
    {
        try {

            $status = $request->get('status');
            $payment_status = $request->get('payment_status');

            $data = [
                "status" => $status,
                "payment_status" => $payment_status
            ];

            $Orderpackage = OrderPackage::find($id);

            // if ($Orderpackage->status == 1) {
            //     return redirect()->back();
            // }

            $Orderpackage->update($data);

            // dd($Orderpackage);

            // if ($Orderpackage->status == 1 && $Orderpackage->payment_status == 1) {
            ####POPULA A ARRAY COM O BONUS UNILEVEL PARA ENVIAR PRA FUNÇÃO

            // $array_unilevel = array();
            // $array_unilevel_peoples = array();
            // $pega_config_unilevel = ConfigBonusunilevel::get();

            // foreach ($pega_config_unilevel as $pega_config_unilevel) {

            //     if ($pega_config_unilevel->status == 1) {
            //         $array_unilevel_peoples[$pega_config_unilevel->level] = $pega_config_unilevel->minimum_users;
            //         $array_unilevel[$pega_config_unilevel->level] = $pega_config_unilevel->value_percent;
            //     } else {
            //         $array_unilevel_peoples[$pega_config_unilevel->level] = "";
            //         $array_unilevel[$pega_config_unilevel->level] = "";
            //     }
            // }

            ####CHECA SE ACHA O USUARIO COM O PEDIDO NA TABELA BANCO
            // $userrec = User::find($Orderpackage->user_id);

            // if ($Orderpackage->package->type != 'activator') {
            //     $userrec->update(['activated' => 1]);
            // }

            // $check_ja_existe = 0;
            // if ($userrec->recommendation_user_id >= 0 && !empty($userrec->recommendation_user_id)) {
            //     $recommendation = User::find($userrec->recommendation_user_id);

            //     if ($recommendation->getDirectsWithOrders($recommendation->id) % 10 == 0) {
            //         if ($recommendation->getDirectsWithOrders($recommendation->id) == 10) {
            //             $data = [
            //                 "score" => 4,
            //                 "status" => 1,
            //                 "description" => "9",
            //                 "user_id" => $recommendation->id,
            //                 "orders_package_id" => $Orderpackage->id,
            //                 "user_id_from" => $userrec->id,
            //                 "level_from" => "0",
            //             ];

            // $score = HistoricScore::create($data);
            // } else {
            //     $data = [
            //         "score" => 2,
            //         "status" => 1,
            //         "description" => "9",
            //         "user_id" => $recommendation->id,
            //         "orders_package_id" => $Orderpackage->id,
            //         "user_id_from" => $userrec->id,
            //         "level_from" => "0",
            //     ];

            // $score = HistoricScore::create($data);
            //         }
            //     }

            //     $check_ja_existe = Banco::where('user_id', $userrec->recommendation_user_id)->where('order_id', $Orderpackage->id)->count();
            // }



            // $verifica_banco_steaking = Banco::where('user_id', $Orderpackage->user_id)->where('order_id', $Orderpackage->id)->get();

            // if (count($verifica_banco_steaking) == 0) {

            // $commission = $Orderpackage->package->commission;

            // $data = [
            //     "price"       => $commission,
            //     "description" => "7",
            //     "status"      => 1,
            //     "user_id"     => $userrec->id,
            //     "order_id"    => $Orderpackage->id,
            //     "level_from"  => 0,
            // ];

            // $banco = Banco::create($data);
            //         $valorespecial = isset($userrec->special_comission) && $userrec->special_comission_active ? $userrec->special_comission : 0;
            //         $valorespecialcommition = $Orderpackage->package->commission * ($valorespecial / 100);

            //         if ($valorespecialcommition > 0) {
            //             $data = [
            //                 "price" => $valorespecialcommition,
            //                 "description" => "8",
            //                 "status" => 1,
            //                 "user_id" => $userrec->id,
            //                 "order_id" => $Orderpackage->id,
            //                 "level_from" => 0,
            //             ];

            //             $banco = Banco::create($data);
            //         }
            //     }
            //     $config_bonus = ConfigBonus::where('id', '=', '1')->orWhere('id', '=', '2')->where('activated', 1)->get();

            //     if ($check_ja_existe == 0) {
            //         if (count($config_bonus) > 0) {
            //             $this->bonusDiretoIndireto_e_Volume($Orderpackage->user_id, $Orderpackage->package->commission, $Orderpackage->id, $array_unilevel, $array_unilevel_peoples);
            //         }
            //     }
            //     $this->createPaymentLog('Payment processed successfully', 200, 'success', $id, "Payment made by Admin");
            // }

            $this->createLog('OrderPackage updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.pkgupdate'))->success();
            return redirect()->route('admin.packages.orderPackages');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.orderpkgnotupdate'))->error();
            return redirect()->route('admin.packages.orderPackages');
        }
    }

    public function payall()
    {
        $orders = OrderPackage::where('status', 0)->where('payment_status', 0)->get();
        try {
            foreach ($orders as $order) {
                $status = 1;
                $payment_status = 1;
                $data = [
                    "status" => $status,
                    "payment_status" => $payment_status
                ];

                $id = $order->id;
                $Orderpackage = OrderPackage::find($id);

                $Orderpackage->update($data);

                // if ($Orderpackage->status == 1 && $Orderpackage->payment_status == 1) {
                ####POPULA A ARRAY COM O BONUS UNILEVEL PARA ENVIAR PRA FUNÇÃO
                // $array_unilevel = array();
                // $array_unilevel_peoples = array();
                // $pega_config_unilevel = ConfigBonusunilevel::get();

                // foreach ($pega_config_unilevel as $pega_config_unilevel) {

                //     if ($pega_config_unilevel->status == 1) {
                //         $array_unilevel_peoples[$pega_config_unilevel->level] = $pega_config_unilevel->minimum_users;
                //         $array_unilevel[$pega_config_unilevel->level] = $pega_config_unilevel->value_percent;
                //     } else {
                //         $array_unilevel_peoples[$pega_config_unilevel->level] = "";
                //         $array_unilevel[$pega_config_unilevel->level] = "";
                //     }
                // }
                ####CHECA SE ACHA O USUARIO COM O PEDIDO NA TABELA BANCO
                // $userrec = User::find($Orderpackage->user_id);

                // if ($Orderpackage->package->type != 'activator') {
                //     $userrec->update(['activated' => 1]);
                // }

                // $check_ja_existe = 0;
                // if ($userrec->recommendation_user_id >= 0 && !empty($userrec->recommendation_user_id)) {
                //     $recommendation = User::find($userrec->recommendation_user_id);

                //     if ($recommendation->getDirectsWithOrders($recommendation->id) % 10 == 0) {
                //         if ($recommendation->getDirectsWithOrders($recommendation->id) == 10) {
                //             $data = [
                //                 "score" => 4,
                //                 "status" => 1,
                //                 "description" => "9",
                //                 "user_id" => $recommendation->id,
                //                 "orders_package_id" => $Orderpackage->id,
                //                 "user_id_from" => $userrec->id,
                //                 "level_from" => "0",
                //             ];

                // $score = HistoricScore::create($data);
                // } else {
                //     $data = [
                //         "score" => 2,
                //         "status" => 1,
                //         "description" => "9",
                //         "user_id" => $recommendation->id,
                //         "orders_package_id" => $Orderpackage->id,
                //         "user_id_from" => $userrec->id,
                //         "level_from" => "0",
                //     ];

                // $score = HistoricScore::create($data);
                // }
                //     }
                //     $check_ja_existe = Banco::where('user_id', $userrec->recommendation_user_id)->where('order_id', $Orderpackage->id)->count();
                // }

                // $verifica_banco_steaking = Banco::where('user_id', $Orderpackage->user_id)->where('order_id', $Orderpackage->id)->get();

                // if (count($verifica_banco_steaking) == 0) {

                // $commission = $Orderpackage->package->commission;

                // $data = [
                //     "price"       => $commission,
                //     "description" => "7",
                //     "status"      => 1,
                //     "user_id"     => $userrec->id,
                //     "order_id"    => $Orderpackage->id,
                //     "level_from"  => 0,
                // ];

                // $banco = Banco::create($data);
                //         $valorespecial = isset($userrec->special_comission) && $userrec->special_comission_active ? $userrec->special_comission : 0;
                //         $valorespecialcommition = $Orderpackage->package->commission * ($valorespecial / 100);

                //         if ($valorespecialcommition > 0) {
                //             $data = [
                //                 "price" => $valorespecialcommition,
                //                 "description" => "8",
                //                 "status" => 1,
                //                 "user_id" => $userrec->id,
                //                 "order_id" => $Orderpackage->id,
                //                 "level_from" => 0,
                //             ];

                //             $banco = Banco::create($data);
                //         }
                //     }
                //     $config_bonus = ConfigBonus::where('id', '=', '1')->orWhere('id', '=', '2')->where('activated', 1)->get();

                //     if ($check_ja_existe == 0) {
                //         if (count($config_bonus) > 0) {
                //             $this->bonusDiretoIndireto_e_Volume($Orderpackage->user_id, $Orderpackage->package->commission, $Orderpackage->id, $array_unilevel, $array_unilevel_peoples);
                //         }
                //     }
                //     $this->createPaymentLog('Payment processed successfully', 200, 'success', $id, "Payment made by Admin");
                // }
            }

            $this->createLog('OrderPackage updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.pkgupdate'))->success();
            return redirect()->route('admin.packages.orderPackages');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.orderpkgnotupdate'))->error();
            return redirect()->route('admin.packages.orderPackages');
        }
    }

    static public function orderUpdateKYC()
    {
        try {
            echo 'INICIO';
            $orders = OrderPackage::where('status', 0)->where('payment_status', 0)->get();
            foreach ($orders as $order) {
                echo 'LOOP';
                $userClub = User::where('id', $order->user_id)->first();
                echo $userClub->contact_id;
                if ($userClub->contact_id != NULL && $userClub->contact_id != '') {
                    $response = ClubSwanController::kycStatic(array('contactId' => $userClub->contact_id));
                } else {
                    $json = '{
                        "status": "fail",
                        "data": {
                            "overallStatus": "PENDING"
                        }
                    }';
                    $response = json_decode($json);
                }
                if ($response->status == 'success' && $response->data->overallStatus == 'APPROVED') {
                    $status = 1;
                    $payment_status = 1;
                    $data = [
                        "status" => $status,
                        "payment_status" => $payment_status
                    ];


                    $Orderpackage = OrderPackage::find($order->id);
                    $Orderpackage->update($data);

                    if ($Orderpackage->status == 1 && $Orderpackage->payment_status == 1) {
                        ####POPULA A ARRAY COM O BONUS UNILEVEL PARA ENVIAR PRA FUNÇÃO
                        $array_unilevel = array();
                        $array_unilevel_peoples = array();
                        $pega_config_unilevel = ConfigBonusunilevel::get();

                        foreach ($pega_config_unilevel as $pega_config_unilevel) {

                            if ($pega_config_unilevel->status == 1) {
                                $array_unilevel_peoples[$pega_config_unilevel->level] = $pega_config_unilevel->minimum_users;
                                $array_unilevel[$pega_config_unilevel->level] = $pega_config_unilevel->value_percent;
                            } else {
                                $array_unilevel_peoples[$pega_config_unilevel->level] = "";
                                $array_unilevel[$pega_config_unilevel->level] = "";
                            }
                        }

                        ####CHECA SE ACHA O USUARIO COM O PEDIDO NA TABELA BANCO
                        $userrec = User::find($Orderpackage->user_id);

                        if ($Orderpackage->package->type != 'activator') {
                            $userrec->update(['activated' => 1]);
                        }

                        $check_ja_existe = 0;
                        if ($userrec->recommendation_user_id >= 0 && !empty($userrec->recommendation_user_id)) {
                            $recommendation = User::find($userrec->recommendation_user_id);

                            if ($recommendation->getDirectsWithOrders($recommendation->id) % 10 == 0) {
                                if ($recommendation->getDirectsWithOrders($recommendation->id) == 10) {
                                    $data = [
                                        "score" => 4,
                                        "status" => 1,
                                        "description" => "9",
                                        "user_id" => $recommendation->id,
                                        "orders_package_id" => $Orderpackage->id,
                                        "user_id_from" => $userrec->id,
                                        "level_from" => "0",
                                    ];

                                    // $score = HistoricScore::create($data);
                                } else {
                                    $data = [
                                        "score" => 2,
                                        "status" => 1,
                                        "description" => "9",
                                        "user_id" => $recommendation->id,
                                        "orders_package_id" => $Orderpackage->id,
                                        "user_id_from" => $userrec->id,
                                        "level_from" => "0",
                                    ];

                                    // $score = HistoricScore::create($data);
                                }
                            }

                            $check_ja_existe = Banco::where('user_id', $userrec->recommendation_user_id)->where('order_id', $Orderpackage->id)->count();
                        }



                        $verifica_banco_steaking = Banco::where('user_id', $Orderpackage->user_id)->where('order_id', $Orderpackage->id)->get();

                        if (count($verifica_banco_steaking) == 0) {

                            // $commission = $Orderpackage->package->commission;

                            // $data = [
                            //     "price"       => $commission,
                            //     "description" => "7",
                            //     "status"      => 1,
                            //     "user_id"     => $userrec->id,
                            //     "order_id"    => $Orderpackage->id,
                            //     "level_from"  => 0,
                            // ];

                            // $banco = Banco::create($data);
                            $valorespecial = isset($userrec->special_comission) && $userrec->special_comission_active ? $userrec->special_comission : 0;
                            $valorespecialcommition = $Orderpackage->package->commission * ($valorespecial / 100);

                            if ($valorespecialcommition > 0) {
                                $data = [
                                    "price" => $valorespecialcommition,
                                    "description" => "8",
                                    "status" => 1,
                                    "user_id" => $userrec->id,
                                    "order_id" => $Orderpackage->id,
                                    "level_from" => 0,
                                ];

                                $banco = Banco::create($data);
                            }
                        }
                        $config_bonus = ConfigBonus::where('id', '=', '1')->orWhere('id', '=', '2')->where('activated', 1)->get();

                        if ($check_ja_existe == 0) {
                            if (count($config_bonus) > 0) {
                                PackageAdminController::bonusDiretoIndireto_e_VolumeStatic($Orderpackage->user_id, $Orderpackage->package->commission, $Orderpackage->id, $array_unilevel, $array_unilevel_peoples);
                            }
                        }
                        DB::table('payment_log')->insert(
                            [
                                'content' => 'Payment processed successfully - KYC Approved',
                                'order_package_id' => $order->id,
                                'operation' => 'orderUpdateKYC',
                                'controller' => 'PackageAdminController',
                                'http_code' => 200,
                                'route' => 'PackageAdminController.orderUpdateKYC',
                                'status' => 'success',
                                'json' => "Payment made by KYC Status Cron - " . json_encode($response)
                            ]
                        );
                    }
                    DB::table('custom_log')->insert(
                        [
                            'content' => 'OrderPackage updated successfully - KYC Approved',
                            'user_id' => '0',
                            'operation' => 'orderUpdateKYC',
                            'controller' => 'PackageAdminController',
                            'http_code' => 200,
                            'route' => 'PackageAdminController.orderUpdateKYC',
                            'status' => 'success',
                        ]
                    );
                }
            }
            return true;
        } catch (Exception $e) {
            echo "|||||   " . $e->getMessage();
            DB::table('custom_log')->insert(
                [
                    'content' => $e->getMessage() . ' - KYC Error',
                    'user_id' => '0',
                    'operation' => 'orderUpdateKYC',
                    'controller' => 'PackageAdminController',
                    'http_code' => 500,
                    'route' => Route::currentRouteName(),
                    'status' => 'error',
                ]
            );
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $package = Package::find($id);
            $package->activated = false;

            $package->update();
            $this->createLog('Package removed successfully', 204, 'success', auth()->user()->id);
            flash(__('admin_alert.pkgremove'))->success();
            return redirect()->route('admin.packages.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotremove'))->error();
            return redirect()->back();
        }
    }

    public function packageFilter($parameter)
    {
        try {
            $packageSearch = Package::orderBy('id', 'DESC');

            //Filters
            switch ($parameter) {
                case 'activated':
                    $packageSearch->where('activated', true);
                    break;
                case 'desactivated':
                    $packageSearch->where('activated', false);
                    break;
            }

            $packages = $packageSearch->paginate(9);
            return view('admin.packages.packages', compact('packages'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->back();
        }
    }

    public function search(SearchRequest $request)
    {
        try {
            $data = $request->search;
            $packages = Package::where('name', 'like', '%' . $data . '%')->paginate(9);
            flash(__('admin_alert.pkgfound'))->success();
            return view('admin.packages.packages', compact('packages'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->back();
        }
    }

    public function orderPackages()
    {
        $orderpackages = OrderPackage::orderBy('id', 'DESC')->paginate(9);

        return view('admin.packages.orders', compact('orderpackages'));
    }

    public function searchOrdersCorporate(SearchRequest $request)
    {

        try {

            $data = $request->search;
            if (is_numeric($data)) {
                $orderpackages = OrderPackage::where('id', 'like', '%' . $data . '%')->orderBy('id', 'DESC')->get();
            } else {
                $orderpackages = DB::table('orders_package')
                    ->selectRaw('*,orders_package.id as id')
                    ->join('users', 'orders_package.user_id', '=', 'users.id')
                    ->join('packages', 'orders_package.package_id', '=', 'packages.id')
                    ->where('users.name', 'like', '%' . $data . '%')
                    ->orWhere('users.login', 'like', '%' . $data . '%')
                    ->get();
            }

            flash(__('admin_alert.userfound'))->success();
            return view('admin.packages.ordersCorporate', compact('orderpackages'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.usernotfound'))->error();
            return redirect()->back();
        }
    }

    public function searchOrders(SearchRequest $request)
    {

        try {

            $data = $request->search;
            if (is_numeric($data)) {
                $orderpackages = OrderPackage::where('id', 'like', '%' . $data . '%')->orderBy('id', 'DESC')->paginate(9);
            } else {
                $orderpackages = DB::table('orders_package')
                    ->selectRaw('*,orders_package.id as id')
                    ->join('users', 'orders_package.user_id', '=', 'users.id')
                    ->join('packages', 'orders_package.package_id', '=', 'packages.id')
                    ->where('users.name', 'like', '%' . $data . '%')
                    ->orWhere('users.login', 'like', '%' . $data . '%')
                    ->paginate(9);
            }

            flash(__('admin_alert.userfound'))->success();
            return view('admin.packages.orders', compact('orderpackages'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.usernotfound'))->error();
            return redirect()->back();
        }
    }

    public function getDateOrdersCorporate(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $orderpackages = OrderPackage::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->get();

        return view('admin.packages.ordersCorporate', compact('orderpackages'));
    }

    public function getDateOrders(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $orderpackages = OrderPackage::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('admin.packages.orders', compact('orderpackages'));
    }

    public function orderfilter($parameter)
    {
        try {
            $packageSearch = OrderPackage::orderBy('created_at', 'DESC');

            //Filters
            switch ($parameter) {
                case 'paid':
                    $packageSearch->where('payment_status', 1);
                    break;
                case 'send':
                    $packageSearch->where('payment_status', 0);
                    break;
                case 'canceled':
                    $packageSearch->where('payment_status', 2);
                    break;
            }

            $orderpackages = $packageSearch->paginate(9);
            // dd($orderpackages);
            return view('admin.packages.orders', compact('orderpackages'));
            // return redirect()->back()->with(['orderpackages' => $orderpackages]);
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->back();
        }
    }



    public function invoicePackage($id)
    {
        $ecomm_order = OrderPackage::where('id', $id)->first();

        if (!isset($ecomm_order)) {
            abort(404);
        }

        $client_corporate = null;

        $client = User::where('id', $ecomm_order->user_id)->first();

        $cell = $client->cell ?? '';
        $client_address = $client->address1;
        $client_postcode = $client->postcode;
        $client_name = $client->name . ' ' . ($client->last_name ?? '');

        if ($client->complement && isset($client->complement)) {
            $client_address .= " " . $client->complement . " - " . $client->number_residence;
        } else {
            $client_address .= " - " . $client->number_residence;
        }

        if ($client->city && isset($client->city)) {
            $client_postcode .= " - " . $client->city;
        }

        $metodo_pay = null;

        if (isset($ecomm_order->payment_method)) {
            $metodo_pay = $this->metodosHabilitadosComgate($ecomm_order->payment_method);
            if ($metodo_pay == false) {
                $metodo_pay = $ecomm_order->payment_method;
            }
        } else if (isset($ecomm_order->wallet)) {
            $metodo_pay = 'BTC';
        } else {
            $metodo_pay = 'Credit Card';
        }

        $data = [
            "client_corporate" => $client_corporate,
            "client_id" => $client->id,
            "client_name" => $client_name,
            "client_email" => $client->email,
            "client_cell" => $cell,
            "client_address" => $client_address,
            "client_postcode" => substr($client_postcode, 0, 20),
            "client_country" => $client->country,
            "metodo_pay" => $metodo_pay,
            "order" => $id,
            'paid_data' => $ecomm_order->updated_at,
            'total_order' => $ecomm_order->price,
            'total_vat' => 0,
            'total_shipping' => 0,
        ];

        $data['address'] = $client->address ?? $client->address1 ?? '';
        $data['zip'] = $client->zip ?? $client->postcode ?? '';
        $data['neighborhood'] = $client->neighborhood ?? $client->area_residence ?? $client->city ?? '';
        $data['number'] = $client->number ?? $client->number_residence ?? '';
        $data['complement'] = isset($client->complement) ? $client->complement : '';
        $data['country'] = $client->country;

        $total_price_product = 0;
        $qv = 0;
        $pesoTotal = 0;

        $package = Package::where('id', $ecomm_order->package_id)->first();

        if (isset($ecomm_order->percent_vat)) {
            $porcentVat = $ecomm_order->percent_vat;
            if ($porcentVat > 0) {
                $fatorMultiplicativo = 1 + ($porcentVat / 100);
                $valorOriginal = $ecomm_order->price / $fatorMultiplicativo;
            } else {
                $valorOriginal = $ecomm_order->price;
            }
        } else {
            $porcentVat = $this->trazerPorcentProduto($ecomm_order->package_id, $data['country']);
            $fatorMultiplicativo = 1 + ($porcentVat / 100);
            $valorOriginal = $ecomm_order->price / $fatorMultiplicativo;
        }

        $data['products'][$ecomm_order->id] = [
            'name' => $ecomm_order->reference,
            'amount' => $ecomm_order->amount,
            'unit' => number_format($valorOriginal, 2, ",", "."),
            'total' => number_format($ecomm_order->price, 2, ",", "."),
            'porcentVat' => $porcentVat,
            'vat' => number_format($ecomm_order->price - $valorOriginal, 2, ",", "."),
        ];

        return $data;
    }

    public function trazerPorcentProduto($id_p, $pais)
    {
        $price_shipping = ShippingPrice::where('country_code', $pais)->orWhere('country', $pais)->first();
        $p = $price_shipping->country;
        $taxValue = TaxPackage::where('package_id', $id_p)->where('country', $p)->first();
        return $taxValue->value;
    }

    public function metodosHabilitadosComgate($metodo)
    {
        $client = new \GuzzleHttp\Client();

        $url = 'https://payments.comgate.cz/v1.0/methods';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            "type" => "json",
            'lang' => 'en',
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        $response = $client->post($url, [
            'form_params' => $data,
            'headers' => $headers,

        ]);

        $statusCode = $response->getStatusCode();
        $metodos = $response->getBody()->getContents();

        $metodos = json_decode($metodos)->methods;
        foreach ($metodos as $mt) {
            if ($metodo == $mt->id) {
                return $mt->name;
            }
        }
        return false;
    }

    public function Fakturoid($order)
    {
        $SendedFakturoid = InvoicesFakturoid::where('number_order', $order)->first();

        if (isset($SendedFakturoid)) {
            return redirect()->route('admin.packages.orderPackages')->withErrors(['SendFakturoid' => 'Invoice already sent.']);
        }

        $data = $this->invoicePackage($order);
        // dd($data);
        $countryCodeClient = ShippingPrice::where('country_code', $data['client_country'])->orWhere('country', $data['client_country'])->first();

        $f = new FakturoidClient('intermodels', 'juraj@lifeprosper.eu', 'd2f384a3e232c5fbeb28c8e2a49435573561905f', 'PHPlib <juraj@lifeprosper.eu>');
        // dd($f);

        $existisSubject = SubjectsFakturoid::where('user_id', $data['client_id'])->first();

        if (isset($existisSubject)) {
            $subject_id = $existisSubject->subject_id;

            try {
                $updateSubject = $f->updateSubject(
                    $subject_id,
                    array(
                        'street' => $data['client_address'],
                        'country' => $countryCodeClient->country_code,
                        'zip' => $data['client_postcode']
                    )
                );
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else {
            try {
                $response = $f->createSubject(
                    array(
                        'name' => $data['client_name'],
                        'email' => $data['client_email'],
                        'street' => $data['client_address'],
                        'country' => $countryCodeClient->country_code,
                        'zip' => $data['client_postcode']
                    )
                );
                $subject = $response->getBody();
                $subject_id = $subject->id;

                $saveSubject = new SubjectsFakturoid;
                $saveSubject->user_id = $data['client_id'];
                $saveSubject->subject_id = $subject_id;
                $saveSubject->save();

            } catch (\Throwable $th) {
                return redirect()->route('admin.packages.orderPackages')->withErrors(['Fakturoid' => 'Error in send to Fakturoid.']);
            }

        }

        $lines = array();
        $subtotal = 0;

        foreach ($data['products'] as $item) {
            array_push(
                $lines,
                array(
                    'name' => $item['name'],
                    'quantity' => $item['amount'],
                    'unit_price' => floatval(str_replace(',', '.', $item['unit'])),
                    'vat_rate' => floatval(str_replace(',', '.', $item['porcentVat'])),
                    'vat' => floatval(str_replace(',', '.', $item['vat'])),
                    'price' => floatval(str_replace(',', '.', $item['total']))
                )
            );

            $subtotal += floatval(str_replace(',', '.', $item['unit']));
        }


        try {

            $metodo_pagamento = '';

            if ($data['metodo_pay'] == 'Credit card' || $data['metodo_pay'] == 'CARD_CZ_CSOB_2') {
                $metodo_pagamento = 'card';
            } else if (substr($data['metodo_pay'], 0, 4) === "BANK" || $data['metodo_pay'] == 'Other banks') {
                $metodo_pagamento = 'bank';
            } else {
                $metodo_pagamento = 'cash';
            }

            // dd($lines);


            $response = $f->createInvoice(
                array(
                    'subject_id' => $subject_id,
                    'payment_method' => $metodo_pagamento,
                    'order_number' => $order,
                    'client_name' => $data['client_name'],
                    'client_street' => $data['client_address'],
                    'client_country' => $countryCodeClient->country_code,
                    'client_zip' => $data['client_postcode'],
                    'currency' => "EUR",
                    'language' => "en",
                    'vat_price_mode' => 'without_vat',
                    'total' => $data['total_order'],
                    'subtotal' => $subtotal,
                    'lines' => $lines
                )
            );
            $invoice = $response->getBody();

            $newOrderFakturoid = new InvoicesFakturoid;
            $newOrderFakturoid->user_id = $data['client_id'];
            $newOrderFakturoid->number_order = $order;
            $newOrderFakturoid->invoice_id = $invoice->id;
            $newOrderFakturoid->save();

            return redirect()->route('admin.packages.orderPackages')->withErrors(['SendFakturoid' => 'Invoice Sended.']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('admin.packages.orderPackages')->withErrors(['Fakturoid' => 'Error in send to Fakturoid.']);
        }

        // $f->fireInvoice($invoice->id, 'pay');
    }

    public function orderPackagesCorporate()
    {
        $orderpackages = OrderPackage::where('payment_method', 'Admin')->orWhere('order_corporate', 1)->orderBy('id', 'DESC')->paginate(9);

        return view('admin.packages.ordersCorporate', compact('orderpackages'));
    }

    public function orderfilterCorporate($parameter)
    {
        try {
            $packageSearch = OrderPackage::orderBy('id', 'DESC');

            //Filters
            switch ($parameter) {
                case 'paid':
                    $packageSearch->where('payment_status', 1);
                    break;
                case 'send':
                    $packageSearch->where('payment_status', 0);
                    break;
                case 'canceled':
                    $packageSearch->where('payment_status', 2);
                    break;
            }

            $orderpackages = $packageSearch->get();
            // dd($orderpackages);
            return view('admin.packages.ordersCorporate', compact('orderpackages'));
            // return redirect()->back()->with(['orderpackages' => $orderpackages]);
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->back();
        }
    }

    public function orderFilterName(Request $request)
    {
        if (isset($request->search)) {
            # code...
            $users = User::where('email', $request->search)
                ->orWhere('name', $request->search)
                ->orWhere('login', $request->search)->pluck('id');

            if (count($users) > 0) {
                $orderpackages = OrderPackage::whereIn('user_id', $users)
                    ->orderBy('id', 'DESC')->paginate(9);

                return view('admin.packages.orders', compact('orderpackages'));
            } else {
                return $this->orderPackages();
            }
        } else {
            return $this->orderPackages();
        }
    }
}