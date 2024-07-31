<?php

namespace App\Http\Controllers;

use App\Models\AddressSecondary;
use App\Models\ChosenPickup;
use App\Models\EditSmarthipping;
use App\Models\InvoicesFakturoid;
use App\Models\PickupPoint;
use App\Models\PriceCoin;
use App\Models\ProductByCountry;
use App\Models\Stock;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;
use App\Models\EcommOrders;
use App\Models\Package;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\ShippingPrice;
use App\Models\TaxPackage;
use Illuminate\Http\Request;
use App\Models\OrderPackage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Fakturoid\Client as FakturoidClient;
use Illuminate\Support\Facades\Session;


class PackageController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $adesao = !$user->getAdessao($user->id); //verifica se ja tem adesão para liberar os outros produtos
        //$adesao = true;

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        // $packages = Package::orderBy('id', 'ASC')->where('activated', 1)->whereIn('id', array(18, 19, 20))->paginate(9);
        $packages = Package::orderBy('id', 'ASC')->where('activated', 1)->paginate(9);
        if ($user->contact_id == NULL) {
            // $complete_registration = "Please complete your registration to purchase:<br>";
            // $array_att = array('last_name' => 'Last Name', 'address1' => 'Address 1', 'address2' => 'Address 2', 'postcode' => 'Postcode', 'state' => 'State', 'wallet' => 'Wallet');
            // foreach ($user->getAttributes() as $key => $value) {
            //     if ($value == NULL && array_search($key, array('last_name', 'address1', 'address2', 'postcode', 'state', 'wallet'))) {
            //         $complete_registration .= "&nbsp;&nbsp;&bull;" . $array_att[$key] . "<br>";
            //     }
            // }
            // $complete_registration .= "<span style='color:#000'><a href='/users/users'>Click here to go to Your Info Page</a></span><br>";
            // flash($complete_registration)->error();
            return view('package.packages', compact('packages', 'adesao', 'user', 'countPackages'));
        } else {
            return view('package.packages', compact('packages', 'adesao', 'user', 'countPackages'));
        }
    }

    public function packagesActivator()
    {
        $user = User::find(Auth::id());
        $adesao = !$user->getAdessao($user->id) >= 1; //verifica se ja tem adesão para liberar os outros produtos
        //$adesao = true;
        $packages = Package::orderBy('id', 'DESC')->where('activated', 1)->where('type', 'activator')->paginate(9);
        if ($user->contact_id == NULL) {
            $complete_registration = "Please complete your registration to purchase:<br>";
            $array_att = array('last_name' => 'Last Name', 'address1' => 'Address 1', 'address2' => 'Address 2', 'postcode' => 'Postcode', 'state' => 'State', 'wallet' => 'Wallet');
            foreach ($user->getAttributes() as $key => $value) {
                if ($value == NULL && array_search($key, array('last_name', 'address1', 'address2', 'postcode', 'state', 'wallet'))) {
                    $complete_registration .= "&nbsp;&nbsp;&bull;" . $array_att[$key] . "<br>";
                }
            }
            flash($complete_registration)->error();
        }

        return view('package.packages', compact('packages', 'adesao', 'user'));
    }

    public function detail($packageid)
    {

        $package = Package::where('id', '=', $packageid)->first();
        $user = User::find(Auth::id());
        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        if ($user->country == $countryUser->country_code) {
            $user->country = $countryUser->country;
            $user->save();
        }
        $vatPercent = TaxPackage::where('package_id', $package->id)->where('country', $countryUser->country)->first();
        if (isset($vatPercent) && $user->pay_vat == 1) {
            $percentTax = $vatPercent->value;
            if ($percentTax > 0) {
                $vatValue = $package->price * (floatval($percentTax) / 100);
            } else {
                $vatValue = 0;
            }
        } else {
            $vatValue = 0;
        }
        $total_value = floatval(($vatValue + $package->price));
        // dd($total_value);

        return view('package.package', compact('package', 'countPackages', 'vatValue'));
    }

    public function package()
    {
        $id_user = Auth::id();


        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        $orderIds = \DB::table('ecomm_orders')
            ->select(\DB::raw('MIN(id) as id'))
            ->where('id_user', $id_user)
            ->groupBy('number_order')
            ->pluck('id');

        $orderProducts = EcommOrders::whereIn('id', $orderIds)
            ->orderBy('created_at', 'DESC')
            ->paginate(9);

        // $orderProducts = EcommOrders::orderBy('id', 'DESC')
        // ->where('id_user', $id_user)

        foreach ($orderProducts as $item) {
            $product = Product::where('id', $item->id_product)->first();
            $payment = PaymentOrderEcomm::where('id', $item->id_payment_order)->first();

            if (isset($payment)) {
                $item['payment_status'] = strtolower($payment->status);
            } else {
                $item['payment_status'] = '';
            }

            $item['total_price'] = $payment->total_price ?? '';
            $item['name_product'] = $product->name ?? '';
            $item['img_product'] = $product->img_1 ?? '';
        }

        $orderPackages = OrderPackage::orderBy('id', 'DESC')
            ->where('hide', false)
            ->where('user_id', $id_user)->paginate(9);

        return view('userpackageinfo', compact('orderProducts', 'countPackages', 'orderPackages'));
    }

    public function ecommOrdersDetail($order)
    {
        $orderDetails = EcommOrders::where('number_order', $order)->get();
        $payment = PaymentOrderEcomm::where('id', $orderDetails[0]->id_payment_order)->first();
        $orderDetails[0]['payment'] = $payment->status;

        foreach ($orderDetails as $value) {
            $product = Product::where('id', $value->id_product)->first();
            $value['name_product'] = $product->name;
            $value['image_product'] = $product->img_1;
        }

        $metodos = $this->getMethodPaymentComgate();

        return view('package.ecommOrdersDetail', compact('orderDetails', 'metodos'));
    }

    public function ecommOrdersDetailEdit($order)
    {

        $edit = EditSmarthipping::where('id_user', Auth::id())->where('number_order', $order)->get();

        if (count($edit) > 0) {
            $products = Product::where('activated', 1)->get();
            $ids = [];
            $orderDetails = [];

            foreach ($edit as $value) {
                $product = Product::where('id', $value->id_product)->first();
                $ids[$value->id_product] = $value->amount;
            }

            return view('package.editsmartshipping', compact('orderDetails', 'products', 'ids', 'order'));
        } else {

            $orderDetails = EcommOrders::where('number_order', $order)->get();
            $payment = PaymentOrderEcomm::where('id', $orderDetails[0]->id_payment_order)->first();
            $orderDetails[0]['payment'] = $payment->status;
            $ids = [];
            $products = Product::where('activated', 1)->get();

            foreach ($orderDetails as $value) {
                $product = Product::where('id', $value->id_product)->first();
                $value['name_product'] = $product->name;
                $value['image_product'] = $product->img_1;
                $ids[$value->id_product] = $value->amount;
            }

            return view('package.editsmartshipping', compact('orderDetails', 'products', 'ids', 'order'));
        }
    }

    public function ecommOrdersDetailEditPost(Request $request)
    {


        $count_form = 0;
        $array_impts = array();

        $ip_order = $_SERVER['REMOTE_ADDR'];
        // $ip_order = '194.156.125.61';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];

        $products = Product::where('activated', 1)->get();
        $user = User::where('id', Auth::id())->first();
        $countryIp = $user->country;
        $percentShippingVat = 0;
        $typeWeight = '';

        $lastProdId = Product::where('activated', 1)->orderBy('id', 'desc')->first()->id;

        $ecommOrder = EcommOrders::where('number_order', $request->order)->first();
        $shippingHome = null;

        $mt = $ecommOrder->method_shipping;


        if ($mt == 'home1') {
            $country = $user->country;
            $countryIp = $user->country;
            $shippingHome = ShippingPrice::where('country', $country)->first();
        } else if ($mt == 'home2') {
            $shippingsec = AddressSecondary::where('id', $user->id)->where('backoffice', 1)->first();
            $shippingHome = ShippingPrice::where('country', $shippingsec->country)->first();
            $countryIp = $shippingHome->country;
        } else {
            $cp = ChosenPickup::where('number_order', $request->order)->first();
            $shippingHome = PickupPoint::where('country_code', $cp->country)->first();
            $countryIp = $shippingHome->country;
        }

        $taxs = [];

        for ($i = 0; $i <= $lastProdId; $i++) {

            $product = 'product_' . $i;
            $amount = 'amount_' . $i;
            $price = 'price_' . $i;

            if (!empty($request->$amount)) {
                $tax = Tax::where('product_id', $request->$product)->where('country', $countryIp)->first();

                if (isset($tax)) {
                    $product_vat = $tax->value;
                    if ($tax->value > $percentShippingVat) {
                        $percentShippingVat = $tax->value;
                        $taxs[$i] = $product_vat;
                    }
                } else {
                    $product_vat = 0.00;
                }

                $subtotal = $request->$price * $request->$amount;
                if ($product_vat > 0) {
                    $vat_value = $subtotal * $product_vat / 100;
                } else {
                    $vat_value = 0;
                }


                $array_push = [
                    'product' => $request->$product,
                    'amount' => $request->$amount,
                    'price' => $request->$price,
                    'subtotal' => $subtotal + $vat_value,
                    'vat' => $vat_value,
                    'tax_percent' => $tax->value ?? 0,
                ];

                // array_push($array_impts, $array_push);
                $array_impts[$request->$product] = $array_push;
            }
        }
        // dd($ecommOrder);

        $total = 0;

        $totalWeight = 0;

        foreach ($array_impts as $inputs) {
            // dd($inputs);
            $total = $total + $inputs['subtotal'];
            $prod = Product::where('id', $inputs["product"])->first();
            if (isset($prod->weight)) {
                $totalWeight += $prod->weight * $inputs["amount"];
            }

        }

        if ($totalWeight <= 2) {
            $priceShippingHome = $shippingHome->kg2;
            $typeWeight = 'kg2';
        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $shippingHome->kg5;
            $typeWeight = 'kg5';
        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $shippingHome->kg10;
            $typeWeight = 'kg10';
        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $shippingHome->kg20;
            $typeWeight = 'kg20';
        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $shippingHome->kg31_5;
            $typeWeight = 'kg31_5';
        }

        $order = $request->order;

        if ($percentShippingVat > 0) {
            $priceShippingHome += $priceShippingHome * ($percentShippingVat / 100);
        }

        // dd($percentShippingVat);

        $priceShippingHome = number_format($priceShippingHome, 2, '.', '');

        $frete = $priceShippingHome;
        $total += $priceShippingHome;

        return view('package.editsmartshipping', compact('products', 'array_impts', 'total', 'frete', 'order', 'percentShippingVat'));

    }

    public function getMethodPaymentComgate()
    {

        return [];
    }

    public function hide($id)
    {
        try {
            $orderpackage = OrderPackage::find($id);
            $orderpackage->hide = true;
            $orderpackage->update();
            flash(__('package.your_order_has_been_hidden'))->success();
            return redirect()->back();
        } catch (Exception $e) {
            flash(__('package.unable_to_hide_your_order'))->error();
            return redirect()->back();
        }
    }

    public function BuyPackage(Request $request, $id, $method)
    {
        $user = User::find(Auth::id());

        $package = Package::find($id);
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $vatPercent = TaxPackage::where('package_id', $package->id)->where('country', $countryUser->country)->first();
        $percentVat = 0;

        if (isset($vatPercent) && $user->pay_vat == 1) {
            if ($vatPercent->value > 0) {
                $vatValue = $package->price * (floatval($vatPercent->value) / 100);
                $percentVat = $vatPercent->value;
            } else {
                $percentVat = 0;
                $vatValue = 0;
            }
        } else {
            $percentVat = 0;
            $vatValue = 0;
        }

        $total_value = $vatValue + $package->price;
        $total_value = number_format($total_value, 2, '.', '');

        $price = floatval(str_replace(',', '.', $total_value));

        $numeroString = strval($price);
        $posicaoPonto = strpos($numeroString, '.');

        $newPrice = $price;

        if ($posicaoPonto) {
            $quant = strlen($numeroString) - $posicaoPonto - 1;
            if ($quant === 1) {
                $newPrice = $newPrice . '0';
            }
        } else {
            $newPrice = $newPrice . '00';
        }

        // dd($newPrice);
        // dd($newPrice);
        if ($method == 'Admin') {
            // dd($request);

            $packageOrder = $this->createOrder(Auth::id(), $package, '', '', null, null, $price, $method, $percentVat);

            $comgate = $this->payComgate($request, $newPrice, substr(str_replace(' ', '', $package->name), 0, 15), $method, $packageOrder->id);
            $codepayment = 'Admin';
            $invoiceid = 'Admin';

            $this->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, null);
            // dd($comgate);

            return redirect()->route('packages.packagelog');
        } else {

            if ($method == 'BTC' || $method == 'ETH') {

                $packageOrder = $this->createOrder(Auth::id(), $package, '', '', null, null, $price, $method, $percentVat);

                $crypto = $this->payCrypto(substr(str_replace(' ', '', $package->name), 0, 15), $price, $method);
                // dd($crypto);
                if (!isset($crypto)) {
                    $packageOrder->delete();
                    return redirect()->back();
                }
                $codepayment = $crypto->id;
                $invoiceid = $crypto->invoice_id;
                $wallet_OP = $crypto->address;
                // dd($crypto);

                $this->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, $wallet_OP);
                session()->put('redirect_buy', 'admin');
                return redirect()->away($crypto->url);
            } else {
                $packageOrder = $this->createOrder(Auth::id(), $package, '', '', null, null, $price, $method, $percentVat);

                $comgate = $this->payComgate($request, $newPrice, substr(str_replace(' ', '', $package->name), 0, 15), $method, $packageOrder->id);
                $codepayment = $comgate['transId'];
                $invoiceid = $comgate['transId'];

                $this->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, null);
                // dd($comgate);

                session()->put('redirect_buy', 'admin');
                return redirect()->away($comgate['redirect']);
            }
        }

        // return redirect()->back();

    }

    function editOrderPackage($id, $codepayment, $invoiceid, $wallet)
    {
        $package = OrderPackage::where('id', $id)->first();
        $package->transaction_code = $codepayment;
        $package->transaction_wallet = $invoiceid;
        if ($wallet) {
            $package->wallet = $wallet;
        }
        $package->save();
    }

    public function payCrypto($name, $price, $method = "btc")
    {
        // dd($name);
        $paymentConfig = [
            "api_url" => "https://crypto.binfinitybank.com/packages/wallets/notify",
            "email" => 'master@tec2u.com.br',
            "password" => "password",
        ];

        if (strtolower($method) == 'btc') {
            $method = 'BITCOIN';
        }

        $curl = curl_init();

        // $urlRedirect = $request->getScheme() . '://' . $request->getHost() . '/ecomm';
        // $url = $request->getScheme() . '://' . $request->getHost() . '/ecomm/finalize/notify';
        $url = "https://lifeprosper.eu/ecomm/finalize/notify";

        $priceDol = PriceCoin::where('name', 'eur')->first();
        $priceInDol = $price * $priceDol->one_in_usd;

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $paymentConfig['api_url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "login": "' . $paymentConfig['email'] . '",
                "password": "' . $paymentConfig['password'] . '",
                "id_order": "' . $name . '",
                "price": "' . $priceInDol . '",
                "coin": "' . $method . '",
                "notify_url" : "' . $url . '",
                "custom_data1" : null,
                "custom_data2" : "package"
                
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            )
            // "price_crypto": "' . $order->price_crypto . '",    
        );

        $raw = json_decode(curl_exec($curl));

        curl_close($curl);
        // dd($raw);
        if (isset($raw->merchant_id) && isset($raw->id)) {
            return (object) [
                "id" => $raw->id,
                "invoice_id" => $raw->merchant_id,
                "address" => $raw->wallet,
                "url" => "https://crypto.binfinitybank.com/invoice/" . $raw->id
            ];
        } else {
            return null;
        }
    }

    public function payComgate(Request $request, $newPrice, $name, $method, $package_id, $user = null)
    {
        $dominio = $request->getHost();
        if (strtolower($dominio) == 'lifeprosper.eu') {
            $test = 'false';
        } else {
            $test = 'false';
        }


        if (isset($user)) {
            $user_id = $user->id;
        } else {
            $user_id = Auth::id();
        }

        $url = '';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => str_replace('.', '', $newPrice),
            'curr' => 'EUR',
            'label' => "$name",
            'email' => User::find($user_id)->email,
            'refId' => "package $package_id",
            'method' => "$method",
            'prepareOnly' => 'true',
            'test' => "$test",
            'lang' => 'en',
        ];

        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        $response = $client->post($url, [
            'form_params' => $data,
            'headers' => $headers,

        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        parse_str($body, $responseData);

        return $responseData;
    }

    public function createOrder($id_user, $package, $payment, $invoiceid, $wallet, $subId, $price, $method, $percentVat)
    {
        $user = User::find($id_user);
        $package_id = $package->id;
        $reference = $package->name;


        // $user->orderPackage()->create([
        //     "reference" => $package->name,
        //     "payment_status" => 0,
        //     "transaction_code" => $payment,
        //     "package_id" => $package->id,
        //     "price" => $price,
        //     "amount" => 1,
        //     "transaction_wallet" => $invoiceid,
        //     "wallet" => $wallet,
        //     "subscription_id" => $subId,
        //     "payment_method" => $method
        // ]);

        $package = new OrderPackage;
        $package->user_id = $id_user;
        $package->reference = $reference;
        $package->payment_status = 0;
        $package->status = 0;
        $package->transaction_code = $payment;
        $package->package_id = $package_id;
        $package->price = $price;
        $package->amount = 1;
        $package->transaction_wallet = $invoiceid;
        $package->wallet = $wallet;
        $package->subscription_id = $subId;
        $package->percent_vat = $percentVat;
        $package->payment_method = $method;
        if ($method == 'Admin') {
            $package->order_corporate = 1;
        }
        $package->save();

        return $package;

    }

    public function metodosHabilitadosComgate($metodo)
    {
        $client = new \GuzzleHttp\Client();

        $url = '';

        $metodos = [];
        foreach ($metodos as $mt) {
            if ($metodo == $mt->id) {
                return $mt->name;
            }
        }
        return false;
    }

    public function getInvoiceInFakturoid($order)
    {
        $f = new FakturoidClient('intermodels', 'juraj@lifeprosper.eu', 'd2f384a3e232c5fbeb28c8e2a49435573561905f', 'PHPlib <juraj@lifeprosper.eu>');

        $existisOrder = OrderPackage::where('id', $order)->first();
        if (!isset($existisOrder)) {
            abort(404);
        }

        $invoiceFak = InvoicesFakturoid::where('number_order', $order)->first();
        if (!isset($invoiceFak)) {
            return false;
        }

        $invoiceId = $invoiceFak->invoice_id;
        $response = $f->getInvoicePdf($invoiceId);
        $data = $response->getBody();

        return $data;

    }


    public function invoicePackage($id)
    {
        $existsFakturoid = $this->getInvoiceInFakturoid($id);
        if ($existsFakturoid != false) {
            return response($existsFakturoid)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $id . '.pdf"');
        }

        $ecomm_order = OrderPackage::where('id', $id)->first();

        if (!isset($ecomm_order)) {
            abort(404);
        }

        $client_corporate = null;

        $client = User::where('id', $ecomm_order->user_id)->first();

        $cell = $client->cell ?? '';
        $client_address = $client->address1;
        $client_postcode = $client->postcode . ' ' . ($client->city ?? '');
        $client_name = $client->name . ' ' . ($client->last_name ?? '');

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
            "client_postcode" => $client_postcode,
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

        return view('pdf.invoicePackage', compact('data'));
    }

    public function invoicePackageXml($id)
    {
        $ecomm_order = OrderPackage::where('id', $id)->first();

        if (!isset($ecomm_order)) {
            abort(404);
        }

        $client_corporate = null;

        $client = User::where('id', $ecomm_order->user_id)->first();

        $cell = $client->cell ?? '';
        $client_address = $client->address1;
        $client_postcode = $client->postcode . ' ' . ($client->city ?? '');
        $client_name = $client->name . ' ' . ($client->last_name ?? '');

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
            "client_postcode" => $client_postcode,
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

        $data['products'][$ecomm_order->id] = [
            'name' => $ecomm_order->reference,
            'amount' => $ecomm_order->amount,
            'unit' => number_format($package->price, 2, ",", "."),
            'total' => number_format($ecomm_order->price, 2, ",", "."),
            'porcentVat' => $this->trazerPorcentProduto($ecomm_order->package_id, $data['country']),
            'vat' => number_format($ecomm_order->price - $package->price, 2, ",", "."),
        ];

        // return view('xml.file_xml', compact('data'));
        return view('pdf.invoiceXml', compact('data'));


        // $url = "https://www.stormware.cz/xml/schema/version_2/invoice.xsd";

        // $data = file_get_contents($url);
        // $xml  = simplexml_load_string($data);

        // dd($xml);

        // exit();

        // return response()->xml($data);

        // return view('xml.invoicePackage', compact('data'));
    }

    public function trazerPorcentProduto($id_p, $pais)
    {
        $price_shipping = ShippingPrice::where('country_code', $pais)->orWhere('country', $pais)->first();
        $p = $price_shipping->country;
        $taxValue = TaxPackage::where('package_id', $id_p)->where('country', $p)->first();
        return $taxValue->value;
    }

    public function ecommOrdersDetailEditPostSave(Request $request)
    {


        $count_form = 0;
        $array_impts = array();

        $ip_order = $_SERVER['REMOTE_ADDR'];
        // $ip_order = '194.156.125.61';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];

        $products = Product::where('activated', 1)->get();
        $user = User::where('id', Auth::id())->first();
        $countryIp = $user->country;
        $percentShippingVat = 0;
        $lastProdId = Product::where('activated', 1)->orderBy('id', 'desc')->first()->id;
        $typeWeight = '';

        $ecommOrder = EcommOrders::where('number_order', $request->order)->first();
        $shippingHome = null;

        $mt = $ecommOrder->method_shipping;


        if ($mt == 'home1') {
            $country = $user->country;
            $countryIp = $user->country;
            $shippingHome = ShippingPrice::where('country', $country)->first();
        } else if ($mt == 'home2') {
            $shippingsec = AddressSecondary::where('id', $user->id)->where('backoffice', 1)->first();
            $shippingHome = ShippingPrice::where('country', $shippingsec->country)->first();
            $countryIp = $shippingHome->country;
        } else {
            $cp = ChosenPickup::where('number_order', $request->order)->first();
            $shippingHome = PickupPoint::where('country_code', $cp->country)->first();
            $countryIp = $shippingHome->country;
        }

        $taxs = [];

        for ($i = 0; $i <= $lastProdId; $i++) {

            $product = 'product_' . $i;
            $amount = 'amount_' . $i;
            $price = 'price_' . $i;

            if (!empty($request->$amount)) {
                $tax = Tax::where('product_id', $request->$product)->where('country', $countryIp)->first();

                if (isset($tax)) {
                    $product_vat = $tax->value;
                    if ($tax->value > $percentShippingVat) {
                        $percentShippingVat = $tax->value;
                        $taxs[$i] = $product_vat;
                    }
                } else {
                    $product_vat = 0.00;
                }

                $subtotal = $request->$price * $request->$amount;
                if ($product_vat > 0) {
                    $vat_value = $subtotal * $product_vat / 100;
                } else {
                    $vat_value = 0;
                }


                $array_push = [
                    'product' => $request->$product,
                    'amount' => $request->$amount,
                    'price' => $request->$price,
                    'subtotal' => $subtotal + $vat_value,
                    'vat' => $vat_value,
                    'tax_percent' => $tax->value ?? 0,
                ];

                // array_push($array_impts, $array_push);
                $array_impts[$request->$product] = $array_push;
            }
        }
        // dd($ecommOrder);

        $total = 0;

        $totalWeight = 0;

        foreach ($array_impts as $inputs) {
            // dd($inputs);
            $total = $total + $inputs['subtotal'];
            $prod = Product::where('id', $inputs["product"])->first();
            if (isset($prod->weight)) {
                $totalWeight += $prod->weight * $inputs["amount"];
            }

        }

        if ($totalWeight <= 2) {
            $priceShippingHome = $shippingHome->kg2;
            $typeWeight = 'kg2';
        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $shippingHome->kg5;
            $typeWeight = 'kg5';
        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $shippingHome->kg10;
            $typeWeight = 'kg10';
        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $shippingHome->kg20;
            $typeWeight = 'kg20';
        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $shippingHome->kg31_5;
            $typeWeight = 'kg31_5';
        }

        $order = $request->order;

        if ($percentShippingVat > 0) {
            $priceShippingHome += $priceShippingHome * ($percentShippingVat / 100);
        }

        // dd($percentShippingVat);

        $priceShippingHome = number_format($priceShippingHome, 2, '.', '');

        $frete = $priceShippingHome;
        $total += $priceShippingHome;


        $idsEdit = [];

        foreach ($array_impts as $value) {
            array_push($idsEdit, $value['product']);

            if ($value['amount'] < 1) {
                $exists = EditSmarthipping::where('id_product', $value['product'])
                    ->where('id_user', Auth::id())
                    ->where('number_order', $order)
                    ->first();

                if (isset($exists)) {
                    $exists->delete();
                }
                continue;
            }

            $pro = Product::where('id', $value['product'])->first();

            $exists = EditSmarthipping::where('id_product', $value['product'])
                ->where('id_user', Auth::id())
                ->where('number_order', $order)
                ->first();

            if (isset($exists)) {
                $nEdit = $exists;
                $nEdit->amount = $value['amount'];
                $nEdit->total = $value['amount'] * $value['price'];
                $nEdit->total_vat = $value['vat'];
                $nEdit->total_shipping = $frete;
                if ($pro->qv > 0) {
                    $nEdit->qv = $value['amount'] * $pro->qv;
                } else {
                    $nEdit->qv = 0;
                }
                if ($pro->cv > 0) {
                    $nEdit->cv = $value['amount'] * $pro->cv;
                } else {
                    $nEdit->cv = 0;
                }
                $nEdit->client_backoffice = $ecommOrder->client_backoffice;
                $nEdit->vat_product_percentage = $value['tax_percent'];
                $nEdit->vat_shipping_percentage = $percentShippingVat;
                $nEdit->save();
            } else {
                $nEdit = new EditSmarthipping;
                $nEdit->number_order = $order;
                $nEdit->id_user = Auth::id();
                $nEdit->id_product = $value['product'];
                $nEdit->amount = $value['amount'];
                $nEdit->total = $value['amount'] * $value['price'];
                $nEdit->total_vat = $value['vat'];
                $nEdit->total_shipping = $frete;
                if ($pro->qv > 0) {
                    $nEdit->qv = $value['amount'] * $pro->qv;
                } else {
                    $nEdit->qv = 0;
                }
                if ($pro->cv > 0) {
                    $nEdit->cv = $value['amount'] * $pro->cv;
                } else {
                    $nEdit->cv = 0;
                }
                $nEdit->client_backoffice = $ecommOrder->client_backoffice;
                $nEdit->vat_product_percentage = $value['tax_percent'];
                $nEdit->vat_shipping_percentage = $percentShippingVat;
                $nEdit->save();
            }
        }


        $allEdit = EditSmarthipping::where('number_order', $order)
            ->where('id_user', Auth::id())
            ->whereNotIn('id_product', $idsEdit)
            ->delete();

        session()->flash('success', 'Saved successfully!');

        return view('package.editsmartshipping', compact('products', 'array_impts', 'total', 'frete', 'order', 'percentShippingVat'));

    }

    public function newProcessPackage1()
    {
        $packages = Package::where('activated', 1)->get();
        return view('package.newProcess', compact('packages'));
    }

    public function newProcessPackage2($id)
    {


        $package = Package::where('id', '=', $id)->first();
        $user = User::find(Auth::id());
        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        if ($user->country == $countryUser->country_code) {
            $user->country = $countryUser->country;
            $user->save();
        }
        $vatPercent = TaxPackage::where('package_id', $package->id)->where('country', $countryUser->country)->first();
        if (isset($vatPercent) && $user->pay_vat == 1) {
            $percentTax = $vatPercent->value;
            if ($percentTax > 0) {
                $vatValue = $package->price * (floatval($percentTax) / 100);
            } else {
                $vatValue = 0;
            }
        } else {
            $vatValue = 0;
        }
        $total_value = floatval(($vatValue + $package->price));

        return view('package.newProcess2', compact('package', 'countPackages', 'vatValue'));
    }

    public function newProcessPackage3($id)
    {
        $package = Package::where('id', '=', $id)->first();
        $user = User::find(Auth::id());
        $id_user = Auth::id();

        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        if ($user->country == $countryUser->country_code) {
            $user->country = $countryUser->country;
            $user->save();
        }
        $percentTax = 0;
        $vatPercent = TaxPackage::where('package_id', $package->id)->where('country', $countryUser->country)->first();
        if (isset($vatPercent) && $user->pay_vat == 1) {
            $percentTax = $vatPercent->value;
            if ($percentTax > 0) {
                $vatValue = $package->price * (floatval($percentTax) / 100);
            } else {
                $vatValue = 0;
            }
        } else {
            $vatValue = 0;
        }
        $total_value = floatval(($vatValue + $package->price));

        Session::put('package_choosed', [
            "id" => $package->id,
            "name" => $package->name,
            "img" => $package->img,
            "price" => $package->price,
            "vat" => $vatValue,
            "vat_percent" => $percentTax,
            "total" => number_format($total_value, 2, '.', ''),
        ]);

        // dd($vatValue, $total_value);

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
        return view('package.newProcess3', compact('products'));
    }

    public function newProcessPackage4(Request $request)
    {

        if (!Session::has('package_choosed')) {
            return $this->newProcessPackage1();
        }

        $user = User::find(Auth::id());
        $id_user = Auth::id();

        $user_cart = [];



        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'quant_product_') === 0) {
                // echo "Campo: $key, Valor: $value <br>";

                $productId = substr($key, strlen('quant_product_'));

                $prodc = Product::where('id', $productId)->first();

                if ($value > 0) {
                    # code...

                    $objeto = new \stdClass();
                    $objeto->id_product = $prodc->id;
                    $objeto->name = $prodc->name;
                    $objeto->img = $prodc->img_1;
                    $objeto->price = $prodc->backoffice_price;
                    $objeto->amount = $value;
                    $objeto->total = $prodc->backoffice_price * $value;

                    array_push($user_cart, $objeto);
                }

            }
        }


        // $user_cart = CartOrder::where('id_user', '=', $id_user)->get();

        if (
            $user->country == '' || $user->country == null ||
            $user->address1 == '' || $user->address1 == null ||
            $user->city == '' || $user->city == null ||
            $user->postcode == '' || $user->postcode == null ||
            // $user->state == '' || $user->state == null ||
            $user->number_residence == '' || $user->number_residence == null
            // $user->area_residence == '' || $user->area_residence == null

        ) {
            $complete_registration = "Complete your address in Info Page";
            return redirect()->back()->withErrors(['address' => $complete_registration]);
        }


        if (count($user_cart) < 1) {
            return redirect()->back();
        }


        $withoutVAT = 0;
        $priceShippingHome = 0;
        $priceShippingPickup = null;
        $subtotal = 0;
        $total_VAT = 0;
        $totalWeight = 0;

        $qv = 0;
        $cv = 0;
        $score = 0.5;

        $shippingHome = ShippingPrice::where('country', $user->country)->first();
        if (!isset($shippingHome)) {
            $shippingHome = ShippingPrice::where('country_code', $user->country)->first();
            if (!isset($shippingHome)) {
                return redirect()->back();
            }
        }
        $shippingPickup = PickupPoint::where('country', $user->country)->first();


        foreach ($user_cart as $product_item) {
            $subtotal = $subtotal + $product_item->total;
        }

        $count_itens = count($user_cart);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);
        $total_tax_add = 0;
        $tax1add = 0;
        $taxadd = 0;
        $idSprodutos = [];

        foreach ($user_cart as $order) {
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $user->country)->first();
            $findProduct = Product::where('id', $order->id_product)->first();
            array_push($idSprodutos, $order->id_product);
            $order->product_id = $findProduct->id;
            $weightProduct = $findProduct;

            if (isset($findProduct->qv)) {
                if ($findProduct->qv > 0) {
                    $qv += $findProduct->qv * $order->amount;
                }
            }

            if (isset($findProduct->cv)) {
                if ($findProduct->cv > 0) {
                    $cv += $findProduct->cv * $order->amount;
                }
            }

            $weightProduct = $weightProduct->weight * $order->amount;

            $totalWeight += $weightProduct;

            if (isset($taxValue)) {
                $tax = $taxValue->value;

                if ($user->pay_vat == 0) {
                    $tax = 0;
                }

                if ($tax > 0) {

                    $subtotal += (($tax / 100) * $order->price) * $order->amount;

                    $total_VAT += (($tax / 100) * $order->price) * $order->amount;

                    $priceTax = (($tax / 100) * $order->price);
                } else {
                    $priceTax = 0;
                }


                $order->priceTax = $priceTax;
            } else {
                $order->priceTax = 0;
            }

        }

        $paisesAceitos = ShippingPrice::all();
        $todosVats = [];

        foreach ($paisesAceitos as $vat) {
            $tt = 0;
            foreach ($user_cart as $order) {
                $taxVv = Tax::where('product_id', $order->id_product)->where('country', $vat->country)->first();

                if (isset($taxVv) && $taxVv->value > 0 && $user->pay_vat == 1) {
                    $tt += number_format(($taxVv->value / 100) * $order->price, 2, '.', '') * $order->amount;
                }

            }
            $todosVats[$vat->country] = $tt;
        }


        $typeWeight = '';

        if ($totalWeight <= 2) {
            $priceShippingHome = $shippingHome->kg2;
            $typeWeight = 'kg2';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg2;

        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $shippingHome->kg5;
            $typeWeight = 'kg5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg5;

        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $shippingHome->kg10;
            $typeWeight = 'kg10';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg10;

        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $shippingHome->kg20;
            $typeWeight = 'kg20';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg20;

        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $shippingHome->kg31_5;
            $typeWeight = 'kg31_5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg31_5;
        }

        // $idSprodutos = array_values($idSprodutos);

        $MaxtaxValuesByCountry = Tax::selectRaw('country, MAX(value)/100 as max_value')
            ->whereIn('product_id', array_values($idSprodutos))
            ->groupBy('country')
            ->get()
            ->pluck('max_value', 'country')
            ->toArray();

        // dd($MaxtaxValuesByCountry);

        // $taxValue = Tax::where('country', $user->country)->max('value');
        $paiss = $user->country;
        $taxValue = $MaxtaxValuesByCountry["$paiss"];

        if (isset($taxValue)) {
            if ($priceShippingHome > 0) {
                if ($taxValue > 0 && $user->pay_vat == 1) {

                    $total_tax_add += floatval($priceShippingHome) * floatval($taxValue);

                }
            }
        }

        $total_tax_add = number_format($total_tax_add, 2, '.', '');

        $priceShippingHome += $total_tax_add;

        $metodos = [];

        $countryes = ShippingPrice::orderBy('country', 'ASC')->get();
        $shippingPickup = PickupPoint::all();
        $todosFreteCasa = [];
        $todosFretePickup = [];

        foreach ($countryes as $value) {
            $value['priceShipping'] = $value->{"$typeWeight"};

            foreach ($MaxtaxValuesByCountry as $key => $valor) {
                if ($key == $value->country) {
                    if ($valor > 0 && $user->pay_vat == 1) {

                        $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};

                    } else {
                        $n = $value->{"$typeWeight"};
                    }

                    $n = number_format($n, 2, '.', '');
                    $todosFreteCasa[$key] = $n;
                }
            }
        }


        $allPickup = null;
        $temPickip = PickupPoint::where('country', $user->country)->first();
        if (isset($temPickip) || true) {
            $allPickup = PickupPoint::all();
            foreach ($allPickup as $value) {
                $value['priceShipping'] = $value->{"$typeWeight"};

                foreach ($MaxtaxValuesByCountry as $key => $valor) {
                    if ($key == $value->country) {
                        if ($valor > 0 && $user->pay_vat == 1) {

                            $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};

                        } else {
                            $n = $value->{"$typeWeight"};
                        }
                        $n = number_format($n, 2, '.', '');
                        $todosFretePickup[$value->country_code] = $n;
                    }
                }
            }
        } else {
            $allPickup = null;
            $shippingPickup = null;
            $priceShippingPickup = null;
        }

        // dd($todosFretePickup);

        $package_choosed = Session::get('package_choosed');
        $user_cart_package = null;
        $objeto = new \stdClass();
        $objeto->id = $package_choosed['id'];
        $objeto->name = $package_choosed['name'];
        $objeto->img = $package_choosed['img'];
        $objeto->price = $package_choosed['price'];
        $objeto->vat = $package_choosed['vat'];
        $objeto->amount = 1;
        $objeto->total = $package_choosed['total'];

        $package_choosed = $objeto;



        $withoutVAT = $subtotal - $total_VAT + $objeto->price;
        $semVat = $withoutVAT;

        $total_VAT += $objeto->vat;
        $subtotal += $objeto->total;

        $withoutVAT = number_format($withoutVAT, 2, ',', '.');
        $total_VAT = number_format($total_VAT, 2, ',', '.');
        // dd($subtotal);
        $btnSmart = null;

        Session::put(
            'products_choosed',
            $user_cart
        );

        return view('package.newProcess4', compact('package_choosed', 'btnSmart', 'todosFreteCasa', 'todosFretePickup', 'semVat', 'todosVats', 'MaxtaxValuesByCountry', 'cv', 'qv', 'allPickup', 'shippingPickup', 'countryes', 'user_cart', 'count_itens', 'subtotal', 'countPackages', 'total_VAT', 'metodos', 'priceShippingHome', 'priceShippingPickup', 'withoutVAT', 'user'));

    }

    public function newProcessPackage4GET(Request $request)
    {

        if (!Session::has('package_choosed')) {
            return $this->newProcessPackage1();
        }

        if (!Session::has('products_choosed')) {
            return $this->newProcessPackage1();
        }

        $user = User::find(Auth::id());
        $id_user = Auth::id();

        $user_cart = [];


        $user_cart = Session::get('products_choosed');

        // $user_cart = CartOrder::where('id_user', '=', $id_user)->get();

        if (
            $user->country == '' || $user->country == null ||
            $user->address1 == '' || $user->address1 == null ||
            $user->city == '' || $user->city == null ||
            $user->postcode == '' || $user->postcode == null ||
            // $user->state == '' || $user->state == null ||
            $user->number_residence == '' || $user->number_residence == null
            // $user->area_residence == '' || $user->area_residence == null

        ) {
            $complete_registration = "Complete your address in Info Page";
            return redirect()->back()->withErrors(['address' => $complete_registration]);
        }


        if (count($user_cart) < 1) {
            return redirect()->back();
        }


        $withoutVAT = 0;
        $priceShippingHome = 0;
        $priceShippingPickup = null;
        $subtotal = 0;
        $total_VAT = 0;
        $totalWeight = 0;

        $qv = 0;
        $cv = 0;
        $score = 0.5;

        $shippingHome = ShippingPrice::where('country', $user->country)->first();
        if (!isset($shippingHome)) {
            $shippingHome = ShippingPrice::where('country_code', $user->country)->first();
            if (!isset($shippingHome)) {
                return redirect()->back();
            }
        }
        $shippingPickup = PickupPoint::where('country', $user->country)->first();


        foreach ($user_cart as $product_item) {
            $subtotal = $subtotal + $product_item->total;
        }

        $count_itens = count($user_cart);

        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);
        $total_tax_add = 0;
        $tax1add = 0;
        $taxadd = 0;
        $idSprodutos = [];

        foreach ($user_cart as $order) {
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $user->country)->first();
            $findProduct = Product::where('id', $order->id_product)->first();
            array_push($idSprodutos, $order->id_product);
            $order->product_id = $findProduct->id;
            $weightProduct = $findProduct;

            if (isset($findProduct->qv)) {
                if ($findProduct->qv > 0) {
                    $qv += $findProduct->qv * $order->amount;
                }
            }

            if (isset($findProduct->cv)) {
                if ($findProduct->cv > 0) {
                    $cv += $findProduct->cv * $order->amount;
                }
            }

            $weightProduct = $weightProduct->weight * $order->amount;

            $totalWeight += $weightProduct;

            if (isset($taxValue)) {
                $tax = $taxValue->value;

                if ($user->pay_vat == 0) {
                    $tax = 0;
                }

                if ($tax > 0) {

                    $subtotal += (($tax / 100) * $order->price) * $order->amount;

                    $total_VAT += (($tax / 100) * $order->price) * $order->amount;

                    $priceTax = (($tax / 100) * $order->price);
                } else {
                    $priceTax = 0;
                }


                $order->priceTax = $priceTax;
            } else {
                $order->priceTax = 0;
            }

        }

        $paisesAceitos = ShippingPrice::all();
        $todosVats = [];

        foreach ($paisesAceitos as $vat) {
            $tt = 0;
            foreach ($user_cart as $order) {
                $taxVv = Tax::where('product_id', $order->id_product)->where('country', $vat->country)->first();

                if (isset($taxVv) && $taxVv->value > 0 && $user->pay_vat == 1) {
                    $tt += number_format(($taxVv->value / 100) * $order->price, 2, '.', '') * $order->amount;
                }

            }
            $todosVats[$vat->country] = $tt;
        }


        $typeWeight = '';

        if ($totalWeight <= 2) {
            $priceShippingHome = $shippingHome->kg2;
            $typeWeight = 'kg2';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg2;

        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $shippingHome->kg5;
            $typeWeight = 'kg5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg5;

        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $shippingHome->kg10;
            $typeWeight = 'kg10';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg10;

        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $shippingHome->kg20;
            $typeWeight = 'kg20';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg20;

        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $shippingHome->kg31_5;
            $typeWeight = 'kg31_5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg31_5;
        }

        // $idSprodutos = array_values($idSprodutos);

        $MaxtaxValuesByCountry = Tax::selectRaw('country, MAX(value)/100 as max_value')
            ->whereIn('product_id', array_values($idSprodutos))
            ->groupBy('country')
            ->get()
            ->pluck('max_value', 'country')
            ->toArray();

        // dd($MaxtaxValuesByCountry);

        // $taxValue = Tax::where('country', $user->country)->max('value');
        $paiss = $user->country;
        $taxValue = $MaxtaxValuesByCountry["$paiss"];

        if (isset($taxValue)) {
            if ($priceShippingHome > 0) {
                if ($taxValue > 0 && $user->pay_vat == 1) {

                    $total_tax_add += floatval($priceShippingHome) * floatval($taxValue);

                }
            }
        }

        $total_tax_add = number_format($total_tax_add, 2, '.', '');

        $priceShippingHome += $total_tax_add;

        $metodos = [];

        $countryes = ShippingPrice::orderBy('country', 'ASC')->get();
        $shippingPickup = PickupPoint::all();
        $todosFreteCasa = [];
        $todosFretePickup = [];

        foreach ($countryes as $value) {
            $value['priceShipping'] = $value->{"$typeWeight"};

            foreach ($MaxtaxValuesByCountry as $key => $valor) {
                if ($key == $value->country) {
                    if ($valor > 0 && $user->pay_vat == 1) {

                        $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};

                    } else {
                        $n = $value->{"$typeWeight"};
                    }

                    $n = number_format($n, 2, '.', '');
                    $todosFreteCasa[$key] = $n;
                }
            }
        }


        $allPickup = null;
        $temPickip = PickupPoint::where('country', $user->country)->first();
        if (isset($temPickip) || true) {
            $allPickup = PickupPoint::all();
            foreach ($allPickup as $value) {
                $value['priceShipping'] = $value->{"$typeWeight"};

                foreach ($MaxtaxValuesByCountry as $key => $valor) {
                    if ($key == $value->country) {
                        if ($valor > 0 && $user->pay_vat == 1) {

                            $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};

                        } else {
                            $n = $value->{"$typeWeight"};
                        }
                        $n = number_format($n, 2, '.', '');
                        $todosFretePickup[$value->country_code] = $n;
                    }
                }
            }
        } else {
            $allPickup = null;
            $shippingPickup = null;
            $priceShippingPickup = null;
        }

        // dd($todosFretePickup);

        $package_choosed = Session::get('package_choosed');
        $user_cart_package = null;
        $objeto = new \stdClass();
        $objeto->id = $package_choosed['id'];
        $objeto->name = $package_choosed['name'];
        $objeto->img = $package_choosed['img'];
        $objeto->price = $package_choosed['price'];
        $objeto->vat = $package_choosed['vat'];
        $objeto->amount = 1;
        $objeto->total = $package_choosed['total'];

        $package_choosed = $objeto;



        $withoutVAT = $subtotal - $total_VAT + $objeto->price;
        $semVat = $withoutVAT;

        $total_VAT += $objeto->vat;
        $subtotal += $objeto->total;

        $withoutVAT = number_format($withoutVAT, 2, ',', '.');
        $total_VAT = number_format($total_VAT, 2, ',', '.');
        // dd($subtotal);
        $btnSmart = null;

        Session::put(
            'products_choosed',
            $user_cart
        );

        return view('package.newProcess4', compact('package_choosed', 'btnSmart', 'todosFreteCasa', 'todosFretePickup', 'semVat', 'todosVats', 'MaxtaxValuesByCountry', 'cv', 'qv', 'allPickup', 'shippingPickup', 'countryes', 'user_cart', 'count_itens', 'subtotal', 'countPackages', 'total_VAT', 'metodos', 'priceShippingHome', 'priceShippingPickup', 'withoutVAT', 'user'));

    }

    public function newProcessPackage5(Request $request)
    {

        if (!Session::has('products_choosed')) {
            return $this->newProcessPackage1();
        }

        if (!Session::has('package_choosed')) {
            return $this->newProcessPackage1();
        }

        $product_choosed = Session::get('products_choosed');
        $package_choosed = Session::get('package_choosed');
        // dd($product_choosed, $package_choosed);

        $productController = new ProductController;

        $cart = $product_choosed;
        if (count($cart) < 1) {
            return $this->newProcessPackage1();
        }

        $userCorporate = User::where('id', User::find(Auth::id())->id)
            ->whereNotNull('id_corporate')
            ->whereNotNull('corporate_nome')
            ->first();

        $userCorporate = isset($userCorporate);

        // dd($request);
        // if ($userCorporate) {
        //     $request_methodPayment = 'admin';
        //     $request->methodPayment = 'admin';
        // } else {
        //     $request_methodPayment = null;

        // }

        $n_order = $productController->genNumberOrder();

        try {
            $total_shipping = number_format($request->total_shipping, 2, ",", ".");
        } catch (\Throwable $th) {
            if (strlen($request->total_vat) < 7) {
                $total_shipping = floatval(str_replace(',', '.', $request->total_shipping));
            } else {
                $valorSemSeparadorMilhar_total_shipping = str_replace('.', '', $request->total_shipping);
                $total_shipping = str_replace(',', '.', $valorSemSeparadorMilhar_total_shipping);
            }
        }
        $total_shipping = str_replace(',', '.', $total_shipping);


        if (strlen($request->price) < 7) {
            $price = floatval(str_replace(',', '.', $request->price));
        } else {
            $valorSemSeparadorMilhar = str_replace('.', '', $request->price);
            $price = str_replace(',', '.', $valorSemSeparadorMilhar);
        }

        if (strlen($request->total_vat) < 7) {
            $total_vat = floatval(str_replace(',', '.', $request->total_vat));
        } else {
            $valorSemSeparadorMilhar = str_replace('.', '', $request->total_vat);
            $total_vat = str_replace(',', '.', $valorSemSeparadorMilhar);
        }

        $numeroString = strval($price);
        $posicaoPonto = strpos($numeroString, '.');

        $newPrice = $price;

        if ($posicaoPonto) {
            $quant = strlen($numeroString) - $posicaoPonto - 1;
            if ($quant === 1) {
                $newPrice = $newPrice . '0';
            }
        } else {
            $newPrice = $newPrice . '00';
        }

        if ($request->method_shipping == 'home2') {
            // if ($request->address_shipping != "on") {
            if (
                empty($request->phone) ||
                empty($request->zip) ||
                empty($request->address) ||
                empty($request->number) ||
                // empty($request->state) ||
                empty($request->city) ||
                empty($request->country)
            ) {
                return redirect()->route('packages.newProcessPackage4GET')->with('error', 'Fill in all fields');
            }

            $nAdress = $productController->RegisteredAddressSecondary($request);

        } else if ($request->method_shipping == 'pickup') {

            if (
                empty($request->id_ppl) ||
                empty($request->accessPointType) ||
                empty($total_shipping) ||
                empty($request->dhlPsId)
            ) {
                return redirect()->route('packages.newProcessPackage4GET')->with('error', 'Select pickup address');
            }
            $nPickup = $productController->registerChosenPickup($request, $n_order);
            $paisNome = ShippingPrice::where('country_code', $request->country_ppl)->first()->country;
        }

        if ($request->methodPayment == 'BTC' || $request->methodPayment == 'ETH') {
            $responseData = $productController->payCrypto($request, $price, $request->methodPayment);

            $data = [
                "country" => $request->country ?? $paisNome ?? null,
                "smartshipping" => 0,
                "total_vat" => $total_vat,
                "id_invoice_trans" => $responseData->invoice_id,
                "url" => $responseData->url,
                "id_payment" => $responseData->id,
                "status" => 'Pending',
                "total_price" => $price,
                'method_shipping' => $request->method_shipping,
                'total_shipping' => $total_shipping,
                "method" => $request->methodPayment,
                'order' => $n_order
            ];

        } else if ($request->methodPayment == 'admin' || isset($request_methodPayment) && $request_methodPayment == 'admin') {
            $responseData = [
                "url" => route('packages.packagelog'),
            ];

            $data = [
                "country" => $request->country ?? $paisNome ?? null,
                "smartshipping" => 0,
                "total_vat" => $total_vat,
                "id_invoice_trans" => 'admin',
                "url" => $responseData['url'],
                "id_payment" => $n_order,
                "status" => 'pending',
                "total_price" => $price,
                'method_shipping' => $request->method_shipping,
                'total_shipping' => $total_shipping,
                "method" => 'admin',
                'order' => $n_order
            ];



        } else if ($request->methodPayment == 'comission') {
            $responseData = $productController->payComission($request, $price, $n_order);

            if ($responseData == false) {
                return redirect()->route('packages.newProcessPackage4GET')->with('error', 'insufficient comission ');
            }
            $data = [
                "country" => $request->country ?? $paisNome ?? null,
                "smartshipping" => 0,
                "total_vat" => $total_vat,
                "id_invoice_trans" => 'Comission',
                "url" => $responseData['url'],
                "id_payment" => $n_order,
                "status" => $responseData['status'],
                "total_price" => $price,
                'method_shipping' => $request->method_shipping,
                'total_shipping' => $total_shipping,
                "method" => $request->methodPayment,
                'order' => $n_order
            ];

        } else {

            $responseData = $productController->payComgate($request, $newPrice, $n_order, $request->methodPayment);

            $jsonResponse = [
                'code' => $responseData['code'],
                'message' => $responseData['message'],
                'transId' => $responseData['transId'],
                'redirect' => urldecode($responseData['redirect']),
            ];

            $data = [
                "country" => $request->country ?? $paisNome ?? null,
                "smartshipping" => 0,
                "total_vat" => $total_vat,
                "id_invoice_trans" => $jsonResponse['transId'],
                "url" => $jsonResponse['redirect'],
                "id_payment" => $n_order,
                'total_shipping' => $total_shipping,
                "status" => 'Pending',
                "total_price" => $price,
                'method_shipping' => $request->method_shipping,
                "method" => $request->methodPayment,
                'order' => $n_order
            ];
        }


        if (isset($responseData)) {
            $payment = $this->createRegisterPayment($data);
            $data['newPayment'] = $payment->id;
            $this->createEcommRegister($data);

            $package = Package::where('id', $package_choosed['id'])->first();
            $packageOrder = $this->createOrder(Auth::id(), $package, '', '', null, null, $package_choosed['total'], $data['method'], $package_choosed['vat_percent']);

            $codepayment = $data['id_payment'];
            $invoiceid = $data['id_invoice_trans'];

            $this->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, null);

            session()->put('redirect_buy', 'admin');

            if ($request->methodPayment == 'comission') {
                $productController->sendPostBonificacao($data["order"], 1);
            }
            // dd($data);

            Session::forget('products_choosed');
            Session::forget('package_choosed');

            return redirect()->away($data['url']);

        } else {
            return redirect()->route('packages.newProcessPackage4GET');
        }
    }

    public function createRegisterPayment($data)
    {
        if (!Session::has('products_choosed')) {
            return $this->newProcessPackage1();
        }

        if (!Session::has('package_choosed')) {
            return $this->newProcessPackage1();
        }

        $product_choosed = Session::get('products_choosed');
        $package_choosed = Session::get('package_choosed');

        $cart = $product_choosed;
        if (count($cart) < 1) {
            return $this->newProcessPackage1();
        }

        $user = User::find(Auth::id())->id;
        $package_choosed = Session::get('package_choosed');
        $total_price = $data["total_price"] - $package_choosed['total'];

        $newPayment = new PaymentOrderEcomm;
        $newPayment->id_user = User::find(Auth::id())->id;
        $newPayment->id_payment_gateway = $data["id_payment"];
        $newPayment->id_invoice_trans = $data["id_invoice_trans"];
        $newPayment->status = $data["status"];
        $newPayment->total_price = $total_price;
        $newPayment->number_order = $data['order'];
        $newPayment->payment_method = $data["method"];

        if ($data["method"] == 'Admin' || $data["method"] == 'admin') {
            $newPayment->order_corporate = 1;
        }

        $newPayment->save();

        return $newPayment;
    }

    public function createEcommRegister($data)
    {
        $user = User::find(Auth::id())->id;
        $user_country = User::find(Auth::id())->country;
        $order_cart = Session::get('products_choosed');
        $usuario = User::find(Auth::id());

        $maiorVat = 0;

        if (count($order_cart) < 1) {
            return redirect()->route('packages.packagelog');
        }

        foreach ($order_cart as $order) {
            $countryOrder = $data["country"] ?? $user_country;
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();

            if (isset($taxValue)) {
                $tax = $taxValue->value;
                if ($tax > $maiorVat) {
                    $maiorVat = $tax;
                }
            }
        }

        foreach ($order_cart as $order) {
            $countryOrder = $data["country"] ?? $user_country;
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();
            $price_product = Product::where('id', $order->id_product)->first();

            $total_VAT = 0;

            if (isset($taxValue)) {
                $tax = $taxValue->value;
                if ($tax > 0) {
                    $total_VAT += (($tax / 100) * $price_product->backoffice_price) * $order->amount;
                } else {
                    $total_VAT += 0;
                }
            }

            $qv = 0;
            $cv = 0;

            if (isset($price_product->qv)) {
                if ($price_product->qv > 0) {
                    $qv = $price_product->qv * $order->amount;
                }
            }

            if (isset($price_product->cv)) {
                if ($price_product->cv > 0) {
                    $cv = $price_product->cv * $order->amount;
                }
            }

            $orders = new EcommOrders;

            $orders->number_order = $data["order"];
            $orders->id_user = $user;
            $orders->id_product = $order->id_product;
            $orders->amount = $order->amount;
            $orders->total = $order->total;
            if ($usuario->pay_vat == 1) {
                $orders->total_vat = $total_VAT;
            } else {
                $orders->total_vat = 0;
            }
            $orders->status_order = "order placed";
            $orders->total_shipping = $data['total_shipping'];
            $orders->client_backoffice = 1;
            $orders->method_shipping = $data['method_shipping'];
            $orders->id_payment_order = $data['newPayment'];
            $orders->smartshipping = $data['smartshipping'];
            if ($usuario->pay_vat == 1) {
                $orders->vat_product_percentage = $taxValue->value;
                $orders->vat_shipping_percentage = $maiorVat;
            } else {
                $orders->vat_product_percentage = 0;
                $orders->vat_shipping_percentage = 0;
            }
            $orders->qv = $qv;
            $orders->cv = $cv;

            $orders->save();
            $separado = [];

            if ($price_product->kit == 2) {
                $parts = explode('|', $price_product->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                    $separado[$id_produto] = $quantidade;

                    $stock = new Stock;
                    $stock->user_id = $user;
                    $stock->product_id = $id_produto;
                    $stock->amount = -$order->amount * $quantidade;
                    $stock->number_order = $data["order"];
                    $stock->ecommerce_externo = 0;
                    $stock->save();
                }

            } else if ($price_product->kit == 0 || $price_product->kit == 1) {
                $stock = new Stock;
                $stock->user_id = $user;
                $stock->product_id = $order->id_product;
                $stock->amount = -$order->amount;
                $stock->number_order = $data["order"];
                $stock->ecommerce_externo = 0;
                $stock->save();
            }

        }
    }
}
