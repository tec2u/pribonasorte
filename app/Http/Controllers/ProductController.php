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

class ProductController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $adesao = !$user->getAdessao($user->id); //verifica se ja tem adesão para liberar os outros produtos
        //$adesao = true;

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

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

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
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        } else {
            return view('product.products', compact('products', 'adesao', 'user', 'countPackages'));
        }
    }

    public function detail($productid)
    {
        $user = User::find(Auth::id());
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {
            $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->where('id_product', $productid)->first();

            if (!isset($productsByCountry))
                return abort(404);

            $product = Product::where('id', '=', $productid)->first();
        } else {

            $product = Product::where('id', '=', $productid)->first();
        }


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

        if (!isset($user_id)) {
            $user_id = Auth::id();
        }

        $user = User::find($user_id)->id;
        $user_cart = CartOrder::where('id_user', '=', $user)->get();

        foreach ($user_cart as $product_item) {
            CartOrder::find($product_item->id)->delete();
        }

        return redirect()->route('packages.cart_buy');
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


        $withoutVAT = $subtotal - $total_VAT;
        $semVat = $withoutVAT;

        $withoutVAT = number_format($withoutVAT, 2, ',', '.');
        $total_VAT = number_format($total_VAT, 2, ',', '.');
        // dd($subtotal);

        return view('cart_buy', compact('btnSmart', 'todosFreteCasa', 'todosFretePickup', 'semVat', 'todosVats', 'MaxtaxValuesByCountry', 'cv', 'qv', 'allPickup', 'shippingPickup', 'countryes', 'user_cart', 'count_itens', 'subtotal', 'countPackages', 'total_VAT', 'metodos', 'priceShippingHome', 'priceShippingPickup', 'withoutVAT', 'user'));
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

    public function cartFinalize(Request $request)
    {
        // dd($request);

        $cart = CartOrder::where('id_user', User::find(Auth::id())->id)->get();
        if (count($cart) < 1) {
            return redirect()->route('packages.cart_buy');
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

        $n_order = $this->genNumberOrder();

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
            if (
                empty($request->phone) ||
                empty($request->zip) ||
                empty($request->address) ||
                empty($request->number) ||
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

        if ($request->methodPayment == 'BTC' || $request->methodPayment == 'ETH') {


            $responseData = $this->payCrypto($request, $price, $request->methodPayment);

            if (!isset($responseData->invoice_id)) {
                return redirect()->back();
            }

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
            $responseData = $this->payComission($request, $price, $n_order);

            if ($responseData == false) {
                return redirect()->back()->with('error', 'insufficient comission ');
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
            $responseData = $this->payComgate($request, $newPrice, $n_order, $request->methodPayment, Auth::id());

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

        // dd($data);

        if (isset($responseData)) {
            $payment = $this->createRegisterPayment($data);
            $data['newPayment'] = $payment->id;
            $this->createRegisterEcommOrder($data);

            session()->put('redirect_buy', 'admin');

            if ($request->methodPayment == 'comission') {
                $this->sendPostBonificacao($data["order"], 1);
            }

            return redirect()->away($data['url']);

        } else {
            return redirect()->back();
        }
    }

    public function createRegisterEcommOrder($data, $user_id = null)
    {
        // dd($data);
        if (!isset($user_id)) {
            $user_id = Auth::id();
            $userApp = 0;
        } else {
            $userApp = 1;
        }

        $user = User::find($user_id)->id;
        $user_country = User::find($user_id)->country;
        $order_cart = CartOrder::where('id_user', '=', $user)->get();
        $usuario = User::find($user_id);

        $maiorVat = 0;

        if (count($order_cart) < 1 && $userApp == 0) {
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
        // dd($order_cart);
        // clear cart
        $this->clearCartBuy($user_id);

    }

    public function createRegisterPayment($data, $user_id = null)
    {

        if (!isset($user_id)) {
            $user_id = Auth::id();
        }

        $user = User::find($user_id)->id;
        $order_cart = CartOrder::where('id_user', '=', $user)->get();

        if (count($order_cart) < 1) {
            // $this->clearCartBuy();
            return redirect()->route('packages.packagelog');
        }

        $newPayment = new PaymentOrderEcomm;
        $newPayment->id_user = User::find($user_id)->id;
        $newPayment->id_payment_gateway = $data["id_payment"];
        $newPayment->id_invoice_trans = $data["id_invoice_trans"];
        $newPayment->status = $data["status"];
        $newPayment->total_price = $data["total_price"];
        $newPayment->number_order = $data['order'];
        $newPayment->payment_method = $data["method"];

        if ($data["method"] == 'Admin' || $data["method"] == 'admin') {
            $newPayment->order_corporate = 1;
        }

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



        $url = 'https://lifeprosper.eu/public/compensacao/bonificacao.php';

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
        $name = "Lifeprosper";

        $paymentConfig = [
            "api_url" => "https://crypto.binfinitybank.com/packages/wallets/notify",
            "email" => 'lifeprosper@tec2u.com.br',
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

    public function payComgate(Request $request, $newPrice, $n_order, $methodPayment, $user_id = null, $recorrente = false)
    {
        if (!isset($user_id)) {
            $user_id = Auth::id();
        }

        $dominio = $request->getHost();
        if (strtolower($dominio) == 'lifeprosper.eu') {
            $test = 'false';
        } else {
            $test = 'false';
        }

        $url = 'https://payments.comgate.cz/v1.0/create';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => str_replace('.', '', $newPrice),
            'curr' => 'EUR',
            'label' => "Order $n_order",
            'email' => User::find($user_id)->email,
            'refId' => $n_order,
            'method' => "$methodPayment",
            'prepareOnly' => 'true',
            'test' => "$test",
            'lang' => 'en',
            'initRecurring' => "$recorrente"
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


        $responseData = $this->payComgate($request, $newPrice, $n_order, 'CARD_CZ_CSOB_2', Auth::id(), true);

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
