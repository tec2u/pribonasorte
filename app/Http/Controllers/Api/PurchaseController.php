<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Models\AddressSecondary;
use App\Models\CartOrder;
use App\Models\ChosenPickup;
use App\Models\IpAccessApi;
use App\Models\Package;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\ProductByCountry;
use App\Models\ShippingPrice;
use App\Models\Tax;
use App\Models\TaxPackage;
use App\Models\User;
use App\Traits\ApiReports;
use App\Traits\ApiUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use ApiUser;
    use ApiReports;
    public function packages(Request $request)
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
            $ipRequest->operation = "api/app/get/packages";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = Package::orderBy('id', 'ASC')->where('activated', 1)->paginate(9);

            foreach ($data as $p) {
                $p->img = asset("/img/packages/" . $p->img);
            }


            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }


    public function package(Request $request)
    {
        try {

            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $validatedData = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/package";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $package = Package::orderBy('id', 'ASC')->where('activated', 1)->where('id', $request->id)->first();

            if (!isset($package)) {
                return response()->json(['error' => "Package not found"]);
            }

            $package->img = asset("/img/packages/" . $package->img);

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

            $package->vat = number_format($vatValue, 2, '.', '');
            $package->total_value = number_format($total_value, 2, '.', '');

            $data = [
                "methods" => [
                    "Credit Card" => "CARD_CZ_CSOB_2",
                    "Bitcoin" => "BTC"
                ],
                "values" => [
                    "package_price" => $package->price,
                    "vat_price" => $package->vat,
                    "total_price" => $package->total_value,
                ],
                "package" => $package,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function buyPackage(Request $request)
    {
        try {

            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $validatedData = Validator::make($request->all(), [
                'id' => 'required|numeric',
                'method' => 'required|string|in:BTC,CARD_CZ_CSOB_2',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/buy/package";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $package = Package::orderBy('id', 'ASC')->where('activated', 1)->where('id', $request->id)->first();

            if (!isset($package)) {
                return response()->json(['error' => "Package not found"]);
            }

            $package->img = asset("/img/packages/" . $package->img);

            $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
            if ($user->country == $countryUser->country_code) {
                $user->country = $countryUser->country;
                $user->save();
            }

            $vatPercent = TaxPackage::where('package_id', $package->id)->where('country', $countryUser->country)->first();

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

            $total_value = floatval(($vatValue + $package->price));
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

            $method = $request->method;
            $packageController = new PackageController;
            $urlRedirect = null;

            if ($method == 'BTC') {
                $packageOrder = $packageController->createOrder($user->id, $package, '', '', null, null, $price, $method, $percentVat);

                $crypto = $packageController->payCrypto(substr(str_replace(' ', '', $package->name), 0, 15), $price);
                $codepayment = $crypto->id;
                $invoiceid = $crypto->invoice_id;
                $wallet_OP = $crypto->address;


                $packageController->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, $wallet_OP);

                $urlRedirect = ($crypto->url);

            } else if ($method == 'CARD_CZ_CSOB_2') {
                $packageOrder = $packageController->createOrder($user->id, $package, '', '', null, null, $price, $method, $percentVat);

                $comgate = $packageController->payComgate($request, $newPrice, substr(str_replace(' ', '', $package->name), 0, 15), $method, $packageOrder->id, $user);
                $codepayment = $comgate['transId'];
                $invoiceid = $comgate['transId'];

                $packageController->editOrderPackage($packageOrder->id, $codepayment, $invoiceid, null);



                $urlRedirect = ($comgate['redirect']);
            }

            $data = [
                "url" => $urlRedirect,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function getProducts(User $user, $select = ['id', 'name', 'img_1', 'qv', 'cv', 'backoffice_price', 'description'])
    {
        $countryUser = ShippingPrice::where('country', $user->country)->orWhere('country_code', $user->country)->first();
        $productsByCountry = ProductByCountry::where('id_country', $countryUser->id)->get('id_product');

        if (count($productsByCountry) > 0) {

            $data = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->whereIn('id', $productsByCountry)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->select($select)
                ->paginate(9);

        } else {
            $data = Product::orderBy('sequence', 'asc')->where('activated', 1)
                ->where(function ($query) {
                    $query->where('availability', 'internal')
                        ->orWhere('availability', 'both');
                })
                ->select($select)
                ->paginate(9);
        }

        // $data = Product::orderBy('sequence', 'ASC')->where('kit', 1)->where('activated', 1)->select('id', 'name', 'img_1', 'qv', 'cv', 'backoffice_price', 'description')->paginate(9);

        foreach ($data as $p) {
            $stock = $p->Stock($p);

            if ($stock > 0 && $p->kit == 1) {
                $stock = 1;
            }

            $tax = Tax::where('product_id', $p->id)->where('country', $user->country)->first();

            if (isset($tax)) {
                $product_vat = $tax->value;
            } else {
                $product_vat = 0.00;
            }

            if ($user->pay_vat == 0) {
                $product_vat = 0.00;
            }

            $p->percent_vat = $product_vat;
            $p->stock = $stock;
            $p->img_1 = asset("/img/products/" . $p->img_1);
        }

        if (count($data) < 1) {
            return response()->json(['error' => "Products not found"]);
        }

        $paginationLinks = [
            'first' => $data->url(1),
            'last' => $data->url($data->lastPage()),
            'prev' => $data->previousPageUrl(),
            'next' => $data->nextPageUrl(),
            'current' => $data->url($data->currentPage()),
        ];

        if (count($data) > 0) {
            return [
                'data' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'links' => $paginationLinks
                ]
            ];
        } else {
            return [];
        }
    }

    public function products(Request $request)
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
            $ipRequest->operation = "api/app/get/products";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            return response()->json($this->getProducts($user));

        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            // throw $th;
            return response()->json(['error' => "Failed in get data"]);
        }
    }

    public function product(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $validatedData = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/product";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = Product::orderBy('id', 'ASC')->where('activated', 1)->where('id', $request->id)->first();

            if (!isset($data)) {
                return response()->json(['error' => "Product not found"]);
            }

            $data->img_1 = asset("/img/products/" . $data->img_1);

            $stock = $data->Stock($data);

            if ($stock > 0 && $data->kit == 1) {
                $stock = 1;
            }

            $data->stock = $stock;



            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function cartAdd(Request $request)
    {
        try {
            $user = $this->getUser($request);

            if ($user == false) {
                return response()->json(['error' => "Invalid token"]);
            }

            $validatedData = Validator::make($request->all(), [
                'id' => 'required|numeric',
                'quantity' => 'required|numeric',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/get/add/cart";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = Product::orderBy('id', 'ASC')->where('activated', 1)->where('id', $request->id)->first();

            if (!isset($data)) {
                return response()->json(['error' => "Product not found"]);
            }

            $data->img_1 = asset("/img/products/" . $data->img_1);

            $addCart = $this->addToCart($request, $user);

            return response()->json($addCart);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function addToCart(Request $request, User $user)
    {
        try {
            $product = Product::find($request->id);

            if (!$product) {
                return [
                    "error" => "Product not found"
                ];
            }

            $stock = $this->calculateStock($product, $request->quantity);

            if (!$stock) {
                return [
                    "error" => "Out of stock"
                ];
            }

            if ($product->kit == 1) {
                $this->addKitProductsToCart($product, $user->id);
                return json_decode($this->cart($request)->getContent());
            }

            $this->updateOrAddToCart($product, $request->quantity, $user, $stock);
            return json_decode($this->cart($request)->getContent());
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in add cart"]);
        }
    }

    private function calculateStock($product, $requestedQuantity)
    {
        if ($product->kit != 0) {
            return $this->getStockForKit($product);
        }

        return min($requestedQuantity, $this->getProductStock($product->id));
    }

    private function getStockForKit($product)
    {
        $separado = $this->parseKitProducts($product->kit_produtos);

        foreach ($separado as $id_produto => $quantidade) {
            $temStock = $this->getProductStock($id_produto);

            if ($temStock < $quantidade) {
                return 0;
            }
        }

        return $product->kit == 1 ? true : $this->getProductStock($product->id);
    }

    private function parseKitProducts($kit_produtos)
    {
        $parts = explode('|', $kit_produtos);
        $separado = [];
        foreach ($parts as $part) {
            list($id_produto, $quantidade) = explode('-', $part);
            $separado[$id_produto] = $quantidade;
        }

        return $separado;
    }

    private function getProductStock($productId)
    {
        return DB::table('stock')->where('product_id', $productId)->sum('amount');
    }

    private function addKitProductsToCart($product, $user_id)
    {
        $productController = new ProductController;
        $separado = $this->parseKitProducts($product->kit_produtos);
        // dd($separado);
        foreach ($separado as $id_produto => $quantidade) {
            $productController->addProductInToCart($id_produto, $quantidade, $user_id);
        }
    }

    private function updateOrAddToCart($product, $quantProduct, $user, $stock)
    {
        $debit_price = $product->backoffice_price;
        $quantProduct = min($quantProduct, $stock);

        $cart = CartOrder::firstOrNew([
            'id_user' => $user->id,
            'id_product' => $product->id
        ]);

        $cart->fill([
            'name' => $product->name,
            'img' => $product->img_1,
            'price' => $product->backoffice_price,
            'amount' => $cart->exists ? $cart->amount + $quantProduct : $quantProduct,
            'total' => $cart->exists ? $cart->total + ($quantProduct * $debit_price) : ($quantProduct * $debit_price)
        ])->save();

        return $cart;
    }

    public function returnStock(Product $product)
    {
        $stock = 1;

        if ($product->kit != 0) {

            $parts = explode('|', $product->kit_produtos);

            foreach ($parts as $part) {
                list($id_produto, $quantidade) = explode('-', $part);
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

        return $stock;
    }

    public function newSmartshiping(Request $request)
    {
        $user = $this->getUser($request);

        if ($user == false) {
            return response()->json(['error' => "Invalid token"]);
        }

        $ip = $request->ip();
        $requestFormated = $request->all();

        $ipRequest = new IpAccessApi;
        $ipRequest->ip = $ip;
        $ipRequest->operation = "api/app/get/products/new/smartshipping";
        $ipRequest->request = json_encode($requestFormated);
        $ipRequest->save();

        return $this->products($request);
    }

    public function newSmartshipingAdd(Request $request)
    {
        $user = $this->getUser($request);

        if ($user == false) {
            return response()->json(['error' => "Invalid token"]);
        }

        $ip = $request->ip();
        $requestFormated = $request->all();

        $ipRequest = new IpAccessApi;
        $ipRequest->ip = $ip;
        $ipRequest->operation = "api/app/get/products/new/smartshipping/add";
        $ipRequest->request = json_encode($requestFormated);
        $ipRequest->save();

        $data = json_decode($request->getContent());

        foreach ($data as $prod) {
            $product = Product::find($prod->product);

            if (!$product) {
                return [
                    "error" => "Product not found"
                ];
            }

            $stock = $this->calculateStock($product, $prod->quantity);

            if ($stock) {
                if ($product->kit == 1) {
                    $this->addKitProductsToCart($product, $user->id);
                } else {
                    $this->updateOrAddToCart($product, $prod->quantity, $user, $stock);
                }
            }

        }

        $retorno = $this->cart($request, true);
        return $retorno;
    }

    public function cart(Request $request, $smartshipping = false)
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
            $ipRequest->operation = "api/app/get/cart";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();


            $id_user = $user->id;
            $user_cart = CartOrder::where('id_user', '=', $id_user)->get();

            if (
                $user->country == '' || $user->country == null ||
                $user->address1 == '' || $user->address1 == null ||
                $user->city == '' || $user->city == null ||
                $user->postcode == '' || $user->postcode == null ||
                $user->state == '' || $user->state == null ||
                $user->number_residence == '' || $user->number_residence == null
                // $user->area_residence == '' || $user->area_residence == null

            ) {
                return response()->json(['error' => "Complete your address in Info Page"]);
            }


            if (count($user_cart) < 1) {
                return response()->json(['error' => "0 Products in cart"]);
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

                $order->img = asset("/img/packages/" . $order->img);

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

                        $n = number_format($n, 2);

                        array_push($todosFreteCasa, [
                            "country" => $value->country,
                            "country_code" => $value->country_code,
                            "price" => $n,
                        ]);

                        // $todosFreteCasa[$key] = $n;
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
                            $n = number_format($n, 2);

                            array_push($todosFretePickup, [
                                "country" => $value->country,
                                "country_code" => $value->country_code,
                                "price" => $n,
                            ]);
                            // $todosFretePickup[$value->country_code] = $n;
                        }
                    }
                }
            } else {
                $shippingPickup = null;
                $priceShippingPickup = null;
            }

            $withoutVAT = $subtotal - $total_VAT;

            $withoutVAT = number_format($withoutVAT, 2, ',', '.');
            $total_VAT = number_format($total_VAT, 2, ',', '.');
            // dd($subtotal);

            $btnSmart = null;
            $myComission = $this->getTotalComissionAvailable($user);

            array_push($metodos, [
                "id" => "BTC",
                "name" => "Bitcoin",
            ], [
                "id" => "COMISSION",
                "name" => "E-Wallet",
            ]);

            $returnCart = [
                // "smartshipping" => $smartshipping,
                "products" => $user_cart,
                "values" => [
                    'available_comissions' => $myComission['value'],
                    "subtotal" => $withoutVAT,
                    "qv" => $qv,
                    "cv" => $cv,
                ],
                "payment_methods" => $metodos,
                "shipping" => [
                    "my_address" => [
                        "vat" => $total_VAT,
                        "address" => $user->address1,
                        "country" => $user->country,
                        "price" => number_format($priceShippingHome, 2, '.', ',')
                    ],
                    "vats_by_address" => $todosVats,
                    "other_address_home" => $todosFreteCasa,
                    "pickup" => $todosFretePickup
                ]
            ];

            return response()->json($returnCart);

            // return response()->json([$btnSmart, $todosFreteCasa, $todosFretePickup, $semVat, $todosVats, $MaxtaxValuesByCountry, $cv, $qv, $allPickup, $shippingPickup, $countryes, $user_cart, $count_itens, $subtotal, $total_VAT, $metodos, $priceShippingHome, $priceShippingPickup, $withoutVAT, $user]);



        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function payment(Request $request)
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
            $ipRequest->operation = "api/app/get/cart/payment";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $order_cart = CartOrder::where('id_user', '=', $user->id)->get();
            if (count($order_cart) < 1) {
                return response()->json(['error' => "0 products in cart"]);
            }

            $productController = new ProductController;
            $n_order = $productController->genNumberOrder();

            try {
                $total_shipping = number_format($request->prices['total_shipping'], 2, ",", ".");
            } catch (\Throwable $th) {
                if (strlen($request->prices['total_shipping']) < 7) {
                    $total_shipping = floatval(str_replace(',', '.', $request->prices['total_shipping']));
                } else {
                    $valorSemSeparadorMilhar_total_shipping = str_replace('.', '', $request->prices['total_shipping']);
                    $total_shipping = str_replace(',', '.', $valorSemSeparadorMilhar_total_shipping);
                }
            }
            $total_shipping = str_replace(',', '.', $total_shipping);


            if (strlen($request->prices['total_price']) < 7) {
                $price = floatval(str_replace(',', '.', $request->prices['total_price']));
            } else {
                $valorSemSeparadorMilhar = str_replace('.', '', $request->prices['total_price']);
                $price = str_replace(',', '.', $valorSemSeparadorMilhar);
            }

            if (strlen($request->prices['total_vat']) < 7) {
                $total_vat = floatval(str_replace(',', '.', $request->prices['total_vat']));
            } else {
                $valorSemSeparadorMilhar = str_replace('.', '', $request->prices['total_vat']);
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

            $shippingMethod = $request->shipping['method'];
            $paymentMethod = $request->method_payment;
            $smartshipping = isset($request->smartshipping) && $request->smartshipping == true;

            if ($smartshipping == true) {
                $paymentMethod = "CARD_CZ_CSOB_2";
            }

            if ($shippingMethod == 'home2') {
                $nAdress = $this->RegisteredAddressSecondary($request->shipping['address'], $user);
                if ($nAdress == false) {
                    return response()->json(['error' => "Fill in all fields of address"]);
                }

                $paisNome = $request->shipping['address']["country"];
            } else if ($shippingMethod == 'pickup') {
                $nPickup = $this->registerChosenPickup($request->shipping['pickup'], $n_order, $user);
                if ($nPickup == false) {
                    return response()->json(['error' => "Select pickup address"]);
                }

                $paisNome = ShippingPrice::where('country_code', $request->shipping['pickup']['country_ppl'])->first()->country;
            } else {
                $paisNome = $user->country;
            }

            $productController = new ProductController;

            if ($paymentMethod == 'BTC') {
                $responseData = $productController->payCrypto($request, $price);
                if (!isset($responseData->invoice_id)) {
                    return response()->json(['error' => "Failed in payment"]);
                }

                $data = [
                    "id_invoice_trans" => $responseData->invoice_id,
                    "url" => $responseData->url,
                    "id_payment" => $responseData->id,
                    "status" => 'Pending'
                ];

            } else if ($paymentMethod == 'COMISSION') {
                $responseData = $productController->payComission($request, $price, $n_order, $user->id);

                if ($responseData == false) {
                    return response()->json(['error' => "insufficient comission"]);
                }

                $data = [
                    "id_invoice_trans" => 'Comission',
                    "url" => $responseData['url'],
                    "id_payment" => $n_order,
                    "status" => $responseData['status']
                ];

            } else {
                if ($smartshipping == true) {
                    $responseData = $productController->payComgate($request, $newPrice, $n_order, $paymentMethod, $user->id, $smartshipping);
                } else {
                    $responseData = $productController->payComgate($request, $newPrice, $n_order, $paymentMethod, $user->id);
                }

                if (!isset($responseData['code'])) {
                    return response()->json(['error' => "Failed in payment"]);
                }

                $jsonResponse = [
                    'code' => $responseData['code'],
                    'message' => $responseData['message'],
                    'transId' => $responseData['transId'],
                    'redirect' => urldecode($responseData['redirect']),
                ];

                $data = [
                    "id_invoice_trans" => $jsonResponse['transId'],
                    "url" => $jsonResponse['redirect'],
                    "id_payment" => $n_order,
                    "status" => 'Pending',
                ];
            }

            if ($smartshipping == true) {
                $data["smartshipping"] = 1;
            } else {
                $data["smartshipping"] = 0;

            }
            $data["country"] = $paisNome;
            $data["total_vat"] = $total_vat;
            $data["total_price"] = $price;
            $data["method_shipping"] = $shippingMethod;
            $data["total_shipping"] = $total_shipping;
            $data["method"] = $paymentMethod;
            $data["order"] = $n_order;

            if (isset($responseData)) {
                $payment = $productController->createRegisterPayment($data, $user->id);
                // dd($payment);
                $data['newPayment'] = $payment->id;
                $productController->createRegisterEcommOrder($data, $user->id);

                if ($paymentMethod == 'COMISSION') {
                    $productController->sendPostBonificacao($data["order"], 1);
                }

                // return redirect()->away($data['url']);
                return response()->json([
                    'method_payment' => $paymentMethod,
                    'url' => $data['url']
                ]);

            } else {
                return response()->json(['error' => "Failed in get data"]);
            }

        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function RegisteredAddressSecondary($request, $user)
    {
        // dd($request);
        $id_user = $user->id;
        $exists = AddressSecondary::where('id_user', $id_user)->first();

        if (
            empty($request['phone']) ||
            empty($request['zip']) ||
            empty($request['address']) ||
            empty($request['number']) ||
            empty($request['state']) ||
            empty($request['city']) ||
            empty($request['country'])
        ) {
            return false;
        }

        if (isset($exists)) {

            $exists->update([
                'phone' => $request['phone'],
                'zip' => $request['zip'],
                'address' => $request['address'],
                'number' => $request['number'],
                'complement' => "null",
                'neighborhood' => "null",
                'city' => $request['city'],
                'state' => $request['state'],
                'country' => $request['country'],
            ]);

        } else {

            $address = new AddressSecondary;
            $address->id_user = $id_user;
            $address->phone = $request['phone'];
            $address->zip = $request['zip'];
            $address->address = $request['address'];
            $address->number = $request['number'];
            $address->complement = "null";
            $address->neighborhood = "null";
            $address->city = $request['city'];
            $address->state = $request['state'];
            $address->country = $request['country'];

            $address->save();
        }
        return true;
    }

    public function registerChosenPickup($request, $number_order, $user)
    {
        if (
            empty($request['id_ppl']) ||
            empty($request['accessPointType']) ||
            empty($request['dhlPsId'])
        ) {
            return false;
        }

        $newChosenPickup = new ChosenPickup;

        $newChosenPickup->id_ppl = $request['id_ppl'];
        $newChosenPickup->accessPointType = $request['accessPointType'];
        $newChosenPickup->code = $request['code'];
        $newChosenPickup->dhlPsId = $request['dhlPsId'];
        $newChosenPickup->depot = $request['depot'];
        $newChosenPickup->depotName = $request['depotName'];
        $newChosenPickup->name = $request['name'];
        $newChosenPickup->street = $request['street'];
        $newChosenPickup->city = $request['city'];
        $newChosenPickup->zipCode = $request['zipCode'];
        $newChosenPickup->country = $request['country'];
        $newChosenPickup->parcelshopName = $request['parcelshopName'];
        $newChosenPickup->id_user = $user->id;
        $newChosenPickup->number_order = $number_order;

        $newChosenPickup->save();

        return $newChosenPickup;

    }
}
