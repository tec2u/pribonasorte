<?php

namespace App\Http\Controllers;

use App\Models\AddressSecondary;
use App\Models\Banco;
use App\Models\Categoria;
use App\Models\ChosenPickup;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\PaymentOrderEcomm;
use App\Models\PickupPoint;
use App\Http\Controllers\Api\PaymentController;
use App\Models\PriceCoin;
use App\Models\Product;
use App\Models\ProductByCountry;
use App\Models\ShippingPrice;
use App\Models\Stock;
use App\Models\Tax;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\OrderPackage;
use App\Models\CartOrder;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $adesao = !$user->getAdessao($user->id); //verifica se ja tem adesão para liberar os outros produtos
        //$adesao = true;

        $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
            ->where(function ($query) {
                $query->where('availability', 'internal')
                    ->orWhere('availability', 'both');
            })
            ->get();




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

            if ($product_info->type != 'fisico') {
                $stock = 1;
            }
            $product_info['stock'] = $stock;
        }

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        if ($user->contact_id == NULL) {
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        } else {
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        }
    }

    public function detail($productid)
    {
        $user = User::find(Auth::id());
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();


        $product = Product::where('id', '=', $productid)->first();


        $stock = 1;
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
            }
        } else {

            $stock = DB::table('stock')
                ->where('product_id', $product->id)
                ->sum('amount');
        }

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('product.product', compact('product', 'countPackages', 'stock'));
    }

    public function addProductInToCart($id, $quant, $user_id = null)
    {

        $product = Product::find($id);

        $quantProduct = $quant;

        $stock = DB::table('stock')
            ->where('product_id', $id)
            ->sum('amount');

        if ($product->type != 'fisico') {
            $stock = 1000;
        }

        if ($quantProduct > $stock) {
            $quantProduct = $stock;
        }

        $debit_price = $product->backoffice_price;

        $totalBuy = $quantProduct * $debit_price;
        if (isset($user_id)) {
            $user = $user_id;
        } else {
            $user = User::find(Auth::id())->id;
        }

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
    }



    public function buyProduct(Request $request, $id)
    {
        $product = Product::find($id);
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
                    # code...
                    $this->addProductInToCart($key, $value);
                }
            }
            if ($product->kit == 1) {
                return redirect()->route('packages.cart_buy');
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

        if ($product->type != 'fisico') {
            $stock = 1000;
        }
        $quantProduct = $request->quant_product;

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


        return redirect()->route('packages.cart_buy');
    }



    public function editDownAmountBuy($id)
    {
        $downAmount = CartOrder::find($id);

        if (isset($downAmount)) {
            if ($downAmount->amount > 1) {
                $downAmount->update([
                    'amount' => $downAmount->amount - 1,
                    'total' => $downAmount->total - $downAmount->price,
                ]);
            } else {
                CartOrder::find($id)->delete();
            }
        }

        return redirect()->route('packages.cart_buy');
    }

    public function editUpAmountBuy($id)
    {
        $upAmount = CartOrder::find($id);

        if (isset($upAmount)) {
            if ($upAmount->amount > 0) {
                $upAmount->update([
                    'amount' => $upAmount->amount + 1,
                    'total' => $upAmount->total + $upAmount->price,
                ]);
            }
        }

        return redirect()->route('packages.cart_buy');
    }

    public function deleteCartBuy($id)
    {
        $cart = CartOrder::find($id);
        if (isset($cart)) {
            $cart->delete();
        }

        return redirect()->route('packages.cart_buy');
    }

    public function clearCartBuy($user_id = null)
    {

        $user_cart = CartOrder::where('id_user', $user_id)->get();

        foreach ($user_cart as $product_item) {
            CartOrder::find($product_item->id)->delete();
        }

        return true;
    }


    public function cardBuy($btnSmart = null)
    {
        $user = User::find(Auth::id());
        $id_user = Auth::id();
        $user_cart = CartOrder::where('id_user', '=', $id_user)->get();

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
            return redirect()->route('packages.index_products')->withErrors(['address' => $complete_registration]);
        }


        if (count($user_cart) < 1) {
            return redirect()->route('packages.index_products');
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

        return view('cart_buy', compact('btnSmart', 'user_cart', 'count_itens', 'subtotal', 'countPackages', 'total_VAT', 'withoutVAT', 'user'));
    }

    public function registerChosenPickup(Request $request, $number_order)
    {
        $newChosenPickup = new ChosenPickup;

        $newChosenPickup->id_ppl = $request->id_ppl;
        $newChosenPickup->accessPointType = $request->accessPointType;
        $newChosenPickup->code = $request->code;
        $newChosenPickup->dhlPsId = $request->dhlPsId;
        $newChosenPickup->depot = $request->depot;
        $newChosenPickup->depotName = $request->depotName;
        $newChosenPickup->name = $request->name_ppl;
        $newChosenPickup->street = $request->street_ppl;
        $newChosenPickup->city = $request->city_ppl;
        $newChosenPickup->zipCode = $request->zipCode_ppl;
        $newChosenPickup->country = $request->country_ppl;
        $newChosenPickup->parcelshopName = $request->parcelshopName;
        $newChosenPickup->id_user = User::find(Auth::id())->id;
        $newChosenPickup->number_order = $number_order;

        $newChosenPickup->save();

        return $newChosenPickup;
    }

    public function paymentLinkRender($orderID)
    {
        $order = EcommOrders::where('number_order', $orderID)->first();

        return view('package.payment', compact('order'));
    }

    public function cartFinalize(Request $request)
    {
        $cart = CartOrder::where('id_user', User::find(Auth::id())->id)->get();
        if (count($cart) < 1) {
            return redirect()->route('packages.cart_buy');
        }

        $n_order = $this->genNumberOrder();

        $testMode = false;
        if ($testMode) {
            $total = 0;
            foreach ($cart as $product) {
                $total += $product->total;
            }
            $responseData = [
                'id' => 1,
                'status' => 'pending',
                'cart_settings' => [
                    'items_total_cost' => $total * 100,
                    'shipping_total_cost' => $request->send_value,
                ],
                'url' => route('home.home')
            ];
        } else {
            $responseData = $this->payment($request, 0, $n_order);
            // return response()->json($responseData);
        }
        if (isset($responseData)) {
            $payment = $this->createRegisterPayment($responseData, $n_order, $request);

            $this->createRegisterEcommOrder($responseData, $n_order, $payment, $request);

            if ($testMode) {
                return redirect()->route('home.home');
            }
            return redirect()->route('packages.payment_link_render', ['orderID' => $n_order]);
        } else {
            return redirect()->back();
        }
    }

    public function createRegisterEcommOrder($data, $n_order = null, $payment, $request)
    {

        $user_id = Auth::id();
        $order_cart = CartOrder::where('id_user', $user_id)->get();

        if (count($order_cart) < 1) {
            return redirect()->route('packages.packagelog');
        }

        foreach ($order_cart as $order) {
            $qv = 0;
            $cv = 0;

            $orders = new EcommOrders;

            $orders->number_order = $n_order;
            $orders->id_user = $user_id;
            $orders->id_product = $order->id_product;
            $orders->amount = $order->amount;
            $orders->total = $order->total;
            $orders->status_order = "order placed";
            if ($request->payment_method == 'paypal') {
                $orders->total_shipping = $data['cart_settings']['shipping_total_cost'];
                $orders->payment_link = $data['url'];
            } else {
                $orders->total_shipping = 0;
                $orders->payment_link = $data->init_point;
            }
            $orders->client_backoffice = 1;
            $orders->id_payment_order = $payment->id;
            $orders->qv = $qv;
            $orders->cv = $cv;

            $orders->save();
            $stock = new Stock;
            $stock->user_id = $user_id;
            $stock->product_id = $order->id_product;
            $stock->amount = -$order->amount;
            $stock->number_order = $n_order;
            $stock->save();
        }

        $this->clearCartBuy($user_id);
    }

    public function createRegisterPayment($data, $orderID, $request)
    {
        $newPayment = new PaymentOrderEcomm;
        $newPayment->id_user = auth()->user()->id;

        if ($request->payment_method == 'paypal') {
            $newPayment->id_payment_gateway = $data["id"];
            $newPayment->id_invoice_trans = $data["id"];
            $newPayment->status = $data["status"];

            $newPayment->total_price = $data["cart_settings"]["items_total_cost"] / 100; //preço retorna em centavos
        } else {
            $newPayment->id_payment_gateway = $data->id;
            $newPayment->id_invoice_trans = $data->id;
            $newPayment->status = 'pending';
            $total_price = 0;
            foreach ($data->items as $item) {
                $total_price += ($item->quantity * $item->unit_price);
            }
            $newPayment->total_price = $total_price;
        }
        $newPayment->number_order = $orderID;

        $newPayment->save();

        return $newPayment;
    }

    public function sendPostBonificacao($number_order, $prod, $user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = Auth::id();
        }

        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];


        $data = [
            "type" => "bonificacao",
            "param" => "GeraBonusPedidoInterno",
            "idpedido" => "$number_order",
            "prod" => $prod
        ];



        $url = 'https://Pribonasorte.eu/public/compensacao/bonificacao.php';

        try {
            $resposta = $client->post($url, [
                'form_params' => $data,
                // 'headers' => $headers,

            ]);

            $statusCode = $resposta->getStatusCode();
            $body = $resposta->getBody()->getContents();

            parse_str($body, $responseData);

            //code...
            $log = new CustomLog;
            $log->content = json_encode($responseData);
            $log->user_id = User::find($user_id)->id;
            $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idpedido'];
            $log->controller = "app/controller/ProductController";
            $log->http_code = 200;
            $log->route = "packages.cartFinalize";
            $log->status = "SUCCESS";
            $log->save();
        } catch (\Throwable $th) {
            return;
        }

        return $responseData;
    }

    public function payComission(Request $request, $price, $order, $user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = User::find(Auth::id())->id;
        }

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
            $subMonth = '0' . $subMonth;
        } else {
            $subMonth = $subMonth;
        }

        $farst_day = date('t', mktime(0, 0, 0, $subMonth, '01', $currentYear));

        $dateComplete = $currentYear . '-' . $subMonth . '-' . $farst_day . ' 23:59:59';

        $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        if ($currentDate->day >= $dayThreshold) {
            $cardAvailable = true;
            $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
            $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

            $availableComission = DB::table('banco')
                ->where('user_id', $user_id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete2)
                ->sum('price');
        } else {
            $availableComission = DB::table('banco')
                ->where('user_id', $user_id)
                ->where('price', '>', 0)
                ->where('created_at', '<=', $dateComplete)
                ->sum('price');
        }

        // dd('Se for 16 ou mais ' . $dateComplete2, 'Menor que 16 ' . $dateComplete);

        $retiradasTotais = DB::table('banco')
            ->where('user_id', $user_id)
            ->where('price', '<', 0) // Considera apenas valores negativos
            ->sum('price');

        $retiradasTotais = -$retiradasTotais;

        if ($retiradasTotais >= $availableComission) {
            $availableComission = 0;
        } else {
            $availableComission = $availableComission - $retiradasTotais;
        }
        $saldo = $availableComission;


        if ($saldo >= $price) {
            $pagamento = new Banco;
            $pagamento->user_id = $user_id;
            $pagamento->order_id = $order;
            $pagamento->description = 99;
            $pagamento->price = -$price;
            $pagamento->status = 1;
            $pagamento->level_from = 0;
            $pagamento->save();

            return [
                "status" => "paid",
                "url" => route('packages.packagelog')
            ];
        } else {
            return false;
        }
    }

    public function payCrypto(Request $request, $price, $method = "btc")
    {
        $name = "Pribonasorte";

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
        $url = "https://Pribonasorte.eu/ecomm/finalize/notify";

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
                "notify_url" : "' . $url . '"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            )
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

    public function createClientPaymentAPI($request)
    {
        $user = auth()->user();
        $url = 'https://api.pagar.me/core/v5/customers/';

        $countryCode = substr($request->cell_ppl, 0, 2); // Os 2 primeiros dígitos
        $areaCode = substr($request->cell_ppl, 2, 2);   // Os 2 dígitos seguintes
        $number = substr($request->cell_ppl, 4);
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'code' => $user->id,
            'document' => $user->cpf,
            'document_type' => "CPF",
            'type' => "individual",
            'gender' => $user->gender == "M" ? "male" : "female",
            'address' => [
                "country" => "BR",
                "state" => $request->state_ppl,
                "city" => $request->city_ppl,
                "zip_code" => preg_replace('/\D/', '', $request->zip_code_ppl),
                "line_1" => $request->number_ppl . ", " . $request->address_ppl . ", " . $request->neighborhood_ppl //"154, Rua Eunice Leoni Butignon, Parque Bom Retiro",
            ],
            'phones' => [
                "mobile_phone" => [
                    "country_code" => $countryCode,
                    "area_code" => $areaCode,
                    "number" => $number,
                ]
            ],
            'birthdate' => $user->birthday,
        ];

        $requestAPI = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '');

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }
        $response = $requestAPI->post($url, $data);
        $data = $response->json();
        User::where('id', auth()->user()->id)->update(['code_api' => $data['id']]);
        if ($response->successful()) {
            return $data['id'];
        } else {
            return $response->body(); // Retorna o corpo da resposta para análise
        }
    }

    public function checkClientExistsAPI($request)
    {
        $code = auth()->user()->code_api;
        $url = 'https://api.pagar.me/core/v5/customers/';

        $requestAPI = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '');

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }

        $response = $requestAPI->get($url . $code);

        if ($response->successful() && isset($response->json()["id"])) {
            return $response->json()["id"];
        } else {
            return $this->createClientPaymentAPI($request);
        }
    }

    public function createNewPaymentOrderAPI($customerID, $request)
    {
        $url = 'https://sdx-api.pagar.me/core/v5/paymentlinks';
        $cartItems = CartOrder::with('product')->where('id_user', User::find(Auth::id())->id)->get();

        $items = [];
        foreach ($cartItems as $item) {
            $obj = [
                "name" => $item->product->name,
                "amount" => $item->price * 100,
                "default_quantity" => $item->amount,
                "description" => $item->product->description_fees,
                "shipping_cost" => 0,
            ];
            $items[] = $obj;
            unset($obj);
        }

        $expiresAt = Carbon::now()->setTime(23, 59, 59)->format('Y-m-d\TH:i:s\Z');

        $data = [
            "is_building" => false,
            "name" => 'Pedido de compra',
            "type" => "order",
            "expires_at" => $expiresAt,
            "payment_settings" => [
                "accepted_payment_methods" => ["boleto", "pix"],
                "statement_descriptor" => "Pagamento",
                "boleto_settings" => [
                    "due_at" =>  Carbon::parse($expiresAt)->addDays(3)->format('Y-m-d')
                ],
                "pix_settings" => [
                    "expires_in" => 24,
                    "discount_percentage" => 0,
                    "additional_information" => [
                        [
                            "Name" => "Pedido",
                            "Value" => "Pagamaento de pedido para pribonasorte.com"
                        ],
                    ]
                ]
            ],
            "customer_settings" => [
                "customer_id" => $customerID
            ],
            "cart_settings" => [
                "shipping_cost" => floatval($request->send_value) * 100,
                "items" => $items
            ],
            "layout_settings" => [
                "image_url" => "https://pribonasorte.tecnologia2u.com.br/img/logo-2.png",
                "primary_color" => "#FADCE6",
                "secondary_color" => "#F0A2FF"
            ]
        ];

        $requestAPI = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->post($url, $data);

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }

        $response = $requestAPI->post($url, $data);
        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json($response->body());
        }
    }

    public function updateOrCreateClientAddress($customerID, $request)
    {
        $url = 'https://api.pagar.me/core/v5/customers/';

        $requestAPI = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '');

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }

        $response = $requestAPI->get($url . $customerID . "/addresses");

        if (count($response->json()["data"]) > 0) {
            $addressID = $response->json()["data"][0]["id"];
            $requestAPI = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withBasicAuth(env('API_PAGARME_KEY'), '');
        }

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }

        $response = $requestAPI->delete($url . $customerID . "/addresses" . "/" . $addressID);
        $data = [
            "country" => "BR",
            "state" => $request->state_ppl,
            "city" => $request->city_ppl,
            "zip_code" => preg_replace('/\D/', '', $request->zip_code_ppl),
            "line_1" => $request->number_ppl . ", " . $request->address_ppl . ", " . $request->neighborhood_ppl,
            "line_2" => ""
        ];

        $requestAPI = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '');

        if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $requestAPI->withoutVerifying();
        }

        $response = $requestAPI->post($url . $customerID . "/addresses", $data);
        return response()->json($response->json());
    }

    public function payment(Request $request)
    {
        if ($request->payment_method == 'paypal') {
            $customerID = $this->checkClientExistsAPI($request);

            $this->updateOrCreateClientAddress($customerID, $request);

            $paymentResponse = $this->createNewPaymentOrderAPI($customerID, $request);
        } else {
            $paymentMP = new PaymentController();
            $paymentResponse = $paymentMP->createPaymentMP($request, auth()->user()->id);
        }

        return $paymentResponse;
    }

    public function RegisteredAddressSecondary(Request $request)
    {
        // dd($request);
        $id_user = User::find(Auth::id())->id;

        $exists = AddressSecondary::where('id_user', $id_user)->where('backoffice', 1)->first();

        if (isset($exists)) {

            $exists->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->countryCodeCell . $request->phone,
                'zip' => $request->zip,
                'address' => $request->address,
                'number' => $request->number,
                'complement' => "",
                'neighborhood' => "",
                'city' => $request->city,
                'state' => $request->state ?? '',
                'country' => $request->country,
            ]);
        } else {

            $address = new AddressSecondary;
            $address->id_user = $id_user;
            $address->first_name = $request->first_name;
            $address->last_name = $request->last_name;
            $address->phone = $request->countryCodeCell . $request->phone;
            $address->zip = $request->zip;
            $address->address = $request->address;
            $address->number = $request->number;
            $address->complement = "";
            $address->neighborhood = "";
            $address->city = $request->city;
            $address->state = $request->state ?? '';
            $address->country = $request->country;
            $address->backoffice = 1;

            $address->save();
        }
        return true;
    }

    public function genNumberOrder()
    {
        $numb_order = random_int(10000000, 99999999);
        $exists = EcommOrders::where('number_order', $numb_order)->first();
        if (isset($exists)) {
            return $this->genNumberOrder();
        } else {
            return $numb_order;
        }
    }

    public function smartshipping(Request $request)
    {
        // dd($request);
        $n_order = $this->genNumberOrder();

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
            if (
                empty($request->phone) ||
                empty($request->zip) ||
                empty($request->address) ||
                empty($request->number) ||
                // empty($request->neighborhood) ||
                // empty($request->state) ||
                empty($request->city) ||
                empty($request->country)
            ) {
                return redirect()->back()->with('error', 'Fill in all fields');
            }

            $nAdress = $this->RegisteredAddressSecondary($request);
        } else if ($request->method_shipping == 'pickup') {
            if (
                empty($request->id_ppl) ||
                empty($request->accessPointType) ||
                empty($total_shipping) ||
                empty($request->dhlPsId)
            ) {
                return redirect()->back()->with('error', 'Select pickup address');
            }
            $nPickup = $this->registerChosenPickup($request, $n_order);
            $paisNome = ShippingPrice::where('country_code', $request->country_ppl)->first()->country;
        }


        $responseData = $this->payment($request, $newPrice, $n_order, 'CARD_CZ_CSOB_2', Auth::id(), true);

        $jsonResponse = [
            'code' => $responseData['code'],
            'message' => $responseData['message'],
            'transId' => $responseData['transId'],
            'redirect' => urldecode($responseData['redirect']),
        ];

        $data = [
            "country" => $request->country ?? $paisNome ?? null,
            "smartshipping" => 1,
            "total_vat" => $total_vat,
            "id_invoice_trans" => $jsonResponse['transId'],
            "url" => $jsonResponse['redirect'],
            "id_payment" => $n_order,
            'total_shipping' => $total_shipping,
            "status" => 'Pending',
            "total_price" => $price,
            'method_shipping' => $request->method_shipping,
            "method" => 'CARD_CZ_CSOB_2',
            'order' => $n_order
        ];


        if (isset($responseData)) {
            $payment = $this->createRegisterPayment($data);
            $data['newPayment'] = $payment->id;
            $this->createRegisterEcommOrder($data);

            session()->put('redirect_buy', 'admin');

            return redirect()->away($data['url']);
        } else {
            return redirect()->back();
        }
    }

    public function tracking(Request $request)
    {
        $orderNumber = $request->input('order');
        $orders = EcommOrders::where('number_order', $orderNumber)->orWhere('shipmentNumber', $orderNumber)->get();
        $shippingNumber = null;

        if (count($orders) > 0) {
            foreach ($orders as $value) {
                if (isset($value->shipmentNumber) && $value->shipmentNumber != null) {
                    $shippingNumber = $value->shipmentNumber;
                }
            }
        }

        if ($shippingNumber) {
            $info = $this->dataTrack($shippingNumber);

            if ($info) {
                $info = $info['events'];
                // dd($info);
                return view('product.tracking', compact('orderNumber', 'info'));
            }
        }

        return view('product.tracking', compact('orderNumber'));
    }


    private function dataTrack($shippingNumber)
    {
        $token = $this->genTokenPPL();

        $params = [
            'limit' => 50,
            'offset' => 0,
            'Accept-Language' => 'en',
            'ShipmentNumbers' => $shippingNumber,


        ];

        $client = new Client();
        $url = 'https://api.dhl.com/ecs/ppl/myapi2/shipment';

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => $params,
            ]);

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            if (isset($data[0]['trackAndTrace'])) {
                return $data[0]['trackAndTrace'];
            } else {
                return false;
            }

            // Faça o que você precisa com os dados da resposta.
        } catch (GuzzleException $e) {
            // Lida com erros de solicitação.
            return false;
        }
    }

    private function genTokenPPL()
    {
        $url = "https://api.dhl.com/ecs/ppl/myapi2/login/getAccessToken";
        $data = array(
            "grant_type" => "client_credentials",
            "scope" => "myapi2",
            "client_id" => "INTER4443873",
            "client_secret" => "UaqffFHrdKvZY7p0W1kGAjLiIQeuj2tH"
        );

        $options = array(
            "http" => array(
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($data)
            )
        );

        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        if ($response === FALSE) {
            return null;
        }

        return json_decode($response)->access_token;
    }

    public function categoria($categoria)
    {
        $categoria = Categoria::where('id', $categoria)->first();

        if (!isset($categoria)) {
            abort(404);
        }

        $user = User::find(Auth::id());
        $adesao = !$user->getAdessao($user->id); //verifica se ja tem adesão para liberar os outros produtos
        //$adesao = true;

        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {
            $products = Product::orderBy('sequence', 'asc')
                ->where('activated', 1)
                ->whereIn('id', $productsByCountry)
                ->where('id_categoria', 'LIKE', "%$categoria->id%")
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->get();
        } else {
            $products = Product::orderBy('sequence', 'asc')
                ->where('activated', 1)
                ->where('id_categoria', 'LIKE', "%$categoria->id%")
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

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        if ($user->contact_id == NULL) {
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        } else {
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        }
    }
}
