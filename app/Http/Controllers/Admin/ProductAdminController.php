<?php

namespace App\Http\Controllers\Admin;

use App\Models\AddressBilling;
use App\Models\CustomLog;
use App\Models\InvoicesFakturoid;
use App\Models\PickupPoint;
use App\Models\SmartshippingPaymentsRecurring;
use App\Models\SubjectsFakturoid;
use App\Models\Tax;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\AddressSecondary;
use App\Models\ChosenPickup;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\OrderEcomm;
use App\Models\PaymentOrderEcomm;
use App\Models\ShippingPrice;
use App\Models\Stock;
use App\Models\User;
use App\Models\HistoricScore;
use Auth;
use File;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Banco;
use Illuminate\Support\Facades\Storage;
use App\Models\Documents;
use App\Models\Video;
use Fakturoid\Client as FakturoidClient;

class ProductAdminController extends Controller
{
    private $token = '';
    private $urlTagShipping = '';
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->paginate(9);
        return view('admin.products.products', ['products' => $products]);
    }

    public function create()
    {
        $allProducts = Product::where('kit', '<>', 1)->get();

        $documents = Documents::get();
        $videos = Video::get();
        return view('admin.products.create', compact('allProducts', 'videos', 'documents'));
    }

    public function store(Request $request)
    {

        $contentKit = '';

        if ($request->kit != 0) {
            foreach ($request->all() as $key => $value) {
                if (Str::contains($key, 'product-')) {
                    $id_prod = Str::after($key, 'product-');
                    if ($id_prod) {
                        $producttt = Product::where('id', $id_prod)->first();
                        if (isset($producttt)) {
                            if ($value > 0) {
                                $contentKit .= "$id_prod-$value|";
                            }
                        }
                    }
                }
            }
        }

        if (substr($contentKit, -1) === '|') {
            $contentKit = rtrim($contentKit, '|');
        }

        if ($request->kit != 0 && $contentKit == null) {
            return redirect()->back()->withErrors(['error' => 'Choose products for kit'])->withInput();

        }

        $user = Auth::user();

        $product = new Product;
        $product->name = $request->name;
        $product->resume = $request->resume;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->unity = "";
        $product->price = $request->price;
        $product->premium_price = $request->premium_price;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->depth = $request->depth;
        $product->weight = $request->weight;
        $product->backoffice_price = $request->backoffice_price;
        $product->qv = $request->qv ?? 0;
        $product->cv = $request->cv ?? 0;
        $product->kit = $request->kit;
        $product->kit_produtos = $contentKit;
        $product->amount = 0;
        $product->score = 0;
        $product->activated = $request->activated;
        $product->availability = $request->availability;
        $product->id_additional_archive = $request->id_additional_archive;
        $product->active = "";

        try {

            // new upload image
            if ($request->hasFile('img_1') && $request->file('img_1')->isValid()) {

                $requestImage = $request->img_1;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/products'), $imageName);
                $product->img_1 = $imageName;
            }

            if ($request->hasFile('img_2') && $request->file('img_2')->isValid()) {

                $requestImage = $request->img_2;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/products'), $imageName);
                $product->img_2 = $imageName;
            }

            if ($request->hasFile('img_3') && $request->file('img_3')->isValid()) {

                $requestImage = $request->img_3;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/products'), $imageName);
                $product->img_3 = $imageName;
            }

            if ($request->hasFile('video') && $request->file('video')->isValid()) {

                $requestImage = $request->video;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
                $requestImage->move(public_path('img/products'), $imageName);
                $product->video = $imageName;
            }

            $product->save();

            $stock = new Stock;
            $stock->user_id = $user->id;
            $stock->product_id = $product->id;
            $stock->amount = isset($request->stock) ? $request->stock : 0;
            $stock->save();

            $allCountries = ShippingPrice::all();

            foreach ($allCountries as $country) {
                $tax = new Tax;
                $tax->product_id = $product->id;
                $tax->country = $country->country;
                $tax->value = 00.00;
                $tax->save();
            }

            return redirect()->route('admin.packages.index_admin');

        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotcreate'))->error();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $stock = Stock::where('product_id', $id)->first();
        $product->stock = isset($stock) ? $stock->amount : 0;

        $allProducts = Product::where('id', '<>', $id)->where('kit', 0)->orWhere('id', $id)->get();

        $separado = [];

        if ($product->kit != 0) {
            $parts = explode('|', $product->kit_produtos);
            foreach ($parts as $part) {
                list($id_produto, $quantidade) = explode('-', $part);
                $separado[$id_produto] = $quantidade;
            }
        }

        return view('admin.products.edit', compact('product', 'allProducts', 'separado'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $contentKit = '';

        if ($request->kit != 0) {
            foreach ($request->all() as $key => $value) {
                if (Str::contains($key, 'product-')) {
                    $id_prod = Str::after($key, 'product-');
                    if ($id_prod) {
                        $producttt = Product::where('id', $id_prod)->first();
                        if (isset($producttt)) {
                            if ($value > 0) {
                                $contentKit .= "$id_prod-$value|";
                            }
                        }
                    }
                }
            }
        }

        if (substr($contentKit, -1) === '|') {
            $contentKit = rtrim($contentKit, '|');
        }

        if ($request->kit != 0 && $contentKit == null) {
            return redirect()->back()->withErrors(['error' => 'Choose products for kit'])->withInput();

        }

        $productEdit = Product::find($id);

        // dd($contentKit);

        if ($request->stock > 0) {
            # code...
            $stock = new Stock;
            $stock->user_id = $user->id;
            $stock->product_id = $id;
            $stock->amount = $request->stock;
            $stock->number_order = -1;
            $stock->ecommerce_externo = -1;
            $stock->save();
        }

        $productEdit->update([
            'name' => $request->name,
            'resume' => $request->resume,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'premium_price' => $request->premium_price,
            'height' => $request->height,
            'width' => $request->width,
            'depth' => $request->depth,
            'weight' => $request->weight,
            'activated' => $request->activated,
            'availability' => $request->availability
        ]);

        $productEdit->kit = $request->kit;
        $productEdit->kit_produtos = $contentKit;
        $productEdit->backoffice_price = $request->backoffice_price;
        $productEdit->qv = $request->qv ?? 0;
        $productEdit->cv = $request->cv ?? 0;
        $productEdit->premium_price = $request->premium_price;
        // dd($productEdit);
        $productEdit->save();


        if ($request->hasFile('img_1') && $request->file('img_1')->isValid()) {

            $filePath = public_path('img/products/' . $productEdit->img_1);

            if (file_exists($filePath)) {
                File::delete($filePath);
            }

            $requestImage = $request->img_1;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/products'), $imageName);


            $productEdit->update([
                'img_1' => $imageName,
            ]);
        }

        if ($request->hasFile('img_2') && $request->file('img_2')->isValid()) {

            $filePath = public_path('img/products/' . $productEdit->img_2);

            if (file_exists($filePath)) {
                File::delete($filePath);
            }

            $requestImage = $request->img_2;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/products'), $imageName);

            $productEdit->update([
                'img_2' => $imageName,
            ]);
        }

        if ($request->hasFile('img_3') && $request->file('img_3')->isValid()) {

            $filePath = public_path('img/products/' . $productEdit->img_3);

            if (file_exists($filePath)) {
                File::delete($filePath);
            }

            $requestImage = $request->img_3;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/products'), $imageName);

            $productEdit->update([
                'img_3' => $imageName,
            ]);
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {

            $filePath = public_path('img/products/' . $productEdit->video);

            if (file_exists($filePath)) {
                File::delete($filePath);
            }

            $requestImage = $request->video;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/products'), $imageName);

            $productEdit->update([
                'video' => $imageName,
            ]);
        }


        return redirect()->route('admin.packages.index_admin');
        // return view('admin.products.edit', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $stock = Stock::where('product_id', $id)->first();

        if ($product->img_1) {
            if (file_exists('img/products/' . $product->img_1)) {
                File::delete('img/products/' . $product->img_1);
            }
        }

        if ($product->img_2) {
            if (file_exists('img/products/' . $product->img_2)) {
                File::delete('img/products/' . $product->img_2);
            }
        }

        if ($product->img_3) {
            if (file_exists('img/products/' . $product->img_3)) {
                File::delete('img/products/' . $product->img_3);
            }
        }

        $product->delete();
        if (isset($stock)) {
            $stock->delete();
        }
        return redirect()->route('admin.packages.index_admin');
    }

    public function orderproducts()
    {
        $orderpackages = PaymentOrderEcomm::orderBy('id', 'DESC')->paginate(50);
        $metodos = $this->metodosComgate();
        // dd($metodos);

        foreach ($orderpackages as $order) {
            $metodo_pay = $order->payment_method;

            foreach ($metodos as $mt) {
                if ($mt->id == $metodo_pay) {
                    $metodo_pay = $mt->name;
                }
            }
            $order->payment_method = $metodo_pay;
        }
        return view('admin.products.orders', compact('orderpackages'));
    }

    public function orderfilter($parameter)
    {
        try {
            $packageSearch = PaymentOrderEcomm::orderBy('created_at', 'DESC');

            //Filters
            switch ($parameter) {
                case 'paid':
                    // dd($parameter);
                    $packageSearch->where('status', 'LIKE', 'paid');
                    break;
                case 'pending':
                    $packageSearch->where('status', 'LIKE', 'pending');
                    break;
                case 'cancelled':
                    $packageSearch->where('status', 'LIKE', 'cancelled');
                    break;
                case 'order placed':
                    $packageSearch = DB::table('payments_order_ecomms')
                        ->join('ecomm_orders', 'payments_order_ecomms.number_order', '=', 'ecomm_orders.number_order')
                        ->distinct('payments_order_ecomms.number_order')
                        ->where('payments_order_ecomms.status', 'paid')
                        ->where('ecomm_orders.status_order', 'order placed')
                        ->select('payments_order_ecomms.*');
                    break;
            }

            $orderpackages = $packageSearch->paginate(50);
            $metodos = $this->metodosComgate();
            // dd($metodos);

            foreach ($orderpackages as $order) {
                $metodo_pay = $order->payment_method;

                foreach ($metodos as $mt) {
                    if ($mt->id == $metodo_pay) {
                        $metodo_pay = $mt->name;
                    }
                }
                $order->payment_method = $metodo_pay;
            }

            // dd($orderpackages);

            return view('admin.products.orders', compact('orderpackages', 'parameter'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->route('admin.packages.orderproducts');
        }
    }


    public function orderFilterName(Request $request, $parameter)
    {
        $search = $request->search;

        if (isset($search) and !empty($search)) {

            try {
                $packageSearch = DB::table('payments_order_ecomms')
                    ->join('users', 'payments_order_ecomms.id_user', '=', 'users.id')
                    ->select('payments_order_ecomms.*', 'users.name', 'users.login');
                // $packageSearch = PaymentOrderEcomm::orderBy('created_at', 'DESC');

                //Filters
                switch ($parameter) {
                    case 'paid':

                        if (is_numeric($search)) {
                            $packageSearch->where('status', 'LIKE', 'paid')->where('id_payment_gateway', $search);
                        } else {
                            $packageSearch->where('status', 'LIKE', 'paid')
                                ->where('users.name', 'LIKE', '%' . $search . '%')
                                ->orWhere('users.login', 'LIKE', '%' . $search . '%');
                            ;
                        }
                        break;

                    case 'pending':

                        if (is_numeric($search)) {
                            $packageSearch->where('status', 'LIKE', 'pending')->where('id_payment_gateway', $search);
                        } else {
                            $packageSearch->where('status', 'LIKE', 'pending')
                                ->where('users.name', 'LIKE', '%' . $search . '%')
                                ->orWhere('users.login', 'LIKE', '%' . $search . '%');
                        }
                        break;

                    case 'cancelled':

                        if (is_numeric($search)) {
                            $packageSearch->where('status', 'LIKE', 'cancelled')->where('id_payment_gateway', $search);
                        } else {
                            $packageSearch->where('status', 'LIKE', 'cancelled')
                                ->where('users.name', 'LIKE', '%' . $search . '%')
                                ->orWhere('users.login', 'LIKE', '%' . $search . '%');
                        }
                        break;
                }

                $orderpackages = $packageSearch->paginate(50);
                $metodos = $this->metodosComgate();
                // dd($metodos);

                foreach ($orderpackages as $order) {
                    $metodo_pay = $order->payment_method;

                    foreach ($metodos as $mt) {
                        if ($mt->id == $metodo_pay) {
                            $metodo_pay = $mt->name;
                        }
                    }
                    $order->payment_method = $metodo_pay;
                }

                return view('admin.products.orders', compact('orderpackages', 'parameter', 'search'));
            } catch (Exception $e) {
                $this->errorCatch($e->getMessage(), auth()->user()->id);
                flash(__('admin_alert.pkgnotfound'))->error();
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function CancelOrders(Request $request)
    {

        if ($request->confirm != 'CONFIRM') {
            return redirect()->back()->withErrors(['SendFakturoid' => 'Error, type CONFIRM.']);
        }

        if (strlen(trim($request->reason)) < 1) {
            return redirect()->back()->withErrors(['SendFakturoid' => 'Error, type reason.']);
        }

        $id = $request->order;
        $url = "/admin/packages/cancelled/orderfilter/product";

        $packageOrders = PaymentOrderEcomm::where('number_order', $id)->first();
        $packageOrders->update([
            'status' => 'cancelled',
        ]);

        EcommOrders::where('number_order', $id)->update(['smartshipping' => 0]);
        HistoricScore::where('orders_package_id', $id)->delete();
        Banco::where('order_id', $id)->delete();

        try {
            $log = new CustomLog;
            $log->content = $request->reason;
            $log->user_id = auth()->user()->id;
            $log->operation = "cancel order $id";
            $log->controller = "ProductAdminController";
            $log->http_code = 200;
            $log->route = "admin.packages.orderfilter.CancelOrders";
            $log->status = "success";
            $log->save();
        } catch (\Throwable $th) {
            return redirect()->back();
        }

        return redirect($url);
    }

    public function getAccessTokenPPL()
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

        $this->token = json_decode($response)->access_token;
    }

    public function estruturaPPLpickup($data, $productType)
    {

        $shipmentSetItem = [];
        $weight = 0;

        foreach ($data['products'] as $product) {
            $weight += $product['weight'];
        }

        if ($weight == 0) {
            $weight = 0.1;
        }

        $weight = number_format($weight, 2, '.', ''); // 2 casas decimais, ponto como separador decimal
        // dd($weight);

        $specificDelivery = [];

        if ($data['mt_shipp'] == "pickup") {
            $specificDelivery = [
                "specificDelivery" => [
                    "parcelShopCode" => $data['pickup_code']
                ],
            ];
        }

        $phone = preg_replace('/\s+/', '', $data['phone']);
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        // dd($specificDelivery['specificDelivery']);

        $dados = [
            "returnChannel" => [
                "type" => "Email",
                "address" => "support@Pribonasorte.eu"
            ],
            "labelSettings" => [
                "format" => "Pdf",
                "dpi" => 300,
                "completeLabelSettings" => [
                    "isCompleteLabelRequested" => true,
                    "pageSize" => "A4",
                    "position" => 3
                ]
            ],
            "shipments" => [
                [
                    "referenceId" => "Order " . $data['order'],
                    "productType" => $productType,
                    "note" => "Product",
                    "shipmentSet" => [
                        "numberOfShipments" => 1,
                        "shipimentSetItem" => [
                            "weighedShipmentInfo" => [
                                "weight" => $weight
                            ]
                        ],
                    ],
                    "sender" => [
                        "name" => "Pribonasorte - INTERMODELS s.r.o.",
                        "street" => "Pod Nouzovem 971/17",
                        "city" => "Praha - Kbely",
                        "zipCode" => "19700",
                        "country" => "CZ",
                        "contact" => "Pribonasorte - INTERMODELS s.r.o.",
                        "email" => "support@Pribonasorte.eu",
                        "phone" => "+420234688024"
                    ],
                    "recipient" => [
                        "name" => $data['client_name'],
                        "street" => $data['address'],
                        "city" => $data['city'],
                        "zipCode" => $data['zip'],
                        "country" => $data['country'],
                        "contact" => $data['client_name'],
                        "email" => $data['client_email'],
                        "phone" => $phone
                    ],
                    "specificDelivery" => [
                        "parcelShopCode" => $data['pickup_code'] ?? ''
                    ],
                    // "cashOnDelivery" => [
                    //     "CodPrice" => ($data['total_order'] * 24.4071),
                    //     "codCurrency" => "CZK",
                    //     "codVarSym" => "214452"
                    // ],
                    // "insurance" => [
                    //     "insurancePrice" => "56000",
                    //     "insuranceCurrency" => "CZK"
                    // ],
                    // "externalNumbers" => [
                    //     [
                    //         "externalNumber" => "1233334",
                    //         "code" => "CUST"
                    //     ]
                    // ]
                ]
            ]
        ];


        return $dados;
    }

    public function estruturaPPLhome($data, $productType)
    {
        $shipmentSetItem = [];
        $weight = 0;

        foreach ($data['products'] as $product) {
            $weight += $product['weight'];
        }

        if ($weight == 0) {
            $weight = 0.1;
        }

        $weight = number_format($weight, 2, '.', ''); // 2 casas decimais, ponto como separador decimal
        // dd('casa');

        $phone = preg_replace('/\s+/', '', $data['phone']);
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }


        if (isset($data['number'])) {
            $addressFormated = $data['address'] . ' - ' . $data['number'];
        } else {
            $addressFormated = $data['address'];
        }

        // dd($addressFormated);

        // dd($specificDelivery['specificDelivery']);

        $dados = [
            "returnChannel" => [
                "type" => "Email",
                "address" => "support@Pribonasorte.eu"
            ],
            "labelSettings" => [
                "format" => "Pdf",
                "dpi" => 300,
                "completeLabelSettings" => [
                    "isCompleteLabelRequested" => true,
                    "pageSize" => "A4",
                    "position" => 3
                ]
            ],
            "shipments" => [
                [
                    "referenceId" => "Order " . $data['order'],
                    "productType" => $productType,
                    "note" => "Product",
                    "shipmentSet" => [
                        "numberOfShipments" => 1,
                        "shipimentSetItem" => [
                            "weighedShipmentInfo" => [
                                "weight" => $weight
                            ]
                        ],
                    ],
                    "sender" => [
                        "name" => "Pribonasorte - INTERMODELS s.r.o.",
                        "street" => "Pod Nouzovem 971/17",
                        "city" => "Praha - Kbely",
                        "zipCode" => "19700",
                        "country" => "CZ",
                        "contact" => "Pribonasorte - INTERMODELS s.r.o.",
                        "email" => "support@Pribonasorte.eu",
                        "phone" => "+420234688024"
                    ],
                    "recipient" => [
                        "name" => $data['client_name'],
                        "street" => $addressFormated,
                        "city" => $data['city'],
                        "zipCode" => $data['zip'],
                        "country" => $data['country'],
                        "contact" => $data['client_name'],
                        "email" => $data['client_email'],
                        "phone" => $phone
                    ],
                    // "cashOnDelivery" => [
                    //     "CodPrice" => ($data['total_order'] * 24.4071),
                    //     "codCurrency" => "CZK",
                    //     "codVarSym" => "214452"
                    // ],
                    // "insurance" => [
                    //     "insurancePrice" => "56000",
                    //     "insuranceCurrency" => "CZK"
                    // ],
                    // "externalNumbers" => [
                    //     [
                    //         "externalNumber" => "1233334",
                    //         "code" => "CUST"
                    //     ]
                    // ]
                ]
            ]
        ];

        return $dados;
    }

    public function sendDataShippingtWithTokenPPL($token, $data)
    {
        // dd($data);

        if (strtolower($data['country']) == 'cz') {
            if (strtolower($data['mt_shipp']) == "pickup") {
                $productType = 'SMAR';
                $dados = $this->estruturaPPLpickup($data, $productType);
            } else {
                $productType = 'PRIV';
                $dados = $this->estruturaPPLhome($data, $productType);
            }
        } else {
            if (strtolower($data['mt_shipp']) == "pickup") {
                $productType = 'SMEU';
                $dados = $this->estruturaPPLpickup($data, $productType);
            } else {
                $productType = 'CONN';
                $dados = $this->estruturaPPLhome($data, $productType);
            }
        }

        // dd($dados);

        $ch = curl_init();

        $url = 'https://api.dhl.com/ecs/ppl/myapi2/shipment/batch';
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));


        //dd(json_encode($dados));

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return false;
        }

        curl_close($ch);

        list($header, $body) = explode("\r\n\r\n", $response, 2);

        $headersArray = explode("\r\n", $header);

        $location = null;

        foreach ($headersArray as $headerLine) {
            if (stripos($headerLine, 'Location:') !== false) {
                $location = trim(str_ireplace('Location:', '', $headerLine));
                break;
            }
        }


        $json = json_decode($body, true);

        // dd($dados);

        if ($json) {
            if (isset($json['errors'])) {
                $errors = $json['errors'];

                $ecomm_order = EcommOrders::where('number_order', $data['order'])->get();
                if (count($ecomm_order) > 0) {
                    foreach ($ecomm_order as $order) {
                        $order->log_ppl = $json['errors'];
                        $order->save();
                    }
                }

            }
        }

        if (isset($location) && $location !== null) {
            $ecomm_order = EcommOrders::where('number_order', $data['order'])->get();
            if (count($ecomm_order) > 0) {
                foreach ($ecomm_order as $order) {
                    $order->log_ppl = '';
                    $order->save();
                }
            }
        }

        $this->urlTagShipping = $location;

    }

    public function getDataPPL($token, $url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $headers = [
            'Authorization: Bearer ' . $token,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return false;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data;
    }

    public function getPDFPPL($token, $url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $headers = [
            'Authorization: Bearer ' . $token
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return false;
        }

        curl_close($ch);

        // $data = json_decode($response, true);

        return $response;
    }


    public function sendPPL($data)
    {
        $this->getAccessTokenPPL();
        $token = $this->token;
        $this->sendDataShippingtWithTokenPPL($token, $data);

    }

    public function generatePDFPPL(Request $request)
    {
        $token = $request->token ?? null;
        $url = $request->urlPPL ?? null;
        $order = $request->order;
        $exists = $request->exists ?? null;

        $arquivo = "$order.pdf";
        $pdfFilePath = public_path('pdf/' . $arquivo);

        if (file_exists($pdfFilePath)) {
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->file($pdfFilePath, $headers);
        }

        if ($exists) {
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->file($pdfFilePath, $headers);
        }

        $retorno = $this->getDataPPL($token, $url);
        if (!$retorno || !isset($retorno['completeLabel']) || !isset($retorno['completeLabel']['labelUrls'])) {
            return redirect()->back();
        }


        if (isset($retorno['items'][0]['shipmentNumber'])) {
            $shipmentNumber = $retorno['items'][0]['shipmentNumber'];
            $orderss = EcommOrders::where('number_order', $order)->get();

            foreach ($orderss as $orderr) {
                $orderr->shipmentNumber = $shipmentNumber;
                $orderr->save();
                // dd('oi');
            }
        }

        $urlPDF = $retorno['completeLabel']['labelUrls'][0];

        $pdf = $this->getPDFPPL($token, $urlPDF);
        // dd($order);

        $pdfData = $pdf;
        $fileName = "$order.pdf";
        $publicPath = public_path();
        $pdfPath = $publicPath . "/pdf";

        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0777, true);
        }

        $pdfFilePath = $pdfPath . '/' . $fileName;
        file_put_contents($pdfFilePath, $pdfData);

        header('Content-Type: application/pdf');
        echo $pdf;
        exit();
    }

    public function infoShipping($order)
    {
        $ecomm_order = EcommOrders::where('number_order', $order)->get();

        if (count($ecomm_order) < 1) {
            abort(404);
        }

        $phone = '';
        if ($ecomm_order[0]->client_backoffice == 0) {
            $client = EcommRegister::where('id', $ecomm_order[0]->id_user)->first();
            if (isset($client->phone) && $client->phone != null) {
                $phone = $client->phone ?? '';
            } else {
                $phone = '';
            }
        } else {
            $client = User::where('id', $ecomm_order[0]->id_user)->first();
            if (isset($client->cell) && $client->cell != null) {
                $phone = $client->cell ?? '';
            } else {
                $phone = $client->telephone ?? '';
            }
        }

        // dd($client);

        $payment = PaymentOrderEcomm::where('number_order', $order)->first();

        $client_name = $client->name . ' ' . $client->last_name ?? '';

        $data = [
            'id_user' => $client->id,
            "client_name" => $client_name,
            "client_email" => $client->email,
            "order" => $order,
            'paid_data' => $payment->updated_at,
            'total_order' => $payment->total_price,
            'mt_shipp' => $ecomm_order[0]->method_shipping,
            'total_vat' => 0,
            'total_shipping' => 0,
        ];

        $data['phone'] = $phone ?? '';

        // if ($ecomm_order[0]->client_backoffice == 0 || $ecomm_order[0]->client_backoffice == null) {
        if (
            $ecomm_order[0]->method_shipping == null
            || $ecomm_order[0]->method_shipping == '1'
            || $ecomm_order[0]->method_shipping == 'home'
            || $ecomm_order[0]->method_shipping == 'home1'
            || $ecomm_order[0]->method_shipping == 'pickup'
        ) {
            $data['address'] = $client->address ?? $client->address1 ?? '';
            $data['state'] = $client->state ?? '';
            $data['zip'] = $client->zip ?? $client->postcode ?? '';
            $data['neighborhood'] = $client->neighborhood ?? $client->area_residence ?? $client->city ?? '';
            if (isset($client->number_residence)) {
                $data['number'] = $client->number_residence ?? '';
            } else {
                $data['number'] = $client->number ?? '';
            }
            $data['complement'] = isset($client->complement) ? $client->complement : '';
            $data['country'] = ShippingPrice::where('country', $client->country)->first()->country_code ?? $client->country;
            $data['city'] = $client->city ?? '';


            if ($ecomm_order[0]->method_shipping == 'pickup') {
                if (isset($ecomm_order[0]->id_payment_recurring)) {
                    $recorrente = SmartshippingPaymentsRecurring::where('id', $ecomm_order[0]->id_payment_recurring)->first();
                    $chosenPickup = ChosenPickup::where('number_order', $recorrente->number_order)->first();
                } else {
                    $chosenPickup = ChosenPickup::where('number_order', $order)->first();
                }

                if (isset($chosenPickup)) {
                    $data['address'] = $chosenPickup->name . ' ' . $chosenPickup->street ?? '';
                    if (strlen($data['address']) > 59) {
                        $data['address'] = $chosenPickup->street ?? '';
                    }
                    $data['zip'] = $chosenPickup->zipCode ?? '';
                    $data['state'] = $client->state ?? '';
                    $data['country'] = $chosenPickup->country;
                    $data['city'] = $chosenPickup->city ?? '';
                    $data['pickup_code'] = $chosenPickup->code ?? '';
                    $data['method_shipping'] = "pickup - " . $chosenPickup->name . ", " . $chosenPickup->street . " " . $chosenPickup->city;
                } else {
                    $data['method_shipping'] = 'pickup';
                }

            } else {
                $data['method_shipping'] = 'Delivery in Home, address 1';
            }

        } else if ($ecomm_order[0]->method_shipping == 'home2') {
            $address2 = AddressSecondary::where('id_user', $ecomm_order[0]->id_user)->where('backoffice', $ecomm_order[0]->client_backoffice)->first();

            if (isset($address2->phone)) {
                $data['phone'] = $address2->phone;
            }
            $data['address'] = $address2->address;
            $data['city'] = $address2->city;
            $data['zip'] = $address2->zip;
            $data['neighborhood'] = $address2->neighborhood;
            $data['number'] = $address2->number;
            $data['complement'] = $address2->complement;
            $data['country'] = ShippingPrice::where('country', $address2->country)->first()->country_code ?? $address2->country;
            $data['method_shipping'] = 'Delivery in Home, address 2';
        }
        // }

        $total_price = 0;
        $qv = 0;

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
                'weight' => $product->weight * $value->amount,
                'total' => $value->amount * $price
            ];

            $total_price += $value->amount * $price;
        }

        $data['total_shipping'] = $data['total_order'] - $total_price - $data['total_vat'];
        $data['qv'] = $qv;

        if (isset($ecomm_order[0]->total_shipping)) {
            $data['total_shipping'] = $ecomm_order[0]->total_shipping;
        }

        return $data;
    }

    public function orderproductslabel($order)
    {
        $arquivo = "$order.pdf";
        $pdfFilePath = public_path('pdf/' . $arquivo);
        $exists = false;
        $data = $this->infoShipping($order);

        // dd($data);

        if (file_exists($pdfFilePath)) {
            $exists = true;
            return view('admin.products.shippingLabel', compact('data', 'exists'));
        } else {
            $this->sendPPL($data);
            $token = $this->token;
            $urlPPL = $this->urlTagShipping;

            return view('admin.products.shippingLabel', compact('data', 'token', 'urlPPL', 'exists'));
        }

    }

    public function stock()
    {
        $stock = Stock::orderBy('id', 'DESC')->paginate(9);

        $allProducts = Product::where('activated', 1)->get();

        foreach ($allProducts as $product_info) {
            $product_info->stock = $product_info->getStock($product_info);
        }


        return view('admin.reports.stock', compact('stock', 'allProducts'));
    }

    public function stockFilter(Request $request)
    {
        $filter = "filter";
        // $start = date($request->in_date, time());
        // $end = date($request->until_date, time());

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $stock = Stock::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->get();

        $in = $fdate ?? date('Y-m-d');
        $until = $sdate ?? date('Y-m-d');

        $allProducts = Product::where('activated', 1)->get();

        foreach ($allProducts as $product_info) {
            $product_info->stock = $product_info->getStock($product_info);
        }

        return view('admin.reports.stock', compact('stock', 'filter', 'in', 'until', 'allProducts'));
    }

    public function stock_detal($id)
    {
        $stock = Stock::orderBy('id', 'DESC')->paginate(9);
        $detal = Stock::find($id);

        $allProducts = Product::where('activated', 1)->get();

        foreach ($allProducts as $product_info) {
            $product_info->stock = $product_info->getStock($product_info);
        }

        return view('admin.reports.stock', compact('stock', 'detal', 'allProducts'));
    }

    public function changeStatus(Request $request)
    {
        // dd($request);
        if (isset($request->order) && isset($request->status_order)) {
            $orderss = EcommOrders::where('number_order', $request->order)->get();

            foreach ($orderss as $order) {
                $order->update(['status_order' => $request->status_order]);
            }
            return redirect()->route('admin.packages.orderfilter.products', ['parameter' => 'paid']);
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
                return view('admin.products.tracking', compact('orderNumber', 'info'));
            }
        }

        return view('admin.products.tracking', compact('orderNumber'));

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

    public function stockReport()
    {
        return view('admin.reportsStockDownload.index');
    }

    public function stockReportDownload(Request $request)
    {
        $moviment = null;
        $sent = null;
        switch ($request->type_report) {
            case 'day':
                $sent = $this->stockReportDownloadSentsDay($request->data_day);
                $moviment = $this->stockReportDownloadDay($request->data_day);
                break;
            case 'week':
                $sent = $this->stockReportDownloadSentsWeek($request->data_week);
                $moviment = $this->stockReportDownloadweek($request->data_week);
                break;
            case 'month':
                $sent = $this->stockReportDownloadSentsMonth($request->data_month);
                $moviment = $this->stockReportDownloadMonth($request->data_month);
                break;
            default:
                abort(404);
                break;
        }

        // dd($sent);

        $filtro = null;
        if (isset($request->data_day)) {
            $filtro = $request->data_day;
        }
        if (isset($request->data_week)) {
            list($ano, $semana) = explode('-W', $request->data_week);
            $primeiroDiaDaSemana = date("Y-m-d", strtotime("$ano-W$semana-1"));
            $ultimoDiaDaSemana = date("Y-m-d", strtotime("$primeiroDiaDaSemana + 6 days"));
            $dataLegivel = "$primeiroDiaDaSemana to $ultimoDiaDaSemana";

            $filtro = $dataLegivel;
        }
        if (isset($request->data_month)) {
            $filtro = $request->data_month;
        }

        $moviment->filtro = $filtro;

        foreach ($moviment as $item) {
            $product = Product::where('id', $item->product_id)->first();
            $item->product_name = $product->name;

            if ($item->ecommerce_externo == 1) {
                $client = EcommRegister::where('id', $item->user_id)->first();
                if (isset($client)) {
                    $item->client_name = $client->name ?? 'null';
                    $item->client_last_name = $client->last_name ?? 'null';
                    $item->client_country = $client->country ?? 'null';
                } else {
                    $item->client_name = 'removed';
                    $item->client_last_name = 'removed';
                    $item->client_country = 'removed';
                }
            } else {
                $client = User::where('id', $item->user_id)->first();
                if (isset($client)) {
                    if ($client->rule == 'RULE_ADMIN') {
                        $item->client_name = $client->name ?? 'admin';
                        $item->client_last_name = $client->last_name ?? 'admin';
                        $item->client_country = $client->country ?? 'admin';
                    } else {
                        $item->client_name = $client->name ?? 'null';
                        $item->client_last_name = $client->last_name ?? 'null';
                        $item->client_country = $client->country ?? 'null';
                    }
                } else {
                    $item->client_name = 'removed';
                    $item->client_last_name = 'removed';
                    $item->client_country = 'removed';
                }
            }
        }

        return view("admin.reportsStockDownload.pdf", compact('moviment', 'sent'));
    }


    private function stockReportDownloadSentsDay($day)
    {
        $sents = EcommOrders::whereDate('created_at', $day)->where('status_order', 'order sent')->get();
        $products = Product::all();
        $quantSent = [];
        $quantStock = [];

        foreach ($sents as $sent) {
            foreach ($products as $value) {
                if ($sent->id_product == $value->id) {
                    if (isset($quantSent[$value->name])) {
                        $quantSent[$value->name] += $sent->amount;
                    } else {
                        $quantSent[$value->name] = $sent->amount;
                    }
                }
            }
        }

        foreach ($products as $value) {
            $totalAmount = DB::table('stock')
                ->where('product_id', $value->id)
                ->sum('amount');

            $quantStock[$value->name] = $totalAmount;
        }

        return [
            "stock" => $quantStock,
            "sent" => $quantSent
        ];
    }

    private function stockReportDownloadSentsWeek($week)
    {
        $year = substr($week, 0, 4);
        $weekNumber = substr($week, 6);

        $sents = EcommOrders::whereYear('created_at', $year)->whereRaw('WEEK(created_at, 1) = ?', [$weekNumber])->where('status_order', 'order sent')->get();
        $products = Product::all();
        $quantSent = [];
        $quantStock = [];

        foreach ($sents as $sent) {
            foreach ($products as $value) {
                if ($sent->id_product == $value->id) {
                    if (isset($quantSent[$value->name])) {
                        $quantSent[$value->name] += $sent->amount;
                    } else {
                        $quantSent[$value->name] = $sent->amount;
                    }
                }
            }
        }

        foreach ($products as $value) {
            $totalAmount = DB::table('stock')
                ->where('product_id', $value->id)
                ->sum('amount');

            $quantStock[$value->name] = $totalAmount;
        }

        return [
            "stock" => $quantStock,
            "sent" => $quantSent
        ];
    }

    private function stockReportDownloadSentsMonth($month)
    {
        $month = substr($month, 5);
        $sents = EcommOrders::whereMonth('created_at', $month)->where('status_order', 'order sent')->get();
        $products = Product::all();
        $quantSent = [];
        $quantStock = [];

        foreach ($sents as $sent) {
            foreach ($products as $value) {
                if ($sent->id_product == $value->id) {
                    if (isset($quantSent[$value->name])) {
                        $quantSent[$value->name] += $sent->amount;
                    } else {
                        $quantSent[$value->name] = $sent->amount;
                    }
                }
            }
        }

        foreach ($products as $value) {
            $totalAmount = DB::table('stock')
                ->where('product_id', $value->id)
                ->sum('amount');

            $quantStock[$value->name] = $totalAmount;
        }

        return [
            "stock" => $quantStock,
            "sent" => $quantSent
        ];
    }

    private function stockReportDownloadDay($day)
    {
        $moviment = Stock::whereDate('created_at', $day)->get();
        return $moviment;
    }

    private function stockReportDownloadweek($week)
    {
        $year = substr($week, 0, 4);
        $weekNumber = substr($week, 6);

        $moviment = Stock::whereYear('created_at', $year)
            ->whereRaw('WEEK(created_at, 1) = ?', [$weekNumber])
            ->get();
        return $moviment;
    }

    private function stockReportDownloadMonth($month)
    {
        // dd($month);
        $month = substr($month, 5);
        $moviment = Stock::whereMonth('created_at', $month)->get();
        return $moviment;
    }

    public function Fakturoid($order)
    {
        $SendedFakturoid = InvoicesFakturoid::where('number_order', $order)->first();

        if (isset($SendedFakturoid)) {
            return redirect()->back()->withErrors(['SendFakturoid' => 'Invoice already sent.']);
        }

        $data = $this->getDataForFakturoid($order);
        $countryCodeClient = ShippingPrice::where('country_code', $data['client_country'])->orWhere('country', $data['client_country'])->first();

        $f = new FakturoidClient('intermodels', 'juraj@Pribonasorte.eu', 'd2f384a3e232c5fbeb28c8e2a49435573561905f', 'PHPlib <juraj@Pribonasorte.eu>');
        // dd($f);

        $existisSubject = SubjectsFakturoid::where('user_id', $data['client_id'])->first();
        // dd($existisSubject);

        if (isset($existisSubject)) {
            $subject_id = $existisSubject->subject_id;

            try {
                $updateSubject = $f->updateSubject(
                    $subject_id,
                    array(
                        'name' => $data['client_name'],
                        'street' => $data['client_address'],
                        'country' => $countryCodeClient->country_code,
                        'zip' => $data['client_postcode']
                    )
                );
            } catch (\Throwable $th) {
                // dd("error: $th");
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
                // dd($th);
                $log = new CustomLog;
                $log->content = $th->getMessage();
                $log->user_id = $data['client_id'];
                $log->operation = "order $order";
                $log->controller = "ProductAdminController";
                $log->http_code = 200;
                $log->route = "admin.packages.send.fakturoid";
                $log->status = "success";
                $log->save();

                $log = new CustomLog;
                $log->content = json_encode($data);
                $log->user_id = $data['client_id'];
                $log->operation = "order $order";
                $log->controller = "ProductAdminController";
                $log->http_code = 200;
                $log->route = "admin.packages.send.fakturoid";
                $log->status = "success";
                $log->save();

                return redirect()->back()->withErrors(['Fakturoid' => 'Error in send to Fakturoid.']);
            }

        }

        $lines = array();
        $maiorTaxa = 0;

        foreach ($data['products'] as $item) {
            array_push(
                $lines,
                array(
                    'name' => $item['name'],
                    'quantity' => $item['amount'],
                    'unit_price' => $item['unit'],
                    'vat_rate' => floatval(str_replace(',', '.', $item['porcentVat'])),
                    'vat' => floatval(str_replace(',', '.', $item['vat'])),
                    'price' => $item['total']
                )
            );

            $taxaa = $item['porcentVat'];

            if ($taxaa > $maiorTaxa) {
                $maiorTaxa = $taxaa;
            }
        }

        if ($maiorTaxa > 0) {
            # code...
            $vatFrete = $data['freteSemVat'] * ($maiorTaxa / 100);
        } else {
            $vatFrete = 0;

        }

        array_push(
            $lines,
            array(
                'name' => 'Shipping',
                'quantity' => 1,
                'unit_price' => $data['freteSemVat'],
                'vat_rate' => floatval(str_replace(',', '.', $maiorTaxa)),
                'vat' => floatval(str_replace(',', '.', $vatFrete)),
                'price' => $data['total_shipping']
            )
        );

        try {

            $metodo_pagamento = '';

            if ($data['metodo_pay'] == 'Credit card' || $data['metodo_pay'] == 'CARD_CZ_CSOB_2') {
                $metodo_pagamento = 'card';
            } else if (substr($data['metodo_pay'], 0, 4) === "BANK" || $data['metodo_pay'] == 'Other banks') {
                $metodo_pagamento = 'bank';
            } else {
                $metodo_pagamento = 'cash';
            }


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
                    'subtotal' => $data['total_price_product'],
                    'lines' => $lines
                )
            );
            $invoice = $response->getBody();

            $newOrderFakturoid = new InvoicesFakturoid;
            $newOrderFakturoid->user_id = $data['client_id'];
            $newOrderFakturoid->number_order = $order;
            $newOrderFakturoid->invoice_id = $invoice->id;
            $newOrderFakturoid->save();

            return redirect()->back()->withErrors(['SendFakturoid' => 'Invoice Sended.']);
        } catch (\Throwable $th) {
            // dd($th);
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $data['client_id'];
            $log->operation = "order $order";
            $log->controller = "ProductAdminController";
            $log->http_code = 200;
            $log->route = "admin.packages.send.fakturoid";
            $log->status = "success";
            $log->save();

            $log = new CustomLog;
            $log->content = json_encode($data);
            $log->user_id = $data['client_id'];
            $log->operation = "order $order";
            $log->controller = "ProductAdminController";
            $log->http_code = 200;
            $log->route = "admin.packages.send.fakturoid";
            $log->status = "success";
            $log->save();

            return redirect()->back()->withErrors(['Fakturoid' => 'Error in send to Fakturoid.']);
        }

        // $f->fireInvoice($invoice->id, 'pay');
    }
    public function getDataForFakturoid($id)
    {
        // dd("oi");
        $ecomm_order = EcommOrders::where('number_order', $id)->get();

        if (count($ecomm_order) < 1) {
            abort(404);
        }

        $client_corporate = null;
        $client_corporate_name = null;

        if ($ecomm_order[0]->client_backoffice == 0) {
            $client = EcommRegister::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->phone ?? '';
            $client_corporate = $client->id_corporate ?? null;
            $client_corporate_name = $client->corporate_nome ?? null;
            $client_address = $client->address;
            $client_postcode = $client->zip;

            if ($client->state && isset($client->state)) {
                $client_address .= " " . $client->state . " - " . $client->number;
            } else {
                $client_address .= " - " . $client->number;
            }

            if ($client->city && isset($client->city)) {
                $client_postcode .= " " . $client->city;
            }

        } else {
            $client = User::where('id', $ecomm_order[0]->id_user)->first();
            $cell = $client->cell ?? '';
            $client_address = $client->address1;
            $client_postcode = $client->postcode;

            $client_corporate = $client->id_corporate ?? null;
            $client_corporate_name = $client->corporate_nome ?? null;

            if ($client->complement && isset($client->complement)) {
                $client_address .= " " . $client->complement . " - " . $client->number_residence;
            } else {
                $client_address .= " - " . $client->number_residence;
            }

            if ($client->city && isset($client->city)) {
                $client_postcode .= " " . $client->city;
            }

        }




        $client_name = $client->name . ' ' . ($client->last_name ?? '');
        $payment = PaymentOrderEcomm::where('number_order', $id)->first();

        $metodo_pay = $this->metodosHabilitadosComgate($payment->payment_method);
        if (!$metodo_pay) {
            $metodo_pay = $payment->payment_method;
        }

        $data = [
            "client_corporate" => $client_corporate,
            "client_corporate_name" => $client_corporate_name,
            "client_id" => $client->id,
            "client_name" => $client_name,
            "client_email" => $client->email,
            "client_cell" => $cell,

            "client_address" => $client_address,
            "client_postcode" => mb_substr($client_postcode, 0, 20, "UTF-8"),

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

        if (isset($client_corporate) && isset($client_corporate_name)) {
            $addBilling = AddressBilling::where('user_id', $ecomm_order[0]->id_user)->where('backoffice', $ecomm_order[0]->client_backoffice)->first();
            $data['client_name'] = $client_corporate_name;
            if (isset($addBilling)) {
                $data['client_address'] = $addBilling->address;
                $data['client_country'] = $addBilling->country;
                $data['client_postcode'] = $addBilling->zip;
            }
        }
        // dd($data);
        return $data;
    }

    public function metodosHabilitadosComgate($metodo)
    {

        return [];
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

    public function orderfilterCorporate()
    {
        $metodos = $this->metodosComgate();
        $orderpackages = PaymentOrderEcomm::where('payment_method', 'admin')->orWhere('order_corporate', 1)
            ->orderBy('id', 'DESC')->paginate(50);

        return view('admin.products.ordersCorporate', compact('orderpackages', 'metodos'));

    }

    public function orderfilterStatusCorporate($parameter)
    {
        try {
            $packageSearch = PaymentOrderEcomm::where('payment_method', 'admin')->orWhere('order_corporate', 1)
                ->orderBy('id', 'DESC');
            $metodos = $this->metodosComgate();

            //Filters
            switch ($parameter) {
                case 'paid':
                    $packageSearch->where('status', 'LIKE', 'paid');
                    break;
                case 'pending':
                    $packageSearch->where('status', 'LIKE', 'pending');
                    break;
                case 'cancelled':
                    $packageSearch->where('status', 'LIKE', 'cancelled');
                    break;
            }

            $orderpackages = $packageSearch->paginate(50);

            // dd($orderpackages);

            return view('admin.products.ordersCorporate', compact('orderpackages', 'metodos'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotfound'))->error();
            return redirect()->route('admin.packages.orderproducts');
        }
    }

    public function orderfilterCorporateSave(Request $request, $id)
    {
        // dd($request);
        try {

            $payment = PaymentOrderEcomm::where('id', $id)->first();

            if (isset($payment)) {
                if (isset($request->methodPayment)) {
                    $payment->payment_method = $request->methodPayment;
                }
                $payment->status = $request->status;
                $payment->save();

                if ($request->status == 'paid') {
                    $this->sendPostBonificacao($payment->number_order, 1);
                }

                $log = new CustomLog;
                $log->content = 'Pay payment by admin order corporate';
                $log->user_id = $payment->id_user;
                $log->operation = "cancel order $payment->number_order";
                $log->controller = "ProductAdminController";
                $log->http_code = 200;
                $log->route = "admin.packages.orderfilter.products.corporate.save";
                $log->status = "success";
                $log->save();

                return redirect()->back()->withErrors(['SendFakturoid' => 'Payment modified.']);
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function metodosComgate()
    {

        return [];
    }

    public function sendPostBonificacao($number_order, $prod)
    {

        $isBackoffice = EcommOrders::where('number_order', $number_order)->first();

        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        if ($isBackoffice->client_backoffice == 0) {
            $data = [
                "type" => "bonificacao",
                "param" => "GeraEcommExterno",
                "idpedido" => "$number_order",
                "prod" => $prod
            ];
        } else {
            $data = [
                "type" => "bonificacao",
                "param" => "GeraBonusPedidoInterno",
                "idpedido" => "$number_order",
                "prod" => $prod
            ];
        }

        $url = 'https://Pribonasorte.eu/public/compensacao/bonificacao.php';

        $resposta = $client->post($url, [
            'form_params' => $data,
            // 'headers' => $headers,

        ]);

        $statusCode = $resposta->getStatusCode();
        $body = $resposta->getBody()->getContents();

        parse_str($body, $responseData);

        try {
            //code...
            $log = new CustomLog;
            $log->content = json_encode($responseData);
            $log->user_id = $isBackoffice->id_user;
            $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idpedido'];
            $log->controller = "app/controller/EcommRegisterController";
            $log->http_code = 200;
            $log->route = "login_backoffice";
            $log->status = "SUCCESS";
            $log->save();
        } catch (\Throwable $th) {
            return;
        }

        return $responseData;
    }

    public function stockEdit(Request $request)
    {
        $id = $request->id;

        $product = Product::find($id);
        if (!isset($product)) {
            abort(404);
        }

        $product->stock = $product->getStock($product);

        $stock = Stock::where('product_id', $id)->orderBy('id', 'desc')->paginate(50);

        return view('admin.reports.stockEdit', compact('stock', 'product'));
    }

    public function stockUpdate(Request $request)
    {
        $id = $request->id;

        $product = Product::find($id);
        if (!isset($product)) {
            abort(404);
        }

        $oldStock = $product->getStock($product);
        $newStock = $request->stock;
        $diff = 0;
        $user = Auth::user();

        if ($oldStock > $newStock) {
            $diff = $oldStock - $newStock;

            $stock = new Stock;
            $stock->user_id = $user->id;
            $stock->product_id = $product->id;
            $stock->amount = -$diff;
            $stock->number_order = -1;
            $stock->ecommerce_externo = -1;
            $stock->save();
        } else {
            $diff = $newStock - $oldStock;

            $stock = new Stock;
            $stock->user_id = $user->id;
            $stock->product_id = $product->id;
            $stock->amount = $diff;
            $stock->number_order = -1;
            $stock->ecommerce_externo = -1;
            $stock->save();
        }

        $product->stock = $product->getStock($product);

        $stock = Stock::where('product_id', $id)->orderBy('id', 'desc')->paginate(50);

        return redirect()->route('admin.packages.stock_admin');
    }



}
