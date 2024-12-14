<?php

namespace App\Http\Controllers;

use App\Models\AddressBilling;
use App\Models\AddressSecondary;
use App\Models\BestDatePaymentSmartshipping;
use App\Models\CancelSmart;
use App\Models\Categoria;
use App\Models\ChosenPickup;
use App\Models\CustomLog;
use App\Models\InvoicesFakturoid;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\ProductByCountry;
use App\Models\Stock;
use App\Models\TaxPackage;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\OrderEcomm;
use App\Models\EcommRegister;
use App\Models\EcommOrders;
use App\Models\Tax;
use App\Models\ShippingPrice;
use App\Models\PickupPoint;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use SoapClient;
use Fakturoid\Client as FakturoidClient;
use Illuminate\Support\Facades\Http;

class EcommController extends Controller
{
    public function index()
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        $products = Product::orderBy('sequence', 'asc')->where('activated', 1)
            ->where(function ($query) {
                $query->where('availability', 'external')
                    ->orWhere('availability', 'both');
            })->get();

        foreach ($products as $product) {
            $totalAmount = null;

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
                        $totalAmount = 0;
                        break;
                    } else {
                        $totalAmount = $temStock;
                    }
                }
            } else {

                $totalAmount = DB::table('stock')
                    ->where('product_id', $product->id)
                    ->sum('amount');
            }

            $product->stock = $totalAmount;
        }

        $allprod = $this->productsRandom();

        return view('ecomm.ecomm', compact('products', 'count_order', 'allprod'));
    }

    public function createClientPaymentAPI($request)
    {
        $user = EcommRegister::where('id', $request->id_user_login)->first();
        $url = 'https://api.pagar.me/core/v5/customers/';

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'code' => $user->id,
            'document' => "50101401445",
            'document_type' => "CPF",
            'type' => "individual",
            'gender' => "male",
            'address' => [
                "country" => "BR",
                "state" => 'SP',
                "city" => 'Paulinia',
                "zip_code" => '13000000',
                "line_1" => "100, Rua Teste, Parque Teste"
            ],
            'phones' => [
                "mobile_phone" => [
                    "country_code" => '55',
                    "area_code" => '19',
                    "number" => '979744515',
                ]
            ],
            'birthdate' => "01-01-2001",
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->post($url, $data);

        $data = $response->json();
        // return $data;
        EcommRegister::where('id', $user->id)->update(['code_api' => $data['id']]);
        if ($response->successful()) {
            return $data['id'];
        } else {
            return $response->body(); // Retorna o corpo da resposta para análise
        }
    }

    public function checkClientExistsAPI($request)
    {
        $code = EcommRegister::where('id', $request->id_user_login)->first()->code_api;
        $url = 'https://api.pagar.me/core/v5/customers/';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->get($url . $code);

        if ($response->successful() && isset($response->json()["id"])) {
            return $response->json()["id"];
        } else {
            return $this->createClientPaymentAPI($request);
        }
    }

    public function createNewPaymentOrderAPI($customerID, $request, $cartOrder)
    {
        $url = 'https://api.pagar.me/core/v5/paymentlinks';

        $items = [];
        foreach ($cartOrder as $item) {
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
                "shipping_cost" => 0,
                "items" => $items
            ],
            "layout_settings" => [
                "image_url" => "https://pribonasorte.tecnologia2u.com.br/img/logo-2.png",
                "primary_color" => "#FADCE6",
                "secondary_color" => "#F0A2FF"
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->body();
        }
    }

    public function updateOrCreateClientAddress($customerID, $request)
    {
        $url = 'https://api.pagar.me/core/v5/customers/';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->get($url . $customerID . "/addresses");

        if (count($response->json()["data"]) > 0) {
            $addressID = $response->json()["data"][0]["id"];
            $data = [
                "country" => "BR",
                "state" => $request->state_ppl,
                "city" => $request->city_ppl,
                "zip_code" => preg_replace('/\D/', '', $request->zip_code_ppl),
                "line_1" => $request->number_ppl . ", " . $request->address_ppl . ", " . $request->neighborhood_ppl,
                "line_2" => ""
            ];
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->delete($url . $customerID . "/addresses" . "/" . $addressID);
        }


        $data = [
            "country" => "BR",
            "state" => $request->state_ppl,
            "city" => $request->city_ppl,
            "zip_code" => preg_replace('/\D/', '', $request->zip_code_ppl),
            "line_1" => $request->number_ppl . ", " . $request->address_ppl . ", " . $request->neighborhood_ppl,
            "line_2" => ""
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('API_PAGARME_KEY'), '')->withoutVerifying()->post($url . $customerID . "/addresses", $data);

        return response()->json($response->json());
    }

    public function payment($request, $order)
    {
        $customerID = $this->checkClientExistsAPI($request);

        $this->updateOrCreateClientAddress($customerID, $request);

        $paymentResponse = $this->createNewPaymentOrderAPI($customerID, $request, $order);

        return $paymentResponse;
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

    public function registerOrder(Request $request, $data = [])
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];

        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();

        if (!empty($request->email) || !empty($request->id_user_login)) {

            if (!empty($request->email)) {
                $verific_register = EcommRegister::where('email', $request->email)->first();
            } else {
                $verific_register = EcommRegister::where('id', $request->id_user_login)->first();
            }

            if ($verific_register) {


                $numb_order = $this->genNumberOrder();


                $newPayment = new PaymentOrderEcomm;
                $newPayment->id_user = $verific_register->id;
                $newPayment->id_payment_gateway = $data["id"];
                $newPayment->id_invoice_trans = $data["id"];
                $newPayment->status = strtolower($data["status"]);
                $newPayment->total_price = $data["cart_settings"]["items_total_cost"] / 100;
                $newPayment->number_order = $numb_order;
                $newPayment->payment_method = 'api';
                $newPayment->save();

                foreach ($order_cart as $order) {
                    $order_total = 0;

                    $orders = new EcommOrders;

                    $orders->number_order = $numb_order;
                    $orders->id_user = $verific_register->id;
                    $orders->id_product = $order->id_product;
                    $orders->amount = $order->amount;
                    $orders->total = $order_total;
                    $orders->payment_link = $data["url"];
                    $orders->total_vat = 0;
                    $orders->total_shipping = 0;
                    $orders->status_order = "order placed";
                    $orders->client_backoffice = 0;
                    $orders->vat_product_percentage = 0;
                    $orders->vat_shipping_percentage = 0;
                    $orders->qv = 0;
                    $orders->cv = 0;
                    $orders->id_payment_order = $newPayment->id;
                    $orders->method_shipping = 0;
                    $orders->smartshipping = 0;
                    $orders->save();
                }

                // clear cart
                foreach ($order_cart as $order_del) {
                    OrderEcomm::findOrFail($order_del->id)->delete();
                }
            }
        } else {
            $user_new = $this->RegisterUser($request);

            if (isset($data['order'])) {
                $numb_order = $data['order'];
            } else {
                $numb_order = $this->genNumberOrder();
            }

            $newPayment = new PaymentOrderEcomm;
            $newPayment->id_user = $user_new->id;
            $newPayment->id_payment_gateway = $data["id_payment"];
            $newPayment->id_invoice_trans = $data["id_invoice_trans"];
            $newPayment->status = strtolower($data["status"]);
            $newPayment->total_price = $data["total_price"];
            $newPayment->number_order = $numb_order;
            $newPayment->payment_method = $data["method"];
            $newPayment->save();

            foreach ($order_cart as $order) {
                $orders = new EcommOrders;

                $orders->number_order = $numb_order;
                $orders->id_user = $user_new->id;
                $orders->id_product = $order->id_product;
                $orders->amount = $order->amount;
                $orders->total = $data["total_price"];
                $orders->total_vat = 0;
                $orders->total_shipping = 0;
                $orders->status_order = 0;
                $orders->id_payment_order = $newPayment->id;
                $orders->vat_product_percentage = 0;
                $orders->vat_shipping_percentage = 0;
                $orders->qv = 0;
                $orders->cv = 0;
                $orders->client_backoffice = 0;
                if (isset($data["smartshipping"]) and !empty($data["smartshipping"])) {
                    $orders->smartshipping = $data["smartshipping"];
                } else {
                    $orders->smartshipping = 0;
                }
                $orders->save();

                // clear cart
                foreach ($order_cart as $order_del) {
                    OrderEcomm::findOrFail($order_del->id)->delete();
                }
            }
        }

        return $orders;
    }

    public function finalizeEcomm(Request $request)
    {


        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::with('product')->where('ip_order', $ip_order)->get();
        if (count($order_cart) < 1) {
            return redirect()->route('ecomm');
        }

        $responseData = $this->payment($request, $order_cart);
        if (isset($responseData)) {
            $payment = $this->registerOrder($request, $responseData);
            return redirect()->route("payment_render.ecomm", ["id" => $payment->number_order]);
        } else {
            return redirect()->back();
        }
    }

    public function renderPayment($id)
    {
        $order = EcommOrders::where('number_order', $id)->first();
        return view('ecomm.renderPayment', compact('order'));
    }

    public function categoria($cat)
    {
        $categoria = Categoria::where('id', $cat)->first();


        $products = Product::orderBy('sequence', 'asc')
            ->where('activated', 1)
            ->where('id_categoria', 'LIKE', "%$categoria->id%")
            ->where(function ($query) {
                $query->where('availability', 'external')
                    ->orWhere('availability', 'both');
            })->get();


        foreach ($products as $product) {
            $totalAmount = null;

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
                        $totalAmount = 0;
                        break;
                    } else {
                        $totalAmount = $temStock;
                    }
                }
            } else {

                $totalAmount = DB::table('stock')
                    ->where('product_id', $product->id)
                    ->sum('amount');
            }

            $product->stock = $totalAmount;
        }

        $allprod = $this->productsRandom();

        return view('ecomm.ecomm', compact('products', 'count_order', 'countryIp', 'allprod'));
    }


    public function CalculateShipping()
    {
        return redirect()->back();
        // CONTINUAR AQUI

    }

    public function cart()
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        $ip_order = '85.214.132.117';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];

        if (session()->has('buyer')) {
            $user = session()->get('buyer');
            $countryIp = ["country" => $user->country];
            $countryIpOrder = $user->country;
        }

        $total_order = 0;
        $total_VAT = 0;
        $withoutVAT = 0;
        $priceShippingHome = 0;
        $priceShippingPickup = null;
        $subtotal = 0;
        $totalWeight = 0;

        $qv = 0;
        $score = 0.5;

        foreach ($order_cart as $order) {
            $total_order = $total_order + $order->total;
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryIp['country'])->first();
            $findProduct = Product::where('id', $order->id_product)->first();
            $weightProduct = $findProduct;

            if ($findProduct->qv > 0) {
                $qv += $findProduct->qv * $order->amount;
            }

            $weightProduct = $weightProduct->weight * $order->amount;
            $totalWeight += $weightProduct;

            if (isset($taxValue)) {
                $tax = $taxValue->value;
                if ($tax > 0) {

                    $total_order += (($tax / 100) * $order->price) * $order->amount;
                    $total_VAT += (($tax / 100) * $order->price) * $order->amount;
                    $priceTax = (($tax / 100) * $order->price);

                    $order->priceTax = $priceTax;
                } else {
                    $priceTax = 0;
                    $order->priceTax = $priceTax;
                }
            } else {
                $order->priceTax = 0;
            }
        }

        $total_finish = $total_order;

        $total_VAT = number_format($total_VAT, 2, ",", ".");

        $format_price = number_format($total_finish, 2, ",", ".");

        return view('ecomm.ecomm_cart', compact('qv', 'order_cart', 'count_order', 'format_price', 'total_VAT', 'countryIpOrder', 'priceShippingHome'));
    }

    function getProductsByCategory($categoria)
    {
        if (session()->has('buyer')) {
            $user = session()->get('buyer');
            $countryIp = ["country" => $user->country];

            $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
            $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

            if (count($productsByCountry) > 0) {

                $products = Product::orderBy('sequence', 'asc')
                    ->where('activated', 1)
                    ->whereIn('id', $productsByCountry)
                    ->where('id_categoria', 'LIKE', "%$categoria->id%")
                    ->where(function ($query) {
                        $query->where('availability', 'external')
                            ->orWhere('availability', 'both');
                    })
                    ->get();
            } else {
                $products = Product::orderBy('sequence', 'asc')
                    ->where('activated', 1)
                    ->where('id_categoria', 'LIKE', "%$categoria->id%")
                    ->where(function ($query) {
                        $query->where('availability', 'external')
                            ->orWhere('availability', 'both');
                    })
                    ->get();
            }
        } else {
            $products = Product::orderBy('sequence', 'asc')
                ->where('activated', 1)
                ->where('id_categoria', 'LIKE', "%$categoria->id%")
                ->where(function ($query) {
                    $query->where('availability', 'external')
                        ->orWhere('availability', 'both');
                })
                ->get();
        }

        return $products;
    }

    public function detals($id)
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        if (session()->has('buyer')) {
            $user = session()->get('buyer');
            $countryIp = ["country" => $user->country];

            $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
            $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

            if (count($productsByCountry) > 0) {
                $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->where('id_product', $id)->first();

                if (!isset($productsByCountry))
                    return abort(404);

                $product = Product::where('id', '=', $id)->first();
            } else {

                $product = Product::where('id', '=', $id)->first();
            }
        } else {
            $product = Product::find($id);
        }

        $separado = [];
        $totalAmount = 1;

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
                    $totalAmount = 0;
                    break;
                } else {
                    $totalAmount = $temStock;
                }
            }
        } else {

            $totalAmount = DB::table('stock')
                ->where('product_id', $product->id)
                ->sum('amount');
        }



        $product->stock = $totalAmount;

        $ip_order = '194.156.125.61';
        $url = "http://ip-api.com/json/{$ip_order}?fields=country";

        $response = file_get_contents($url);
        $countryIpOrder = json_decode($response, true);
        $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
        $countryIp = ["country" => $countryIpOrder];

        if (session()->has('buyer')) {
            $user = session()->get('buyer');
            $countryIp = ["country" => $user->country];
        }

        $categorias = Categoria::get();

        foreach ($categorias as $category) {
            $category->products = $this->getProductsByCategory($category);
        }

        $allprod = $this->productsRandom();

        return view('ecomm.ecomm_detals', compact('product', 'count_order', 'countryIp', 'categorias', 'allprod'));
    }

    public function productsRandom()
    {

        $allprod = Product::where('activated', 1)
            ->where(function ($query) {
                $query->where('availability', 'external')
                    ->orWhere('availability', 'both');
            })
            ->inRandomOrder()
            ->get();

        return $allprod;
    }

    public function addProductInToCart($id, $quant)
    {
        $id_products = $id;
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $product_info = Product::findOrFail($id_products);

        $replice = OrderEcomm::where('id_product', $id_products)->where('ip_order', $ip_order)->first();
        $stock = DB::table('stock')
            ->where('product_id', $id_products)
            ->sum('amount');


        if (isset($replice->amount) && $replice->amount >= $stock) {
            $quantify_products = 0;
        } else {
            $quantify_products = $quant > $stock ? $stock : $quant;
        }

        $product_info = Product::findOrFail($id_products);
        $product_price = $product_info->price;
        $total_product_price = $quantify_products * $product_price;


        if (!$replice) {

            $order_new = new OrderEcomm;
            $order_new->ip_order = $ip_order;
            $order_new->id_product = $id_products;
            $order_new->price = $product_price;
            $order_new->amount = $quantify_products;
            $order_new->total = $total_product_price;

            $order_new->save();
        } else {
            OrderEcomm::find($replice->id)->update([
                'amount' => $replice->amount + $quantify_products,
                'total' => $replice->total + $total_product_price,
            ]);
        }
    }

    public function addCart(Request $request)
    {
        $id_products = $request->id_product;
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $product_info = Product::findOrFail($id_products);

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
                } else {
                    $stock = $temStock;
                }

                if ($product_info->kit == 1) {
                    # code...
                    $this->addProductInToCart($key, $value);
                }
            }
            if ($product_info->kit == 1) {
                return redirect()->route('index.cart');
            } else {
                $stock = DB::table('stock')
                    ->where('product_id', $id_products)
                    ->sum('amount');
            }
        } else {

            $stock = DB::table('stock')
                ->where('product_id', $id_products)
                ->sum('amount');
        }

        $replice = OrderEcomm::where('id_product', $id_products)->where('ip_order', $ip_order)->first();


        if (isset($replice->amount) && $replice->amount >= $stock) {
            $quantify_products = 0;
        } else {
            $quantify_products = $request->quant_product > $stock ? $stock : $request->quant_product;
        }

        $product_info = Product::findOrFail($id_products);
        $product_price = $product_info->price;
        $total_product_price = $quantify_products * $product_price;


        if (!$replice) {

            $order_new = new OrderEcomm;
            $order_new->ip_order = $ip_order;
            $order_new->id_product = $id_products;
            $order_new->price = $product_price;
            $order_new->amount = $quantify_products;
            $order_new->total = $total_product_price;

            $order_new->save();
        } else {
            OrderEcomm::find($replice->id)->update([
                'amount' => $replice->amount + $quantify_products,
                'total' => $replice->total + $total_product_price,
            ]);
        }

        return redirect()->route('index.cart');
    }

    public function upAmount($id)
    {
        $order = OrderEcomm::find($id);

        if (!isset($order)) {
            return redirect()->route('index.cart');
        }
        $p = Product::where('id', $order->id_product)->first();

        if ($p->kit != 0) {

            $parts = explode('|', $p->kit_produtos); // Divide a string pelo caractere "|"

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
                } else {
                    $stock = $temStock;
                }
            }
        } else {

            $stock = DB::table('stock')
                ->where('product_id', $order->id_product)
                ->sum('amount');
        }

        $amount = ($order->amount + 1) < $stock ? $order->amount + 1 : $stock;

        $amountUp = OrderEcomm::find($id)->update([
            'total' => $order->total + $order->price,
            'amount' => $amount,
        ]);

        return redirect()->route('index.cart');
    }

    public function downAmount($id)
    {
        $order = OrderEcomm::find($id);

        if (!isset($order)) {
            return redirect()->route('index.cart');
        }

        if ($order->amount > 1) {

            $amountUp = OrderEcomm::find($id)->update([
                'total' => $order->total - $order->price,
                'amount' => $order->amount - 1,
            ]);
        } else {
            if ($order->amount == 1) {
                OrderEcomm::findOrFail($id)->delete();
            }
        }

        return redirect()->route('index.cart');
    }

    public function clearCart()
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];

        $ordersAll = OrderEcomm::where('ip_order', $ip_order)->get();

        foreach ($ordersAll as $order) {

            OrderEcomm::findOrFail($order->id)->delete();
        }

        return redirect()->route('index.cart');
    }

    public function FinalizeShop()
    {
        // VERIFICAR LOG
        @$session_user = session('buyer');

        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);
        $user = null;

        if (count($order_cart) < 1) {
            return redirect()->route('ecomm');
        }

        // if (!$session_user) {
        //     return redirect()->route('page.login.ecomm');
        // }


        $user = session()->get('buyer');
        $countryIp = ["country" => $user->country];
        $countryIpOrder = $user->country;


        $total_order = 0;
        $total_VAT = 0;
        $withoutVAT = 0;
        $priceShippingHome = 0;
        $priceShippingPickup = null;
        $subtotal = 0;
        $totalWeight = 0;
        $paisesAceitos = ShippingPrice::all();
        $todosVats = [];
        $price_shipping = ShippingPrice::where('country', $countryIpOrder)->first();
        $shippingPickup = PickupPoint::where('country', $countryIpOrder)->first();
        $total_tax_add = 0;
        $tax1add = 0;
        $taxadd = 0;
        $typeWeight = '';
        $qv = 0;
        $allSavedTax = Tax::all();
        $idSprodutos = [];

        foreach ($order_cart as $order) {
            $withoutVAT = $withoutVAT + $order->total;
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryIp['country'])->first();
            $findProduct = Product::where('id', $order->id_product)->first();
            array_push($idSprodutos, $order->id_product);
            $order->product_id = $findProduct->id;
            $weightProduct = $findProduct;
            if ($findProduct->qv > 0) {
                $qv += $findProduct->qv * $order->amount;
            }
            $order->price_product = $findProduct->price;
            $total_order = $total_order + $order->total;
            $weightProduct = $weightProduct->weight * $order->amount;
            $totalWeight += $weightProduct;

            if (isset($taxValue) && $taxValue->value > 0) {
                $tax = $taxValue->value;
                $total_order += number_format(($tax / 100) * $order->price, 2, '.', '') * $order->amount;
                $total_VAT += number_format(($tax / 100) * $order->price, 2, '.', '') * $order->amount;
                $priceTax = (($tax / 100) * $order->price);

                // dd($total_VAT);
                $order->priceTax = $priceTax;
            } else {
                $order->priceTax = 0;
            }
        }



        $total_tax_add = 0;

        $total_tax_add = number_format($total_tax_add, 2, '.', '');

        $semtaxa = $priceShippingHome;
        $priceShippingHome += $total_tax_add;
        // dd($priceShippingHome);

        $subtotal = $total_order;
        // dd($subtotal);
        $total_finish = $total_order + $priceShippingHome;

        $format_shipping = number_format($priceShippingHome, 2, ",", ".");

        $total_VAT = number_format($total_VAT, 2, ",", ".");
        $format_price = number_format($total_finish, 2, ",", ".");

        $metodos = [];
        $todosFreteCasa = [];
        $todosFretePickup = [];

        $allPickup = PickupPoint::all();
        $list_product = array();

        foreach ($order_cart as $prodcts) {

            if (!in_array($prodcts->id_product, $list_product)) {
                array_push($list_product, $prodcts->id_product);
            }
        }


        if ($count_order > 0) {
            return view('ecomm.ecomm_finalize', compact('todosFreteCasa', 'todosFretePickup', 'todosVats', 'allSavedTax', 'order_cart', 'qv', 'allPickup', 'subtotal', 'withoutVAT', 'ip_order', 'count_order', 'format_price', 'user', 'total_VAT', 'metodos', 'countryIpOrder', 'priceShippingHome', 'priceShippingPickup', 'format_shipping', 'list_product'));
        } else {
            return redirect()->route('ecomm');
        }
    }

    public function FinalizeShopSmart()
    {
        // VERIFICAR LOG
        @$session_user = session('buyer');

        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);
        $user = null;

        if (count($order_cart) < 1) {
            return redirect()->route('ecomm');
        }

        // if (!$session_user) {
        //     return redirect()->route('page.login.ecomm');
        // }


        $user = session()->get('buyer');
        $countryIp = ["country" => $user->country];
        $countryIpOrder = $user->country;


        $total_order = 0;
        $total_VAT = 0;
        $withoutVAT = 0;
        $priceShippingHome = 0;
        $priceShippingPickup = null;
        $subtotal = 0;
        $totalWeight = 0;
        $paisesAceitos = ShippingPrice::all();
        $todosVats = [];
        $price_shipping = ShippingPrice::where('country', $countryIpOrder)->first();
        $shippingPickup = PickupPoint::where('country', $countryIpOrder)->first();
        $total_tax_add = 0;
        $tax1add = 0;
        $taxadd = 0;
        $typeWeight = '';
        $qv = 0;
        $allSavedTax = Tax::all();
        $idSprodutos = [];

        foreach ($order_cart as $order) {
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryIp['country'])->first();
            $findProduct = Product::where('id', $order->id_product)->first();
            $withoutVAT = $withoutVAT + ($findProduct->premium_price * $order->amount);
            array_push($idSprodutos, $order->id_product);
            $order->product_id = $findProduct->id;
            $weightProduct = $findProduct;
            if ($findProduct->qv > 0) {
                $qv += $findProduct->qv * $order->amount;
            }
            $order->price_product = $findProduct->premium_price;
            $total_order = $total_order + $findProduct->premium_price * $order->amount;
            $weightProduct = $weightProduct->weight * $order->amount;
            $totalWeight += $weightProduct;

            if (isset($taxValue) && $taxValue->value > 0) {
                $tax = $taxValue->value;
                $total_order += number_format(($tax / 100) * $findProduct->premium_price, 2, '.', '') * $order->amount;
                $total_VAT += number_format(($tax / 100) * $findProduct->premium_price, 2, '.', '') * $order->amount;
                $priceTax = (($tax / 100) * $findProduct->premium_price);

                // dd($total_VAT);
                $order->priceTax = $priceTax;
            } else {
                $order->priceTax = 0;
            }
        }


        foreach ($paisesAceitos as $vat) {
            $tt = 0;
            foreach ($order_cart as $order) {
                $taxVv = Tax::where('product_id', $order->id_product)->where('country', $vat->country)->first();
                $findProduct = Product::where('id', $order->id_product)->first();
                if (isset($taxVv) && $taxVv->value > 0) {
                    $tt += number_format(($taxVv->value / 100) * $findProduct->premium_price, 2, '.', '') * $order->amount;
                }
            }
            $todosVats[$vat->country] = $tt;
        }


        if ($totalWeight <= 2 && $totalWeight > 0) {
            $priceShippingHome = $price_shipping->kg2;
            $typeWeight = 'kg2';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg2;
        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $price_shipping->kg5;
            $typeWeight = 'kg5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg5;
        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $price_shipping->kg10;
            $typeWeight = 'kg10';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg10;
        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $price_shipping->kg20;
            $typeWeight = 'kg20';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg20;
        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $price_shipping->kg31_5;
            $typeWeight = 'kg31_5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg31_5;
        }

        $MaxtaxValuesByCountry = Tax::selectRaw('country, MAX(value)/100 as max_value')
            ->whereIn('product_id', array_values($idSprodutos))
            ->groupBy('country')
            ->get()
            ->pluck('max_value', 'country')
            ->toArray();

        // dd($MaxtaxValuesByCountry);

        // $tax1add = Tax::where('country', $countryIp['country'])->max('value');
        $tax1add = $MaxtaxValuesByCountry[$countryIp['country']];
        // dd($tax1add);


        if (isset($tax1add)) {
            # code...
            if ($priceShippingHome > 0) {
                if ($tax1add > 0) {
                    # code...
                    $total_tax_add += floatval($priceShippingHome) * floatval($tax1add);
                }
                // $total_tax_add += 0;
            }
        }

        $total_tax_add = number_format($total_tax_add, 2, '.', '');

        $semtaxa = $priceShippingHome;
        $priceShippingHome += $total_tax_add;
        // dd($priceShippingHome);

        $subtotal = $total_order;
        // dd($subtotal);
        $total_finish = $total_order + $priceShippingHome;

        $format_shipping = number_format($priceShippingHome, 2, ",", ".");

        $total_VAT = number_format($total_VAT, 2, ",", ".");
        $format_price = number_format($total_finish, 2, ",", ".");

        $url = '';

        $metodos = [];
        $todosFreteCasa = [];
        $todosFretePickup = [];

        $allCountry = ShippingPrice::orderBy('country', 'ASC')->get();
        foreach ($allCountry as $value) {
            $value['priceShipping'] = $value->{"$typeWeight"};

            foreach ($MaxtaxValuesByCountry as $key => $valor) {
                if ($key == $value->country) {
                    $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};
                    $n = number_format($n, 2, '.', '');
                    $todosFreteCasa[$key] = $n;
                }
            }
        }

        $allPickup = PickupPoint::all();
        foreach ($allPickup as $value) {
            $value['priceShipping'] = $value->{"$typeWeight"};

            foreach ($MaxtaxValuesByCountry as $key => $valor) {
                if ($key == $value->country) {
                    $n = $value->{"$typeWeight"} * $valor + $value->{"$typeWeight"};
                    $n = number_format($n, 2, '.', '');
                    $todosFretePickup[$value->country_code] = $n;
                }
            }
        }
        $list_product = array();

        foreach ($order_cart as $prodcts) {

            if (!in_array($prodcts->id_product, $list_product)) {
                array_push($list_product, $prodcts->id_product);
            }
        }

        if ($count_order > 0) {
            return view('ecomm.ecomm_finalize_smart', compact('list_product', 'todosFreteCasa', 'todosFretePickup', 'todosVats', 'MaxtaxValuesByCountry', 'allSavedTax', 'order_cart', 'qv', 'allPickup', 'subtotal', 'withoutVAT', 'ip_order', 'count_order', 'format_price', 'user', 'total_VAT', 'metodos', 'countryIpOrder', 'priceShippingHome', 'priceShippingPickup', 'format_shipping', 'allCountry'));
        } else {
            return redirect()->route('ecomm');
        }
    }

    public function deleteCart($id)
    {
        OrderEcomm::findOrFail($id)->delete();

        return redirect()->route('index.cart');
    }

    public function PageLogin()
    {
        // VERIFICA SE UMA SESSÃO JÁ EXISTE
        if (session()->has('buyer')) {
            return redirect()->route('page.panel.ecomm');
        }

        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        return view('ecomm.ecomm_login', compact('count_order'));
    }

    public function PagePanel()
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');
            $lastOrder = EcommOrders::where('id_user', $user->id)->latest()->first();
            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            if (isset($lastOrder)) {
                $order = EcommOrders::where('id_user', $user->id)->where('number_order', $lastOrder->number_order)->get();
                $invoiceFak = InvoicesFakturoid::where('number_order', $lastOrder->number_order)->first();

                $order_number = $lastOrder->number_order;

                foreach ($order as $value) {
                    $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                    $value['payment'] = $payment->status;
                    $value['total'] = $payment->total_price;
                }

                $client = new \GuzzleHttp\Client();

                $url = '';

                $metodos = [];

                return view('ecomm.ecomm_panel', compact('invoiceFak', 'user', 'order', 'order_number', 'count_order', 'metodos'));
            }

            return view('ecomm.ecomm_panel', compact('user', 'count_order'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function PageOrdersPanel()
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');

            $orderIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $user->id)
                ->groupBy('number_order')
                ->pluck('id');

            $order = EcommOrders::whereIn('id', $orderIds)
                ->orderBy('created_at', 'DESC')
                ->paginate(15);

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            foreach ($order as $value) {
                $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                $value['payment'] = $payment->status ?? '';
                $value['total'] = $payment->total_price ?? '';
                $invoiceFak = InvoicesFakturoid::where('number_order', $value->number_order)->first();
                $value['invoiceFak'] = isset($invoiceFak);
            }
            return view('ecomm.ecomm_orders_panel', compact('user', 'order', 'count_order'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function PageOrdersDetalPanel($id)
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');

            $first_order = EcommOrders::where('id', $id)->first();
            if (!isset($first_order)) {
                abort(404);
            }
            $order_number = $first_order->number_order;
            $order = EcommOrders::where('number_order', $order_number)->get();

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);


            foreach ($order as $value) {
                $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                $value['payment'] = $payment->status;
                $value['total_price'] = $payment->total_price;
            }

            $client = new \GuzzleHttp\Client();

            $url = '';

            $metodos = [];
            $invoiceFak = InvoicesFakturoid::where('number_order', $order_number)->first();

            return view('ecomm.ecomm_detal_orders_panel', compact('invoiceFak', 'user', 'order', 'count_order', 'order_number', 'metodos'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function PageSettingsPanel()
    {
        $userLogin = session()->get('buyer');
        if (!isset($userLogin)) {
            return redirect()->route('page.login.ecomm');
        }
        $user = EcommRegister::where('id', $userLogin->id)->first();
        session()->put('buyer', $user);

        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        $allCountry = ShippingPrice::orderBy('country', 'ASC')->get();

        $userReferral = User::where('id', $user->recommendation_user_id)->first();
        $addressBilling = AddressBilling::where('user_id', $user->id)->where('backoffice', 0)->first();
        $AddressSecondary = AddressSecondary::where('id_user', $user->id)->where('backoffice', 0)->first();

        return view('ecomm.ecomm_settings', compact('user', 'count_order', 'allCountry', 'userReferral', 'addressBilling', 'AddressSecondary'));
    }

    public function RecoverPassword()
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        return view('ecomm.ecomm_recover_password', compact('count_order'));
    }

    public function RegisterUserReferral($id)
    {
        $userBack = User::where('id', $id)->first();
        if (!isset($userBack)) {
            return redirect()->route('.welcome');
        }

        $cookie = Cookie::make('referral_ecomm', $userBack->login, 1440);
        return redirect()->route('register.user.ecomm')->withCookie($cookie);
    }

    public function RegisterUser(Request $request)
    {

        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        $allCountry = ShippingPrice::orderBy('country', 'ASC')->get();

        if ($request->hasCookie('referral_ecomm')) {
            $userBack = $request->cookie('referral_ecomm');
            return view('ecomm.ecomm_register', compact('count_order', 'allCountry', 'userBack'));
        } else {
            return view('ecomm.ecomm_register', compact('count_order', 'allCountry'));
        }
    }

    public function pageReplicePass()
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
        $count_order = count($order_cart);

        return view('ecomm.ecomm_replice_password', compact('count_order'));
    }

    public function metodosHabilitadosComgate($metodo)
    {

        return [];
    }

    public function getInvoiceInFakturoid($order)
    {
        $f = new FakturoidClient('intermodels', 'juraj@Pribonasorte.eu', 'd2f384a3e232c5fbeb28c8e2a49435573561905f', 'PHPlib <juraj@Pribonasorte.eu>');

        $existisOrder = EcommOrders::where('number_order', $order)->first();
        if (!isset($existisOrder)) {
            abort(404);
        }

        $invoiceFak = InvoicesFakturoid::where('number_order', $order)->first();
        if (!isset($invoiceFak)) {
            // dd($invoiceFak);
            return false;
        }

        $invoiceId = $invoiceFak->invoice_id;
        $response = $f->getInvoicePdf($invoiceId);
        $data = $response->getBody();

        return $data;
    }

    public function invoicePDF($id)
    {
        $existsFakturoid = $this->getInvoiceInFakturoid($id);
        if ($existsFakturoid != false) {
            return response($existsFakturoid)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $id . '.pdf"');
        }

        $ecomm_order = EcommOrders::where('number_order', $id)->get();

        if (count($ecomm_order) < 1) {
            abort(404);
        }

        $client_corporate = null;

        if ($ecomm_order[0]->client_backoffice == 0) {
            $client = EcommRegister::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->phone ?? '';
            $client_corporate = $client->id_corporate ?? null;
            $client_address = $client->address;
            $client_postcode = $client->zip . ' ' . ($client->city ?? '');
        } else {
            $client = User::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->cell ?? '';
            $client_address = $client->address1;
            $client_postcode = $client->postcode . ' ' . ($client->city ?? '');
        }

        $client_name = $client->name . ' ' . ($client->last_name ?? '');
        $payment = PaymentOrderEcomm::where('number_order', $id)->first();

        $metodo_pay = $this->metodosHabilitadosComgate($payment->payment_method);
        if (!$metodo_pay) {
            $metodo_pay = $payment->payment_method;
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
            'paid_data' => $payment->updated_at,
            'total_order' => $payment->total_price,
            'mt_shipp' => $ecomm_order[0]->method_shipping,
            'total_vat' => 0,
            'total_shipping' => 0,
        ];

        // if ($ecomm_order[0]->client_backoffice == 0 || $ecomm_order[0]->client_backoffice == null) {
        if (
            $ecomm_order[0]->method_shipping == null
            || $ecomm_order[0]->method_shipping == '1'
            || $ecomm_order[0]->method_shipping == 'home'
            || $ecomm_order[0]->method_shipping == 'home1'
            || $ecomm_order[0]->method_shipping == 'pickup'
        ) {
            $data['address'] = $client->address ?? $client->address1 ?? '';
            $data['zip'] = $client->zip ?? $client->postcode ?? '';
            $data['neighborhood'] = $client->neighborhood ?? $client->area_residence ?? $client->city ?? '';
            $data['number'] = $client->number ?? $client->number_residence ?? '';
            $data['complement'] = isset($client->complement) ? $client->complement : '';
            $data['country'] = $client->country;


            if ($ecomm_order[0]->method_shipping == 'pickup') {
                $chosenPickup = ChosenPickup::where('number_order', $id)->first();

                if (isset($chosenPickup)) {
                    $data['country'] = $chosenPickup->country;
                    $data['method_shipping'] = "pickup - " . $chosenPickup->name . ", " . $chosenPickup->street . " " . $chosenPickup->city;
                } else {
                    $data['method_shipping'] = 'pickup';
                }
            } else {
                $data['method_shipping'] = 'Delivery in Home';
            }
        } else if ($ecomm_order[0]->method_shipping == 'home2') {
            $address2 = AddressSecondary::where('id_user', $ecomm_order[0]->id_user)->where('backoffice', $ecomm_order[0]->client_backoffice)->first();
            $data['address'] = $address2->address;
            $data['zip'] = $address2->zip;
            $data['neighborhood'] = $address2->neighborhood;
            $data['number'] = $address2->number;
            $data['complement'] = $address2->complement;
            $data['country'] = $address2->country;
            $data['method_shipping'] = 'Delivery in Home';
        }
        // }

        $total_price_product = 0;
        $qv = 0;
        $pesoTotal = 0;

        foreach ($ecomm_order as $value) {
            $data['total_vat'] += $value->total_vat;
            $qv += $value->qv;

            $product = Product::where('id', $value->id_product)->first();

            $price = $value->total / $value->amount;

            if ($value->client_backoffice == 0) {
                if ($value->smartshipping == 1) {
                    $price = $value->total / $value->amount;
                }
            }

            $data['products'][$value->id_product] = [
                'name' => $product->name,
                'amount' => $value->amount,
                'unit' => $price,
                'total' => $value->amount * $price + $value->total_vat,
                'porcentVat' => $this->trazerPorcentProduto($product->id, $data['country'], $value->id),
                'vat' => $value->total_vat
            ];



            $pesoTotal += $product->weight * $value->amount;
            $total_price_product += $value->amount * $price;
        }

        $data['total_price_product'] = $total_price_product;
        $data['total_shipping'] = $data['total_order'] - $total_price_product - $data['total_vat'];
        $data['qv'] = $qv;

        if (isset($ecomm_order[0]->total_shipping)) {
            $data['total_shipping'] = $ecomm_order[0]->total_shipping;
        }


        $freteSemVat = $this->trazerPrecoFrete($data['country'], $pesoTotal);
        // dd($freteSemVat);

        if ($ecomm_order[0]->method_shipping == 'pickup') {
            $data['freteSemVat'] = $freteSemVat['pickup'];
        } else {
            $data['freteSemVat'] = $freteSemVat['home'];
        }

        // dd($data);
        return view('pdf.invoiceProduct', compact('data'));
    }

    public function criaXML()
    {

        $xml = new \SimpleXMLElement('<root></root>');
        $xml->addChild('element1', 'conteúdo1');
        $xml->addChild('element2', 'conteúdo2');

        $response = response($xml->asXML());

        $response->header('Content-Type', 'application/xml');

        $response->header('Content-Disposition', 'attachment; filename=seu-arquivo.xml');

        return $response;
    }

    public function trazerPorcentProduto($id_p, $pais, $order)
    {
        $ecomm_order = EcommOrders::where('id', $order)->first();
        if (isset($ecomm_order->vat_product_percentage) && $ecomm_order->vat_product_percentage != null) {
            return $ecomm_order->vat_product_percentage;
        }

        $price_shipping = ShippingPrice::where('country_code', $pais)->orWhere('country', $pais)->first();
        $p = $price_shipping->country;
        $taxValue = Tax::where('product_id', $id_p)->where('country', $p)->first();
        return $taxValue->value;
    }

    public function newInvoicePDF($id)
    {
        // dd('oi');
        $ecomm_order = EcommOrders::where('number_order', $id)->get();

        if (count($ecomm_order) < 1) {
            abort(404);
        }

        $client_corporate = null;

        if ($ecomm_order[0]->client_backoffice == 0) {
            $client = EcommRegister::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->phone ?? '';
            $client_corporate = $client->id_corporate ?? null;
        } else {
            $client = User::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->cell ?? '';
        }

        $client_name = $client->name . ' ' . $client->last_name ?? '';
        $payment = PaymentOrderEcomm::where('number_order', $id)->first();

        $data = [
            "client_corporate" => $client_corporate,
            "client_id" => $client->id,
            "client_name" => $client_name,
            "client_email" => $client->email,
            "client_cell" => $cell,
            "order" => $id,
            'paid_data' => $payment->updated_at,
            'total_order' => $payment->total_price,
            'mt_shipp' => $ecomm_order[0]->method_shipping,
            'total_vat' => 0,
            'total_shipping' => 0,
        ];

        // if ($ecomm_order[0]->client_backoffice == 0 || $ecomm_order[0]->client_backoffice == null) {
        if (
            $ecomm_order[0]->method_shipping == null
            || $ecomm_order[0]->method_shipping == '1'
            || $ecomm_order[0]->method_shipping == 'home'
            || $ecomm_order[0]->method_shipping == 'home1'
            || $ecomm_order[0]->method_shipping == 'pickup'
        ) {
            $data['address'] = $client->address ?? $client->address1 ?? '';
            $data['zip'] = $client->zip ?? $client->postcode ?? '';
            $data['neighborhood'] = $client->neighborhood ?? $client->area_residence ?? $client->city ?? '';
            $data['number'] = $client->number ?? $client->number_residence ?? '';
            $data['complement'] = isset($client->complement) ? $client->complement : '';
            $data['country'] = $client->country;


            if ($ecomm_order[0]->method_shipping == 'pickup') {
                $chosenPickup = ChosenPickup::where('number_order', $id)->first();

                if (isset($chosenPickup)) {
                    $data['method_shipping'] = "pickup - " . $chosenPickup->name . ", " . $chosenPickup->street . " " . $chosenPickup->city;
                } else {
                    $data['method_shipping'] = 'pickup';
                }
            } else {
                $data['method_shipping'] = 'Delivery in Home';
            }
        } else if ($ecomm_order[0]->method_shipping == 'home2') {
            $address2 = AddressSecondary::where('id_user', $ecomm_order[0]->id_user)->where('backoffice', $ecomm_order[0]->client_backoffice)->first();
            $data['address'] = $address2->address;
            $data['zip'] = $address2->zip;
            $data['neighborhood'] = $address2->neighborhood;
            $data['number'] = $address2->number;
            $data['complement'] = $address2->complement;
            $data['country'] = $address2->country;
            $data['method_shipping'] = 'Delivery in Home';
        }
        // }

        $total_price_product = 0;
        $qv = 0;

        foreach ($ecomm_order as $value) {
            $data['total_vat'] += $value->total_vat;
            $qv += $value->qv;

            $product = Product::where('id', $value->id_product)->first();
            $price = $value->total / $value->amount;
            $data['products'][$value->id_product] = [
                'name' => $product->name,
                'amount' => $value->amount,
                'unit' => $price,
                'total' => $value->amount * $price + $value->total_vat,
                'vat' => $value->total_vat
            ];

            $total_price_product += $value->amount * $price;
        }

        $data['total_price_product'] = $total_price_product;
        $data['total_shipping'] = $data['total_order'] - $total_price_product - $data['total_vat'];
        $data['qv'] = $qv;

        if (isset($ecomm_order[0]->total_shipping)) {
            $data['total_shipping'] = $ecomm_order[0]->total_shipping;
        }

        // dd($data);
        return view('pdf.invoiceProduct', compact('data'));
    }

    public function trazerPrecoFrete($pais, $totalWeight)
    {

        $price_shipping = ShippingPrice::where('country_code', $pais)->orWhere('country', $pais)->first();
        $shippingPickup = PickupPoint::where('country_code', $pais)->orWhere('country', $pais)->first();
        $priceShippingHome = 0;
        $priceShippingPickup = 0;

        $typeWeight = '';


        if ($totalWeight <= 2 && $totalWeight > 0) {
            $priceShippingHome = $price_shipping->kg2;
            $typeWeight = 'kg2';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg2;
        } else if ($totalWeight > 2 && $totalWeight <= 5) {
            $priceShippingHome = $price_shipping->kg5;
            $typeWeight = 'kg5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg5;
        } else if ($totalWeight > 5 && $totalWeight <= 10) {
            $priceShippingHome = $price_shipping->kg10;
            $typeWeight = 'kg10';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg10;
        } else if ($totalWeight > 10 && $totalWeight <= 20) {
            $priceShippingHome = $price_shipping->kg20;
            $typeWeight = 'kg20';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg20;
        } else if ($totalWeight > 20 && $totalWeight <= 31.5) {
            $priceShippingHome = $price_shipping->kg31_5;
            $typeWeight = 'kg31_5';

            if (isset($shippingPickup))
                $priceShippingPickup = $shippingPickup->kg31_5;
        }

        return [
            "home" => $priceShippingHome,
            "pickup" => $priceShippingPickup
        ];
    }

    public function orderslipPDF($id)
    {
        $ecomm_order = EcommOrders::where('number_order', $id)->get();

        if (count($ecomm_order) < 1) {
            abort(404);
        }

        if ($ecomm_order[0]->client_backoffice == 0) {
            $client = EcommRegister::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->phone ?? '';
        } else {
            $client = User::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->cell ?? '';
        }

        $client_name = $client->name . ' ' . $client->last_name ?? '';
        $payment = PaymentOrderEcomm::where('number_order', $id)->first();

        $data = [
            "client_name" => $client_name,
            "client_tel" => $cell,
            "client_email" => $client->email,
            "order" => $id,
            'paid_data' => $payment->updated_at,
            'total_order' => $payment->total_price,
            'mt_shipp' => $ecomm_order[0]->method_shipping,
            'total_vat' => 0,
            'total_shipping' => 0,
        ];

        // if ($ecomm_order[0]->client_backoffice == 0 || $ecomm_order[0]->client_backoffice == null) {
        if (
            $ecomm_order[0]->method_shipping == null
            || $ecomm_order[0]->method_shipping == '1'
            || $ecomm_order[0]->method_shipping == 'home'
            || $ecomm_order[0]->method_shipping == 'home1'
            || $ecomm_order[0]->method_shipping == 'pickup'
        ) {
            $data['address'] = $client->address ?? $client->address1 ?? '';
            $data['zip'] = $client->zip ?? $client->postcode ?? '';
            $data['neighborhood'] = $client->neighborhood ?? $client->area_residence ?? $client->city ?? '';
            $data['number'] = $client->number ?? $client->number_residence ?? '';
            $data['complement'] = isset($client->complement) ? $client->complement : '';
            $data['country'] = $client->country;


            if ($ecomm_order[0]->method_shipping == 'pickup') {
                $chosenPickup = ChosenPickup::where('number_order', $id)->first();

                if (isset($chosenPickup)) {
                    $data['method_shipping'] = "pickup - " . $chosenPickup->name . ", " . $chosenPickup->street . " " . $chosenPickup->city;
                } else {
                    $data['method_shipping'] = 'pickup';
                }
            } else {
                $data['method_shipping'] = 'Delivery in Home';
            }
        } else if ($ecomm_order[0]->method_shipping == 'home2') {
            $address2 = AddressSecondary::where('id_user', $ecomm_order[0]->id_user)->where('backoffice', $ecomm_order[0]->client_backoffice)->first();
            $data['address'] = $address2->address;
            $data['zip'] = $address2->zip;
            $data['neighborhood'] = $address2->neighborhood ?? '';
            $data['number'] = $address2->number;
            $data['complement'] = $address2->complement ?? '';
            $data['country'] = $address2->country;
            $data['method_shipping'] = 'Delivery in Home';
        }
        // }

        $total_price = 0;
        $qv = 0;
        $quant_products_box = 0;

        foreach ($ecomm_order as $value) {
            $data['total_vat'] += $value->total_vat;
            $qv += $value->qv;

            $product = Product::where('id', $value->id_product)->first();
            $price = $value->total / $value->amount;
            $data['products'][$value->id_product] = [
                'name' => $product->name,
                'amount' => $value->amount,
                'unit' => $price,
                'total' => $value->amount * $price
            ];

            if ($product->kit != 0) {
                $parts = explode('|', $product->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                    $separado[$id_produto] = $quantidade;

                    $quant_products_box += ($value->amount * $quantidade);
                }
            } else {
                $quant_products_box += $value->amount;
            }

            $total_price += $value->amount * $price;
        }

        $data['total_shipping'] = $data['total_order'] - $total_price - $data['total_vat'];
        $data['qv'] = $qv;
        $data['quant_products_box'] = $quant_products_box;

        // dd($data);

        if (isset($ecomm_order[0]->total_shipping)) {
            $data['total_shipping'] = $ecomm_order[0]->total_shipping;
        }

        return view('pdf.orderslipProduct', compact('data'));
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
                return view('ecomm.tracking', compact('orderNumber', 'info'));
            }
        }

        return view('ecomm.tracking', compact('orderNumber'));
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

    public function cancelSmartshipping(Request $request)
    {

        try {
            // dd($request);
            $order = $request->order;
            $reason = $request->motivo;

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
            $nCancel->reason = $reason;
            $nCancel->save();

            $log = new CustomLog;
            $log->content = "$reason";
            $log->user_id = $user_id;
            $log->operation = "$order -cancel smartshipping";
            $log->controller = "EcommController";
            $log->http_code = 200;
            $log->route = "cancel.smartshipping";
            $log->status = "success";
            $log->save();
        } catch (\Throwable $th) {

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function chooseDayForSmartshipping(Request $request)
    {
        // dd($request);

        try {

            $order = $request->order;
            $day = $request->day;

            $ecommOrders = EcommOrders::where('number_order', $order)->first();

            if (!isset($ecommOrders)) {
                return redirect()->back();
            }

            if ($ecommOrders->client_backoffice == 1) {
                $user = User::where('id', $ecommOrders->id_user)->first();
            } else {
                $user = EcommRegister::where('id', $ecommOrders->id_user)->first();
            }

            $user_id = $user->id;

            $daySaved = BestDatePaymentSmartshipping::where('number_order', $order)->first();
            if (isset($daySaved)) {
                $daySaved->day = $day;
                $daySaved->save();
            } else {
                $choosed = new BestDatePaymentSmartshipping;
                $choosed->user_id = $user_id;
                $choosed->number_order = $order;
                $choosed->day = $day;
                $choosed->save();
            }

            $log = new CustomLog;
            $log->content = "Choose day smart";
            $log->user_id = $user_id;
            $log->operation = "$order -Choose day smart";
            $log->controller = "EcommController";
            $log->http_code = 200;
            $log->route = "cancel.smartshipping";
            $log->status = "success";
            $log->save();
        } catch (\Throwable $th) {
            return redirect()->back();
            // dd($th);
        }
        return redirect()->back();
    }

    public function PageSmartshipReport()
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');

            $orderIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $user->id)
                ->where('smartshipping', 1)
                ->groupBy('number_order')
                ->pluck('id');

            $order = EcommOrders::whereIn('id', $orderIds)
                ->orderBy('created_at', 'DESC')
                ->paginate(15);

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            foreach ($order as $value) {
                $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                $value['payment'] = $payment->status ?? '';
                $value['total'] = $payment->total_price ?? '';
                $invoiceFak = InvoicesFakturoid::where('number_order', $value->number_order)->first();
                $value['invoiceFak'] = isset($invoiceFak);
            }

            $reportSmart = true;
            return view('ecomm.ecomm_orders_panel', compact('reportSmart', 'user', 'order', 'count_order'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function PageQVReport()
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');

            $orderIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $user->id)
                ->where('smartshipping', 1)
                ->groupBy('number_order')
                ->pluck('id');

            $order = EcommOrders::whereIn('id', $orderIds)
                ->orderBy('created_at', 'DESC')
                ->paginate(15);

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            foreach ($order as $value) {
                $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                $value['payment'] = $payment->status ?? '';
                $value['total'] = $payment->total_price ?? '';
                $value['qvtt'] = EcommOrders::where('number_order', $value->number_order)->sum('qv');
                $invoiceFak = InvoicesFakturoid::where('number_order', $value->number_order)->first();
                $value['invoiceFak'] = isset($invoiceFak);
            }

            $reportSmart = true;
            return view('ecomm.ecomm_qvreport', compact('reportSmart', 'user', 'order', 'count_order'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function PageInvoicesReport()
    {
        if (session()->has('buyer')) {

            $user = session()->get('buyer');

            $orderIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $user->id)
                ->where('smartshipping', 1)
                ->groupBy('number_order')
                ->pluck('id');

            $order = EcommOrders::whereIn('id', $orderIds)
                ->orderBy('created_at', 'DESC')
                ->paginate(15);

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            foreach ($order as $value) {
                $payment = PaymentOrderEcomm::where('id', $value->id_payment_order)->first();
                $value['payment'] = $payment->status ?? '';
                $value['total'] = $payment->total_price ?? '';
                $value['qvtt'] = EcommOrders::where('number_order', $value->number_order)->sum('qv');
                $invoiceFak = InvoicesFakturoid::where('number_order', $value->number_order)->first();
                $value['invoiceFak'] = isset($invoiceFak);
            }

            $reportSmart = true;
            return view('ecomm.ecomm_invoices', compact('reportSmart', 'user', 'order', 'count_order'));
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }
}
