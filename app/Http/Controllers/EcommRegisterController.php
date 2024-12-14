<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\AddressBilling;
use App\Models\ChosenPickup;
use App\Models\CustomLog;
use App\Models\HistoricScore;
use App\Models\OrderPackage;
use App\Models\PaymentOrderEcomm;
use App\Models\PriceCoin;
use App\Models\Product;
use App\Models\Rede;
use App\Models\Stock;
use App\Models\PaymentLog;
use App\Models\Tax;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\EcommRegister;
use App\Models\EcommOrders;
use App\Models\OrderEcomm;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\MailValidate;
use App\Models\ShippingPrice;
use App\Models\NewsletterEcomm;
use App\Models\PickupPoint;
use App\Models\User;
use App\Models\AddressSecondary;
use App\Http\Requests\RegisterRequest;

class EcommRegisterController extends Controller
{

    public function sendEmailRegister($nome, $email, $user_id)
    {

        $client = new Client();

        $url = 'https://api.brevo.com/v3/smtp/email';

        $headers = [
            'Accept' => 'application/json',
            'api-key' => env('API_KEY_BREVO'),
            'Content-Type' => 'application/json',
        ];

        $data = [
            'sender' => [
                'name' => 'Pribonasorte',
                'email' => 'info@Pribonasorte.eu',
            ],
            'to' => [
                [
                    'email' => "$email",
                    'name' => "$nome",
                ],
            ],
            'subject' => 'Account Created Successfully',
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $nome
                                    <br>
                                    <br>
                                    Welcome to Pribonasorte!
                                    <br>
                                    <br>
                                    As a new customer, you've made a great choice by joining us. Our mission is to support your journey to optimal health and wellness with high-quality products.
                                    <br>
                                    <br>
                                    Explore our premium product range and feel free to reach out if you have any questions or need guidance. Our customer support team is here to assist you.
                                    <br>
                                    <br>
                                    If you decide to set up SMARTSHIP, our automatic monthly order feature, you'll enjoy a 10% discount on your regular orders, allowing you to regularly use our products and maintain a stock of them
                                    <br>
                                    <br>
                                    Join our Telegram group for updates: <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0</a>
                                    <br>
                                    <br>
                                    Also, be sure to follow us on social media to stay connected:<br>
                                    Instagram: <a href='https://www.instagram.com/Pribonasorte.eu/'>https://www.instagram.com/Pribonasorte.eu//</a> <br>
                                    Facebook: <a href='https://www.facebook.com/Pribonasorte.eu'>https://www.facebook.com/Pribonasorte.eu</a><br>
                                    TikTok: <a href='https://www.tiktok.com/@Pribonasorte.eu'>https://www.tiktok.com/@Pribonasorte.eu</a>
                                    <br>
                                    <br>
                                    Your health and well-being are important to us. Thank you for choosing Pribonasorte
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        JURAJ MOJZIS<br>
                                        CEO of Pribonasorte<br>
                                        Support- +420234688024<br>
                                        WHATSAPP number- +420 721 530 732
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $log = new CustomLog;
            $log->content = "Sended email";
            $log->user_id = $user_id;
            $log->operation = "SEND EMAIL REGISTER";
            $log->controller = "admin/ecommRegister";
            $log->http_code = 200;
            $log->route = "send.email.brevo";
            $log->status = "ERROR";
            $log->save();

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $user_id;
            $log->operation = "EMAIL REGISTRATION";
            $log->controller = "EcommRegisterController";
            $log->http_code = $th->getCode();
            $log->route = "finalize.register.order";
            $log->status = "ERROR";
            $log->save();
        }
    }


    public function RegisterUser(RegisterRequest $request)
    {
        $request->validated();

        $verific_register = EcommRegister::where('email', $request->email)->first();

        if (isset($verific_register)) {
            // return false;
            return redirect()->back()->with('erro', 'Email already registered');
        }


        $name_acent = array($request->name, $request->last_name, $request->city, $request->state ?? '', $request->country);
        $data_names = array();

        foreach ($name_acent as $new_name) {

            $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');

            $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');

            $sem_acento = str_replace($comAcentos, $semAcentos, $new_name);

            $acentos = array('`', '.', '_', ',', '^', '~');

            $clear_name = str_replace($acentos, '', $sem_acento);

            array_push($data_names, $clear_name);
        }

        // register new user
        $register = new EcommRegister;
        $register->name = $data_names[0]; //$request->name;
        $register->last_name = $data_names[1]; //$request->last_name;
        $register->email = $request->email;
        $register->corporate_name = $request->corporate_name;
        $register->fantasy_name = $request->fantasy_name;
        $register->id_corporate = $request->id_corporate;
        $register->identity_card = 0;
        // $register->birth = $request->birth;

        $register->sex = $request->sex;
        $register->phone = "+55 " . $request->phone;
        $register->zip = $request->zip;
        $register->address = $request->address;
        $register->number = $request->number;
        // $register->complement = $request->complement;
        // $register->neighborhood = $request->neighborhood;
        $register->city = $data_names[2]; //$request->city;
        $register->state = $data_names[3];
        // $register->state = '';
        $register->country = "BR"; //$request->country;



        $eliminar_espace = str_replace(' ', '', $request->username);

        $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');

        $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');

        $sem_acent = str_replace($comAcentos, $semAcentos, $eliminar_espace);

        $acentos = array('`', '.', '_', ',', '^', '~');


        $clear_acent = str_replace($acentos, '', $sem_acent);

        $new_usern = strtolower($clear_acent);


        $register->username = $new_usern;

        // password and encryption
        $password = trim($request->password);
        $checkpass = trim($request->checkpass);

        if ($password != $checkpass) {
            return redirect()->back()->with('erro', 'Passwords must be the same');
        }

        $pass_encrypt = Hash::make($password);
        $register->password = $pass_encrypt;

        $verification_recomm = User::where('login', $request->recommendation_user_id)->first();


        if ($verification_recomm) {
            $register->recommendation_user_id = $verification_recomm->id;
            $register->save();


            if ($request->address_shipping != "on") {
                $address2 = new AddressBilling;
                $address2->user_id = $register->id;
                $address2->backoffice = 0;
                $address2->country = $request->country;
                $address2->city = $request->city;
                $address2->zip = $request->zip;
                $address2->state = $request->state ?? '';
                $address2->address = $request->address;
                $address2->number_residence = $request->number;
                $address2->save();

                $editNewUser = EcommRegister::where('id', $register->id)->first();
                $editNewUser->address = $request->address2;
                $editNewUser->city = $request->city2;
                $editNewUser->zip = $request->zip2;
                $editNewUser->country = $request->country2;
                $editNewUser->state = $request->state2 ?? '';
                $editNewUser->number = $request->number2;
                $editNewUser->save();
            }


            $request->merge(['id_user' => $register->id]);
            session()->put('buyer', $register);

            $this->sendEmailRegister($register->name, $register->email, $register->id);
            $this->sendEmailForDistributor($register->id, $register->name, $register->username, $register->email, $register->phone, $register->recommendation_user_id);
            return redirect()->route('ecomm');


        } else {

            $ip_order = $_SERVER['REMOTE_ADDR'];
            $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();
            $count_order = count($order_cart);

            $ip_order = '194.156.125.61';

            $url = "http://ip-api.com/json/{$ip_order}?fields=country";

            $response = file_get_contents($url);
            $countryIpOrder = json_decode($response, true);
            $countryIpOrder = $countryIpOrder['country'] ?? "Czech Republic";
            $countryIp = ["country" => $countryIpOrder];

            $shipping = ShippingPrice::where('country', $countryIpOrder)->first();

            $allCountry = ShippingPrice::all();

            if (isset($shipping)) {
                $allowedRegister = true;
            } else {
                $allowedRegister = false;
            }

            $code_tel = $shipping->code;

            session()->flash('error', 'Referral user not found!');
            return view('ecomm.ecomm_register', compact('count_order', 'allowedRegister', 'allCountry', 'code_tel'));
        }

    }


    // VAMOS REFATORAR ACIMA

    public function RegisteredShipping()
    {
        $shipping = new PickupPoint;
        $shipping->country = "Poland";
        $shipping->kg2 = 5.71;
        $shipping->kg5 = 5.83;
        $shipping->kg10 = 6.50;
        $shipping->kg20 = 7.38;
        $shipping->kg31_5 = 8.31;

        $shipping->save();


        exit();
    }

    public function RegisterOrder(Request $request, $data = [])
    {


        if ($request->new_user === 'true') {
            $created = $this->RegisterUser($request);

            if (!$created) {
                return redirect()->back()->with('erro', 'Email already registered');
            }
            return redirect()->back()->with('message', 'Created successfully');
        }

        $ip_order = $_SERVER['REMOTE_ADDR'];

        // redeem products from user cart
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();

        // check if there is already a registration with the email
        if (!empty($request->email) || !empty($request->id_user_login)) {

            if (!empty($request->email)) {
                $verific_register = EcommRegister::where('email', $request->email)->first();
            } else {
                $verific_register = EcommRegister::where('id', $request->id_user_login)->first();
            }

            if ($verific_register) {

                if (isset($data['order'])) {
                    $numb_order = $data['order'];
                } else {
                    $numb_order = $this->genNumberOrder();
                }

                $newPayment = new PaymentOrderEcomm;
                $newPayment->id_user = $verific_register->id;
                $newPayment->id_payment_gateway = $data["id_payment"];
                $newPayment->id_invoice_trans = $data["id_invoice_trans"];
                $newPayment->status = strtolower($data["status"]);
                $newPayment->total_price = $data["total_price"];
                $newPayment->number_order = $numb_order;
                $newPayment->payment_method = $data["method"];
                $newPayment->save();

                $maiorVat = 0;

                foreach ($order_cart as $order) {
                    $countryOrder = $data["country"];
                    $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();

                    if (isset($taxValue)) {
                        $tax = $taxValue->value;
                        if ($tax > $maiorVat) {
                            $maiorVat = $tax;
                        }
                    }
                }

                foreach ($order_cart as $order) {
                    $countryOrder = $data["country"];
                    $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();
                    $price_product = Product::where('id', $order->id_product)->first();

                    $total_VAT = 0;
                    $order_total = 0;

                    if (isset($taxValue)) {
                        $tax = $taxValue->value;
                        if ($tax > 0) {
                            // dd($taxValue);
                            if (isset($data["smartshipping"]) and $data["smartshipping"] == 1) {
                                $total_VAT += (($tax / 100) * $price_product->premium_price) * $order->amount;
                                $order_total += $price_product->premium_price * $order->amount;
                            } else {
                                $total_VAT += (($tax / 100) * $price_product->price) * $order->amount;
                                $order_total += $price_product->price * $order->amount;
                            }
                        } else {
                            if (isset($data["smartshipping"]) and $data["smartshipping"] == 1) {
                                $order_total += $price_product->premium_price * $order->amount;
                            } else {
                                $order_total += $price_product->price * $order->amount;
                            }
                        }
                    }
                    // dd($data);

                    if ($price_product->qv > 0) {
                        $qv = $price_product->qv * $order->amount;
                    } else {
                        $qv = 0;
                    }

                    if ($price_product->cv > 0) {
                        $cv = $price_product->cv * $order->amount;
                    } else {
                        $cv = 0;
                    }

                    $orders = new EcommOrders;

                    if (isset($data["method_shipping"]) and !empty($data["method_shipping"])) {
                        $method_shipp = $data["method_shipping"];
                    } else {
                        $method_shipp = "home1";
                    }

                    $orders->number_order = $numb_order;
                    $orders->id_user = $verific_register->id;
                    $orders->id_product = $order->id_product;
                    $orders->amount = $order->amount;
                    $orders->total = $order_total;
                    $orders->total_vat = $total_VAT;
                    $orders->total_shipping = $data['total_shipping'];
                    $orders->status_order = "order placed";
                    $orders->client_backoffice = 0;
                    $orders->vat_product_percentage = $taxValue->value;
                    $orders->vat_shipping_percentage = $maiorVat;
                    $orders->qv = $qv;
                    $orders->cv = $cv;
                    $orders->id_payment_order = $newPayment->id;
                    $orders->method_shipping = $method_shipp;

                    if (isset($data["smartshipping"]) and !empty($data["smartshipping"])) {
                        $orders->smartshipping = $data["smartshipping"];
                    } else {
                        $orders->smartshipping = 0;
                    }


                    $orders->save();

                    if ($price_product->kit == 2) {
                        $parts = explode('|', $price_product->kit_produtos); // Divide a string pelo caractere "|"

                        foreach ($parts as $part) {
                            list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                            $separado[$id_produto] = $quantidade;

                            $stock = new Stock;
                            $stock->user_id = $verific_register->id;
                            $stock->product_id = $id_produto;
                            $stock->amount = -$order->amount * $quantidade;
                            $stock->number_order = $numb_order;
                            $stock->ecommerce_externo = 1;
                            $stock->save();
                        }


                    } else if ($price_product->kit == 0 || $price_product->kit == 1) {
                        $stock = new Stock;
                        $stock->user_id = $verific_register->id;
                        $stock->product_id = $order->id_product;
                        $stock->amount = -$order->amount;
                        $stock->number_order = $numb_order;
                        $stock->ecommerce_externo = 1;
                        $stock->save();
                    }

                }

                // clear cart
                foreach ($order_cart as $order_del) {
                    OrderEcomm::findOrFail($order_del->id)->delete();
                }

                session()->put('redirect_buy', 'ecomm');
                $url = $data['url'];
                return redirect()->away($url);
            }
        }


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

        $maiorVat = 0;

        foreach ($order_cart as $order) {
            $countryOrder = $data["country"];
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();

            if (isset($taxValue)) {
                $tax = $taxValue->value;
                if ($tax > $maiorVat) {
                    $maiorVat = $tax;
                }
            }
        }

        foreach ($order_cart as $order) {
            $countryOrder = $data["country"] ?? $user_new->country;
            $taxValue = Tax::where('product_id', $order->id_product)->where('country', $countryOrder)->first();
            $price_product = Product::where('id', $order->id_product)->first();

            $total_VAT = 0;
            $order_total = 0;

            if (isset($taxValue)) {
                $tax = $taxValue->value;
                if ($tax > 0) {
                    # code...
                    if (isset($data["smartshipping"]) and $data["smartshipping"] == 1) {
                        $total_VAT += (($tax / 100) * $price_product->premium_price) * $order->amount;
                        $order_total += $price_product->premium_price * $order->amount;
                    } else {
                        $total_VAT += (($tax / 100) * $price_product->price) * $order->amount;
                        $order_total += $price_product->price * $order->amount;
                    }
                } else {
                    if (isset($data["smartshipping"]) and $data["smartshipping"] == 1) {
                        $order_total += $price_product->premium_price * $order->amount;
                    } else {
                        $order_total += $price_product->price * $order->amount;
                    }
                }
            }

            // dd($total_VAT);

            if ($price_product->qv > 0) {
                $qv = $price_product->qv * $order->amount;
            } else {
                $qv = 0;
            }

            if ($price_product->cv > 0) {
                $cv = $price_product->cv * $order->amount;
            } else {
                $cv = 0;
            }

            $orders = new EcommOrders;

            $orders->number_order = $numb_order;
            $orders->id_user = $user_new->id;
            $orders->id_product = $order->id_product;
            $orders->amount = $order->amount;
            $orders->total = $order_total;
            $orders->total_vat = $total_VAT;
            $orders->total_shipping = $data['total_shipping'];
            $orders->status_order = "order placed";
            $orders->id_payment_order = $newPayment->id;
            $orders->vat_product_percentage = $taxValue->value;
            $orders->vat_shipping_percentage = $maiorVat;
            $orders->qv = $qv;
            $orders->cv = $cv;
            $orders->client_backoffice = 0;
            if (isset($data["smartshipping"]) and !empty($data["smartshipping"])) {
                $orders->smartshipping = $data["smartshipping"];
            } else {
                $orders->smartshipping = 0;
            }
            $orders->save();

            if ($price_product->kit == 2) {
                $parts = explode('|', $price_product->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                    $separado[$id_produto] = $quantidade;

                    $stock = new Stock;
                    $stock->user_id = $user_new->id;
                    $stock->product_id = $id_produto;
                    $stock->amount = -$order->amount * $quantidade;
                    $stock->number_order = $numb_order;
                    $stock->ecommerce_externo = 1;
                    $stock->save();
                }

            } else if ($price_product->kit == 0 || $price_product->kit == 1) {
                $stock = new Stock;
                $stock->user_id = $user_new->id;
                $stock->product_id = $order->id_product;
                $stock->amount = -$order->amount;
                $stock->number_order = $numb_order;
                $stock->ecommerce_externo = 1;
                $stock->save();
            }

        }

        // clear cart
        foreach ($order_cart as $order_del) {
            OrderEcomm::findOrFail($order_del->id)->delete();
        }

        // return redirect()->route('page.login.ecomm');

        session()->put('redirect_buy', 'ecomm');
        $url = $data['url'];

        // return redirect()->route('ecomm');

        return redirect()->away($url);
    }

    public function LogUserEcomm(Request $request)
    {
        $email = trim($request->input('email'));
        $password = trim($request->input('password'));

        $user = EcommRegister::where('email', $email)->first();

        // check entries
        if (!$user) {
            session()->flash('error', 'The user does not exist!');
            return redirect()->route('page.login.ecomm');
        }

        if (!Hash::check($password, $user->password)) {
            session()->flash('error', 'The password is incorrect!');
            return redirect()->route('page.login.ecomm');
        }

        //create user session
        session()->put('buyer', $user);

        $ip_order = $_SERVER['REMOTE_ADDR'];

        $ordersAll = OrderEcomm::where('ip_order', $ip_order)->get();

        foreach ($ordersAll as $order) {
            OrderEcomm::findOrFail($order->id)->delete();
        }

        if ($request->headers->get('referer') === route('finalize.shop')) {
            return redirect()->back();
        }

        return redirect()->route('ecomm');
    }

    public function logOutUser()
    {
        session()->forget('buyer');
        return redirect()->route('page.login.ecomm');
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

    public function PayCrypto(Request $request)
    {
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();

        if (count($order_cart) < 1) {
            return redirect()->route('ecomm');
        }

        $n_order = $this->genNumberOrder();
        $name = "Pribonasorte";
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

        try {

            $paymentConfig = [
                "api_url" => "https://crypto.binfinitybank.com/packages/wallets/notify",
                "email" => 'master@tec2u.com.br',
                "password" => "password",
            ];
            $method = $request->payment;

            $mtPay = $method;

            if (strtolower($method) == "btc") {
                $mtPay = "BITCOIN";
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
                    "coin": "' . $mtPay . '",
                    "notify_url" : "' . $url . '"

                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                )
            );

            $raw = json_decode(curl_exec($curl));

            curl_close($curl);



            if (isset($raw->merchant_id) && isset($raw->id)) {

                if ($request->method_shipping == 'pickup') {
                    if (
                        empty($request->id_ppl) ||
                        empty($request->accessPointType) ||
                        empty($total_shipping) ||
                        empty($request->dhlPsId)
                    ) {
                        return redirect()->back()->with('error', 'Select pickup address');
                    }

                    $this->registerChosenPickup($request, $n_order);
                    $paisNome = ShippingPrice::where('country_code', $request->country_ppl)->first()->country;
                    $country = $paisNome;
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
                        return redirect()->back()->with('error', 'Fill in all fields at new address');
                    }

                    $this->RegisteredAddressSecondary($request);
                    $country = $request->country;
                }

                if ($request->method_shipping == 'home1') {
                    $country = EcommRegister::where('id', $request->id_user_login)->first()->country;
                }



                $data = [
                    "country" => $country ?? $paisNome ?? null,
                    'order' => $n_order,
                    "total_vat" => $total_vat,
                    "id_invoice_trans" => $raw->merchant_id,
                    // "smartshipping" => $request->smartshipping,
                    "method_shipping" => $request->method_shipping,
                    'total_shipping' => $total_shipping,
                    "url" => "https://crypto.binfinitybank.com/invoice/" . $raw->id,
                    "id_payment" => $raw->id,
                    "status" => "Pending",
                    "method" => $method,
                    "total_price" => $price
                ];

                // dd($data);

                return $this->RegisterOrder($request, $data);
            } else {

                return redirect()->back();
            }

        } catch (Exception $e) {
            flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->back();
            // return redirect()->route('packages.detail', ['id' => $package->id]);
        }
    }

    public function RetryPayCrypt(Request $request)
    {
        $payment = PaymentOrderEcomm::where('id', $request->id_payment)->first();
        $message_status = strtolower($payment->status);
        if ($message_status === 'expired' || $message_status === 'cancelled') {

            $name = "Pribonasorte";
            // $price = floatval(str_replace(',', '.', $payment->total_price));
            $price = $payment->total_price;

            try {

                $paymentConfig = [
                    "api_url" => "https://crypto.binfinitybank.com/packages/wallets/notify",
                    "email" => 'master@tec2u.com.br',
                    "password" => "password",
                ];

                $curl = curl_init();
                // $urlRedirect = $request->getScheme() . '://' . $request->getHost() . '/ecomm';
                // $url = $request->getScheme() . '://' . $request->getHost() . '/ecomm/finalize/notify';
                $url = "https://Pribonasorte.eu/ecomm/finalize/notify";

                $method = "BITCOIN";

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
                        "custom_data1" : "' . $request->id_payment . '"
                }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    )
                );

                $raw = json_decode(curl_exec($curl));

                curl_close($curl);

                session()->put('redirect_buy', $request->redirect_buy);

                if (isset($raw->merchant_id) && isset($raw->id)) {
                    $url = "https://crypto.binfinitybank.com/invoice/" . $raw->id;
                    $payment->status = 'Pending';
                    $payment->save();

                    return redirect()->away($url);
                } else {

                    return redirect()->back();
                }


            } catch (Exception $e) {
                flash(__('backoffice_alert.unable_to_process_your_order'))->error();
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function PayComgate(Request $request)
    {
        // dd($request);
        $ip_order = $_SERVER['REMOTE_ADDR'];
        $order_cart = OrderEcomm::where('ip_order', $ip_order)->get();

        if (count($order_cart) < 1) {
            return redirect()->route('ecomm');
        }

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


        if (isset(session('buyer')['email'])) {
            $email_pay = session('buyer')['email'];
        } else {
            $email_pay = "juro@trupikstar.sk";
        }

        $dominio = $request->getHost();
        if (strtolower($dominio) == 'Pribonasorte.eu') {
            $testComgate = 'false';
        } else {
            $testComgate = 'false';
        }

        $url = '';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => str_replace('.', '', $newPrice),
            'curr' => 'EUR',
            'label' => "Order $n_order",
            'email' => "$email_pay",
            'refId' => $n_order,
            'method' => "$request->methodPayment",
            'prepareOnly' => 'true',
            'test' => "$testComgate",
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

        if (isset($responseData)) {


            $jsonResponse = [
                'code' => $responseData['code'],
                'message' => $responseData['message'],
                'transId' => $responseData['transId'],
                'redirect' => urldecode($responseData['redirect']),
            ];

            if ($request->method_shipping == 'pickup') {
                if (
                    empty($request->id_ppl) ||
                    empty($request->accessPointType) ||
                    empty($request->dhlPsId)
                ) {
                    return redirect()->back()->with('error', 'Select pickup address');
                }

                $this->registerChosenPickup($request, $n_order);
                $paisNome = ShippingPrice::where('country_code', $request->country_ppl)->first()->country;
                $country = $paisNome;

            }

            if ($request->method_shipping == 'home2') {
                // dd($request);
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
                    return redirect()->back()->with('error', 'Fill in all fields at new address');
                }

                $this->RegisteredAddressSecondary($request);
                $country = $request->country;
            }

            if ($request->method_shipping == 'home1') {
                $country = EcommRegister::where('id', $request->id_user_login)->first()->country;
            }

            $data = [
                "country" => $country ?? $paisNome ?? null,
                "total_vat" => $total_vat,
                "id_invoice_trans" => $jsonResponse['transId'],
                // "smartshipping" => $request->smartshipping,
                "method_shipping" => $request->method_shipping,
                "url" => $jsonResponse['redirect'],
                'total_shipping' => $total_shipping,
                "id_payment" => $n_order,
                "status" => 'pending',
                "total_price" => $price,
                "method" => $request->methodPayment,
                'order' => $n_order,
            ];
            // dd($data);

            // $id_user = session('buyer')['id'];
            // $register_user = EcommOrders::where('id_user', $id_user);
            // $register_user->update([
            //     'method_shipping' => 1,
            // ]);
            // dd($data);
            return $this->RegisterOrder($request, $data);
        } else {
            return redirect()->back();
        }
    }

    public function RetryPayComgate(Request $request)
    {
        $payment = PaymentOrderEcomm::where('id', $request->id_payment)->first();

        $dominio = $request->getHost();
        if (strtolower($dominio) == 'Pribonasorte.eu') {
            $testComgate = 'false';
        } else {
            $testComgate = 'false';
        }

        $url = '';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => str_replace('.', '', $payment->total_price),
            'curr' => 'EUR',
            'label' => "Order $request->order",
            'email' => 'juro@trupikstar.sk',
            'refId' => $request->order,
            'method' => "$request->payment",
            'prepareOnly' => 'true',
            'test' => "$testComgate",
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

        session()->put('redirect_buy', $request->redirect_buy);

        if (isset($responseData)) {
            $payment->id_payment_gateway = $request->order;
            $payment->status = 'pending';
            $payment->payment_method = $request->payment;
            $payment->id_invoice_trans = $responseData['transId'];
            $payment->save();

            return redirect()->away(urldecode($responseData['redirect']));
        } else {
            return redirect()->back();
        }
    }

    public function payPackageProduct(Request $request, $requestFormated)
    {
        try {
            if (isset($requestFormated["node"])) {

                $payment = PaymentOrderEcomm::where('id_payment_gateway', $requestFormated["id"])
                    ->whereRaw('LOWER(status) != ?', ['paid'])
                    ->first();

                $package = OrderPackage::where('transaction_code', $requestFormated["id"])
                    ->where('payment_status', '<>', 1)
                    ->first();

                if (isset($payment) && isset($package)) {
                    # code...
                    if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                        $payment->status = 'paid';
                    }


                    if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                        $payment->status = 'cancelled';
                    }

                    $payment->save();

                    if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                        if (isset($payment)) {
                            $this->sendEmailForPayedProduct($payment->number_order);
                            $this->sendPostBonificacao($payment->number_order, 1);
                        }
                    }


                    $log = new PaymentLog;
                    $log->content = 'https://crypto.binfinitybank.com/invoice/' . $requestFormated["id"];
                    $log->order_package_id = $payment->id;
                    $log->operation = "payment";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();


                    if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                        $package->payment_status = 1;
                        $package->status = 1;
                        $this->sendEmailPayedPackage($package->user_id);
                    }

                    if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                        $package->payment_status = 2;
                        $package->status = 0;
                    }

                    $package->save();

                    $log = new PaymentLog;
                    $log->content = $requestFormated["status"];
                    $log->order_package_id = $package->id;
                    $log->operation = "payment package";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();

                    return true;
                } else {
                    return false;
                }

            } else if (isset($requestFormated["transId"])) {
                $payment = PaymentOrderEcomm::where('id_invoice_trans', $requestFormated["transId"])
                    ->where('id_payment_gateway', $requestFormated['refId'])
                    ->whereRaw('LOWER(status) != ?', ['paid'])
                    ->first();

                $package = OrderPackage::where('transaction_code', $requestFormated["transId"])
                    ->orWhere('transaction_wallet', $requestFormated["transId"])
                    ->where('payment_status', '<>', 1)
                    ->first();

                if (isset($payment) && isset($package)) {
                    # code...

                    $isSmart = EcommOrders::where('id_payment_order', $payment->id)->first();

                    $price = number_format($requestFormated["price"] / 100, 2, '.', '');

                    if (strtolower($requestFormated["status"]) == 'paid') {
                        $payment->total_paid = $price;
                        $this->sendEmailForPayedProduct($payment->number_order);
                        if (strtolower($requestFormated["test"]) == 'false') {
                            $this->sendPostBonificacao($payment->number_order, 1);
                        } else {
                            $this->sendPostBonificacao($payment->number_order, 0);
                        }

                        if (isset($isSmart)) {
                            if ($isSmart->smartshipping == 1) {
                                if ($isSmart->client_backoffice == 1) {
                                    $user = User::where('id', $isSmart->id_user)->first();
                                    $user->smartshipping = 1;
                                    $email = $user->email;
                                    $user->save();
                                } else {
                                    $user = EcommRegister::where('id', $isSmart->id_user)->first();
                                    $user->smartshipping = 1;
                                    $email = $user->email;
                                    $user->save();
                                }
                            }
                        }
                    }

                    $payment->status = strtolower($requestFormated["status"]);
                    $payment->payment_method = $requestFormated["method"];
                    $payment->save();

                    $log = new PaymentLog;
                    $log->content = $requestFormated["status"];
                    $log->order_package_id = $payment->id;
                    $log->operation = "payment";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();

                    # code...

                    if (isset($requestFormated['method'])) {
                        $package->payment_method = $requestFormated["method"];
                    }

                    if (strtolower($requestFormated["status"]) == 'paid') {
                        $package->payment_status = 1;
                        $package->status = 1;
                        $this->sendEmailPayedPackage($package->user_id);
                    }

                    if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                        $package->payment_status = 2;
                        $package->status = 0;
                    }

                    $package->save();

                    $log = new PaymentLog;
                    $log->content = $requestFormated["status"];
                    $log->order_package_id = $package->id;
                    $log->operation = "payment package";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();

                    return true;
                } else {
                    return false;
                }
            }

            // dd('oi');
            return true;
        } catch (\Throwable $th) {
            // dd($th);
            return false;
        }
    }

    public function sendEmailPayedPackage($user_id)
    {
        $user = User::where('id', $user_id)->first();

        $client = new Client();

        $url = 'https://api.brevo.com/v3/smtp/email';

        $headers = [
            'Accept' => 'application/json',
            'api-key' => env('API_KEY_BREVO'),
            'Content-Type' => 'application/json',
        ];

        $data = [
            'sender' => [
                'name' => 'Pribonasorte',
                'email' => 'info@Pribonasorte.eu',
            ],
            'to' => [
                [
                    'email' => "$user->email",
                    'name' => "$user->name",
                ],
            ],
            'subject' => 'Package Payed',
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $user->name
                                    <br>
                                    <br>
                                    Welcome to Pribonasorte!
                                    <br>
                                    <br>
                                    We're thrilled to have you on board as a Pribonasorte Distributor! Congratulations on making the decision to join our community. You're now an active distributor, and you can start registering new distributors and customers.
                                    <br>
                                    <br>
                                    To get started, simply go to your DASHBOARD and find the registration link at the bottom. This is your opportunity to expand your network and make a positive impact on the health and prosperity of others.
                                    <br>
                                    <br>
                                    As an active distributor, you also have access to our premium products. We recommend trying out our products with an order of 100QV or more. This way, you can experience all our offerings and have products on hand for your family, friends, potential distributors, or customers. After all, you can't open a florist shop without any flowers!
                                    <br>
                                    <br>
                                    In the world of MLM, we often talk about passive income, and here's a valuable tip: setting up a SMARTSHIP order is key to achieving that passive income. It's the backbone of your long-term success in network marketing.
                                    <br>
                                    <br>
                                    Join our vibrant community dedicated to well-being and prosperity. We're here to support you every step of the way.
                                    <br>
                                    <br>
                                    <strong>
                                    Communication is key, and it's very important to follow us on all social media platforms. Immediately after registration, the first steps should be to follow us on our private Facebook group and Telegram, or WhatsApp:
                                    </strong>
                                    <br>
                                    <br>
                                    <ul>
                                       <li>Private Facebook Group <a href='https://www.facebook.com/groups/467430965762866'>https://www.facebook.com/groups/467430965762866/</a> </li>
                                       <li>Telegram <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0/</a> </li>
                                       <li>WhatsApp <a href='https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j'>https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    Additionally, you can stay connected through:
                                    <br>
                                    <br>
                                     <ul>
                                       <li>Instagram <a href='https://www.instagram.com/Pribonasorte_official'>https://www.instagram.com/Pribonasorte_official/</a> </li>
                                       <li>Facebook <a href='https://www.facebook.com/profile.php?id=61557861185751'>https://www.facebook.com/profile.php?id=61557861185751/</a> </li>
                                       <li>YouTube <a href='https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ'>https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        JURAJ MOJZIS<br>
                                        CEO of Pribonasorte<br>
                                        Support- +420234688024<br>
                                        WHATSAPP number- +420 721 530 732
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $log = new CustomLog;
            $log->content = "Sended email";
            $log->user_id = $user_id;
            $log->operation = "SEND EMAIL PACKAGE PAYED";
            $log->controller = "admin/ecommRegister";
            $log->http_code = 200;
            $log->route = "send.email.brevo";
            $log->status = "ERROR";
            $log->save();

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $user_id;
            $log->operation = "EMAIL PACKAGE PAYED";
            $log->controller = "EcommRegisterController";
            $log->http_code = $th->getCode();
            $log->route = "finalize.notify";
            $log->status = "ERROR";
            $log->save();
        }
    }

    public function notify(Request $request)
    {
        try {
            //code...

            $requestFormated = $request->all();
            $packagrProduct = $this->payPackageProduct($request, $requestFormated);
            if ($packagrProduct == true) {
                return response("OK", 200);
            }

            // crypto
            if (isset($requestFormated["node"])) {
                if (isset($requestFormated["custom_data1"]) && $requestFormated["custom_data1"] !== "") {
                    $payment = PaymentOrderEcomm::where('id', $requestFormated["custom_data1"])
                        ->whereRaw('LOWER(status) != ?', ['paid'])
                        ->first();

                    if (isset($payment)) {
                        # code...

                        $payment->id_payment_gateway = $requestFormated["id"];
                        $payment->status = strtolower($requestFormated["status"]);
                        $payment->id_invoice_trans = $requestFormated["merchant_id"];

                        if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                            $payment->status = 'paid';
                        }

                        if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                            $payment->status = 'cancelled';
                        }

                        $payment->save();

                        if (strtolower($requestFormated["status"]) == 'paid' && isset($payment)) {
                            $this->sendEmailForPayedProduct($payment->number_order);
                            $this->sendPostBonificacao($payment->number_order, 1);
                        }
                    }

                }
                if (isset($requestFormated["custom_data2"]) && $requestFormated["custom_data2"] == 'package') {
                    $payment = OrderPackage::where('transaction_code', $requestFormated["id"])
                        ->where('payment_status', '<>', 1)
                        ->first();
                    if (isset($payment)) {

                        if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                            $payment->payment_status = 1;
                            $payment->status = 1;
                            $this->sendEmailPayedPackage($payment->user_id);
                        }

                        if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                            $payment->payment_status = 2;
                            $payment->status = 0;
                        }

                        $payment->save();

                        $log = new PaymentLog;
                        $log->content = $requestFormated["status"];
                        $log->order_package_id = $payment->id;
                        $log->operation = "payment package";
                        $log->controller = "EcommRegisterController";
                        $log->http_code = "200";
                        $log->route = "/ecomm/finalize/notify";
                        $log->status = "success";
                        $log->json = json_encode($request->all());
                        $log->save();
                    }

                } else {
                    $payment = PaymentOrderEcomm::where('id_payment_gateway', $requestFormated["id"])
                        ->whereRaw('LOWER(status) != ?', ['paid'])
                        ->first();
                    if (isset($payment)) {

                        if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                            $payment->status = 'paid';
                        }

                        if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                            $payment->status = 'cancelled';
                        }


                        $payment->save();

                        if (strtolower($requestFormated["status"]) == 'paid' || strtolower($requestFormated["status"]) == 'overpaid' || strtolower($requestFormated["status"]) == 'underpaid') {
                            $this->sendEmailForPayedProduct($payment->number_order);
                            $this->sendPostBonificacao($payment->number_order, 1);
                        }

                    }

                }


                if (isset($payment)) {

                    $log = new PaymentLog;
                    $log->content = 'https://crypto.binfinitybank.com/invoice/' . $requestFormated["id"];
                    $log->order_package_id = $payment->id;
                    $log->operation = "payment";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();

                }
            }

            // comgate
            if (isset($requestFormated["transId"])) {

                $parts = explode(" ", $requestFormated["refId"]);

                if (isset($parts[0]) && $parts[0] === "package") {
                    $payment = OrderPackage::where('transaction_code', $requestFormated["transId"])
                        ->where('payment_status', '<>', 1)
                        ->first();

                    if (isset($requestFormated['method'])) {
                        $payment->payment_method = $requestFormated["method"];
                    }

                    if (strtolower($requestFormated["status"]) == 'paid' && isset($payment)) {
                        $payment->payment_status = 1;
                        $payment->status = 1;
                        $this->sendEmailPayedPackage($payment->user_id);
                    }

                    if (strtolower($requestFormated["status"]) == 'cancelled' || strtolower($requestFormated["status"]) == 'expired') {
                        $payment->payment_status = 2;
                        $payment->status = 0;
                    }

                    $payment->save();

                    $log = new PaymentLog;
                    $log->content = $requestFormated["status"];
                    $log->order_package_id = $payment->id;
                    $log->operation = "payment package";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();

                } else {

                    $payment = PaymentOrderEcomm::where('id_invoice_trans', $requestFormated["transId"])
                        ->where('id_payment_gateway', $requestFormated['refId'])
                        ->whereRaw('LOWER(status) != ?', ['paid'])
                        ->first();

                    $isSmart = EcommOrders::where('id_payment_order', $payment->id)->first();

                    $price = number_format($requestFormated["price"] / 100, 2, '.', '');

                    if (strtolower($requestFormated["status"]) == 'paid' && isset($payment)) {
                        $payment->total_paid = $price;
                        $this->sendEmailForPayedProduct($payment->number_order);
                        if (strtolower($requestFormated["test"]) == 'false') {
                            $this->sendPostBonificacao($payment->number_order, 1);
                        } else {
                            $this->sendPostBonificacao($payment->number_order, 0);
                        }

                        if (isset($isSmart)) {
                            if ($isSmart->smartshipping == 1) {
                                if ($isSmart->client_backoffice == 1) {
                                    $user = User::where('id', $isSmart->id_user)->first();
                                    $user->smartshipping = 1;
                                    $email = $user->email;
                                    $user->save();
                                } else {
                                    $user = EcommRegister::where('id', $isSmart->id_user)->first();
                                    $user->smartshipping = 1;
                                    $email = $user->email;
                                    $user->save();
                                }



                            }
                        }
                    }

                    $payment->status = strtolower($requestFormated["status"]);
                    $payment->payment_method = $requestFormated["method"];
                    $payment->save();

                    $log = new PaymentLog;
                    $log->content = $requestFormated["status"];
                    $log->order_package_id = $payment->id;
                    $log->operation = "payment";
                    $log->controller = "EcommRegisterController";
                    $log->http_code = "200";
                    $log->route = "/ecomm/finalize/notify";
                    $log->status = "success";
                    $log->json = json_encode($request->all());
                    $log->save();
                }
            }



        } catch (\Throwable $th) {
            $log = new PaymentLog;
            $log->content = $requestFormated["status"];
            $log->order_package_id = 1;
            $log->operation = "error in payment package - error occurred";
            $log->controller = "EcommRegisterController";
            $log->http_code = "200";
            $log->route = "/ecomm/finalize/notify";
            $log->status = "success";
            $log->json = json_encode($th->getMessage());
            $log->save();

            $log = new PaymentLog;
            $log->content = $requestFormated["status"];
            $log->order_package_id = 1;
            $log->operation = "error payment package - content";
            $log->controller = "EcommRegisterController";
            $log->http_code = "200";
            $log->route = "/ecomm/finalize/notify";
            $log->status = "success";
            $log->json = json_encode($request->all());
            $log->save();

        }

        return response("OK", 200);
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

    public function UpdateRegister(Request $request)
    {
        $register = EcommRegister::findOrFail($request->id);
        $register->update([
            'name' => $request->name,
            'username' => $request->username,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'corporate_name' => $request->corporate_name,
            'fantasy_name' => $request->fantasy_name,
            'id_corporate' => $request->id_corporate,
            'identity_card' => $request->identity_card,
            'birth' => $request->birth,
            'sex' => $request->sex,
            'phone' => $request->phone,
            'zip' => $request->zip,
            'address' => $request->address,
            'number' => $request->number,
            'city' => $request->city,
            'state' => '',
            'country' => $request->country,
        ]);

        // password and encryption
        $password = trim($request->password);
        $checkpass = trim($request->checkpass);

        if (!empty($password) and !empty($checkpass)) {


            if ($password != $checkpass) {

                session()->flash('erro', 'Passwords do not match!');
                return redirect()->back();

            }

            $pass_encrypt = Hash::make($password);
            $register->update(['password' => $pass_encrypt]);
        }

        if (
            isset($request->address_billing) &&
            isset($request->number_billing) &&
            isset($request->city_billing) &&
            isset($request->zip_billing) &&
            // isset($request->state_billing) &&
            isset($request->country_billing)
        ) {

            $addressBilling = AddressBilling::where('user_id', $request->id)->where('backoffice', 0)->first();
            if (isset($addressBilling)) {
                $addressBilling->address = $request->address_billing;
                $addressBilling->number_residence = $request->number_billing;
                $addressBilling->city = $request->city_billing;
                $addressBilling->zip = $request->zip_billing;
                $addressBilling->state = '';
                $addressBilling->country = $request->country_billing;
                $addressBilling->save();
            } else {
                $address2 = new AddressBilling;
                $address2->user_id = $register->id;
                $address2->backoffice = 0;
                $address2->address = $request->address_billing;
                $address2->number_residence = $request->number_billing;
                $address2->city = $request->city_billing;
                $address2->zip = $request->zip_billing;
                $address2->state = '';
                $address2->country = $request->country_billing;
                $address2->save();
            }
        }

        return redirect()->back();
    }

    public function UpdateSecondRegister(Request $request)
    {
        if (session()->has('buyer')) {

            $userLogin = session()->get('buyer');

            $adress = AddressSecondary::where('id_user', $userLogin->id)->where('backoffice', 0)->first();

            if (isset($adress)) {
                $adress->phone = $request->cell;
                $adress->first_name = $request->first_name;
                $adress->last_name = $request->last_name;
                $adress->zip = $request->zip;
                $adress->address = $request->address;
                $adress->number = $request->number;
                $adress->phone = $request->phone;
                $adress->complement = $request->complement;
                $adress->neighborhood = $request->neighborhood;
                $adress->city = $request->city;
                $adress->state = $request->state ?? '';
                $adress->country = $request->country;

                $adress->save();
            } else {
                $adress = new AddressSecondary;

                $adress->id_user = $userLogin->id;
                $adress->first_name = $request->first_name;
                $adress->last_name = $request->last_name;
                $adress->phone = $request->cell;
                $adress->zip = $request->zip;
                $adress->address = $request->address;
                $adress->number = $request->number;
                $adress->phone = $request->phone;
                $adress->complement = $request->complement;
                $adress->neighborhood = $request->neighborhood;
                $adress->city = $request->city;
                $adress->state = $request->state ?? '';
                $adress->country = $request->country;
                $adress->backoffice = 0;

                $adress->save();
            }

            return redirect()->back();
        } else {
            return redirect()->route('page.login.ecomm');
        }
    }

    public function RegisteredUser(Request $request)
    {
        // register new user
        $register = new EcommRegister;
        $register->name = $request->name;
        $register->email = $request->email;
        $register->corporate_name = $request->corporate_name;
        $register->fantasy_name = $request->fantasy_name;
        $register->cnpj = $request->cnpj;
        $register->cpf = $request->cpf;
        $register->last_name = $request->last_name;
        $register->birth = $request->birth;
        $register->sex = $request->sex;
        $register->phone = $request->phone;
        $register->zip = $request->zip;
        $register->address = $request->address;
        $register->number = $request->number;
        $register->complement = $request->complement;
        $register->neighborhood = $request->neighborhood;
        $register->city = $request->city;
        $register->state = $request->state ?? '';
        $register->country = $request->country;

        // password and encryption
        $password = trim($request->password);
        $checkpass = trim($request->checkpass);

        if ($password != $checkpass) {

            session()->flash('erro', 'Passwords do not match!');
            return redirect()->route('finalize.shop');
        }

        $pass_encrypt = Hash::make($password);
        $register->password = $pass_encrypt;

        $register->save();

        session()->flash('erro', 'Account created successfully!');
        return redirect()->route('page.login.ecomm');
    }

    public function RecoverValidade(Request $request)
    {
        $register = EcommRegister::where('email', $request->email)->first();

        if (isset($register)) {

            $update_user = EcommRegister::find($register->id);

            $code = random_int(100000, 999999);

            $update_user->update([
                'replice_code' => $code,
            ]);

            $capture_code = EcommRegister::find($update_user->id);

            $this->sendEmailRecover($register->name, $register->email, $register->id, $capture_code->replice_code);
            // Mail::to($mail)->send(new MailValidate($register));

            return redirect()->route('replace_pass.ecomm');
        } else {
            return redirect()->route('recover.ecomm');
        }
    }

    public function sendEmailRecover($nome, $email, $user_id, $code)
    {

        $client = new Client();

        $url = 'https://api.brevo.com/v3/smtp/email';

        $headers = [
            'Accept' => 'application/json',
            'api-key' => env('API_KEY_BREVO'),
            'Content-Type' => 'application/json',
        ];

        $data = [
            'sender' => [
                'name' => 'Pribonasorte',
                'email' => 'info@Pribonasorte.eu',
            ],
            'to' => [
                [
                    'email' => "$email",
                    'name' => "$nome",
                ],
            ],
            'subject' => 'Account Created Successfully',
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $nome
                                    <br>
                                    Thank you for registering with us.
                                    <br>
                                    <br>
                                    Enter the code below on the password recovery page to reset your access:
                                    <br>
                                    $code
                                 </p>
                              </body>
                           </html>",
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $user_id;
            $log->operation = "EMAIL REGISTRATION";
            $log->controller = "EcommRegisterController";
            $log->http_code = $th->getCode();
            $log->route = "finalize.register.order";
            $log->status = "ERROR";
            $log->save();
        }
    }

    public function AlterPassword(Request $request)
    {
        $code_check = $request->code_check;
        $register = EcommRegister::where('replice_code', $code_check)->first();

        if ($register) {

            if ($request->password === $request->check_pass) {

                $pass_encrypt = Hash::make($request->password);

                $alter = EcommRegister::find($register->id);
                $alter->update([
                    'password' => $pass_encrypt,
                    'replice_code' => null
                ]);

                return redirect()->route('page.login.ecomm');
            } else {
                session()->flash('message', 'Passwords do not match!');
            }
        } else {
            session()->flash('message', 'Code incorret!');
        }
        return back();
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
        $newChosenPickup->id_user = session('buyer')['id'];
        $newChosenPickup->number_order = $number_order;

        $newChosenPickup->save();

        return $newChosenPickup;

    }
    public function RegisteredAddressSecondary(Request $request)
    {
        $id_user = session('buyer')['id'];

        $exists = AddressSecondary::where('id_user', $id_user)->where('backoffice', 0)->first();

        if (isset($exists)) {

            $exists->update([
                'phone' => $request->phone,
                'zip' => $request->zip,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'number' => $request->number,
                'complement' => null,
                'neighborhood' => null,
                'city' => $request->city,
                'state' => $request->state ?? '',
                'country' => $request->country,
                'backoffice' => 0,
            ]);

        } else {

            $address = new AddressSecondary;
            $address->id_user = $id_user;
            $address->first_name = $request->first_name;
            $address->last_name = $request->last_name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->address = $request->address;
            $address->number = $request->number;
            $address->complement = null;
            $address->neighborhood = null;
            $address->city = $request->city;
            $address->state = $request->state ?? '';
            $address->country = $request->country;
            $address->backoffice = 0;

            $address->save();
        }


        // $register_user = EcommOrders::where('id_user', $id_user);
        // $register_user->update([
        //     'method_shipping' => 'home2',
        // ]);

        return back();
    }

    public function PaySmart(Request $request)
    {
        // dd($request);

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


        if (isset(session('buyer')['email'])) {
            $email_pay = session('buyer')['email'];
        } else {
            $email_pay = "juro@trupikstar.sk";
        }

        $dominio = $request->getHost();
        if (strtolower($dominio) == 'Pribonasorte.eu') {
            $testComgate = 'false';
        } else {
            $testComgate = 'false';
        }

        $mt_pay = 'CARD_CZ_CSOB_2';

        $url = '';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => str_replace('.', '', $newPrice),
            'curr' => 'EUR',
            'label' => "Order $n_order",
            'email' => "$email_pay",
            'refId' => $n_order,
            'method' => "$mt_pay",
            'prepareOnly' => 'true',
            'test' => "$testComgate",
            'lang' => 'en',
            'initRecurring' => "true"
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
        if (isset($responseData)) {


            $jsonResponse = [
                'code' => $responseData['code'],
                'message' => $responseData['message'],
                'transId' => $responseData['transId'],
                'redirect' => urldecode($responseData['redirect']),
            ];

            if ($request->method_shipping == 'pickup') {
                if (
                    empty($request->id_ppl) ||
                    empty($request->accessPointType) ||
                    empty($request->dhlPsId)
                ) {
                    return redirect()->back()->with('error', 'Select pickup address');
                }

                $this->registerChosenPickup($request, $n_order);
                $paisNome = ShippingPrice::where('country_code', $request->country_ppl)->first()->country;
                $country = $paisNome;
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
                    return redirect()->back()->with('error', 'Fill in all fields at new address');
                }

                $this->RegisteredAddressSecondary($request);
                $country = $request->country;
            }
            if ($request->method_shipping == 'home1' || empty($request->method_shipping)) {
                $country = EcommRegister::where('id', $request->id_user_login)->first()->country;
            }

            $data = [
                "country" => $country ?? $paisNome ?? null,
                "total_vat" => $total_vat,
                "id_invoice_trans" => $jsonResponse['transId'],
                "smartshipping" => 1,
                "method_shipping" => $request->method_shipping,
                "url" => $jsonResponse['redirect'],
                'total_shipping' => $total_shipping,
                "id_payment" => $n_order,
                "status" => 'pending',
                "total_price" => $price,
                "method" => "$mt_pay",
                'order' => $n_order,
            ];

            // $id_user = session('buyer')['id'];
            // $register_user = EcommOrders::where('id_user', $id_user);
            // $register_user->update([
            //     'method_shipping' => 1,
            // ]);
            // dd($data);
            return $this->RegisterOrder($request, $data);
        } else {
            return redirect()->back();
        }
    }

    public function sendEmailForDistributor($id, $name, $username, $email, $phone, $recommendation_user_id)
    {
        $distributor = User::where('id', $recommendation_user_id)->first();

        $client = new Client();

        $url = 'https://api.brevo.com/v3/smtp/email';

        $headers = [
            'Accept' => 'application/json',
            'api-key' => env('API_KEY_BREVO'),
            'Content-Type' => 'application/json',
        ];

        $data = [
            'sender' => [
                'name' => 'Pribonasorte',
                'email' => 'info@Pribonasorte.eu',
            ],
            'to' => [
                [
                    'email' => "$distributor->email",
                    'name' => "$distributor->name",
                ],
            ],
            'subject' => 'Account Created Successfully',
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $distributor->name
                                    <br>
                                    <br>
                                    Congratulations! You have a new customer:
                                    <br>
                                    <br>
                                    Name: $name <br>
                                    Username: $username <br>
                                    Email: $email <br>
                                    Phone: $phone <br>
                                    <br>
                                    <br>
                                    Welcome this new individual and offer them all the necessary support as they begin their journey with our products. Customers like them are golden in network marketing, and their satisfaction with our products is key to our mutual success.
                                    <br>
                                    <br>
                                    If they decide to activate SMARTSHIP, our automatic monthly orders, they'll enjoy a 10% discount on their regular orders, allowing them to regularly use our products and maintain a stock of our products
                                    <br>
                                    <br>
                                    Communication is key, and it's very important to follow us on all social media platforms. Immediately after registration, the first steps should be to follow us on our private Facebook group and Telegram, or WhatsApp:
                                    <br>
                                    <ul>
                                       <li>Private Facebook Group <a href='https://www.facebook.com/groups/467430965762866'>https://www.facebook.com/groups/467430965762866/</a> </li>
                                       <li>Telegram <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0/</a> </li>
                                       <li>WhatsApp <a href='https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j'>https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    Additionally, you can stay connected through:
                                    <br>
                                    <br>
                                     <ul>
                                       <li>Instagram <a href='https://www.instagram.com/Pribonasorte_official'>https://www.instagram.com/Pribonasorte_official/</a> </li>
                                       <li>Facebook <a href='https://www.facebook.com/profile.php?id=61557861185751'>https://www.facebook.com/profile.php?id=61557861185751/</a> </li>
                                       <li>YouTube <a href='https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ'>https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        JURAJ MOJZIS<br>
                                        CEO of Pribonasorte<br>
                                        Support- +420234688024<br>
                                        WHATSAPP number- +420 721 530 732
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $log = new CustomLog;
            $log->content = "Sended email";
            $log->user_id = $id;
            $log->operation = "SEND EMAIL DISTRIBUTORS, WHEN THEY ENROLL CUSTOMERS";
            $log->controller = "EcommRegisterController";
            $log->http_code = 200;
            $log->route = "finalize.register.order";
            $log->status = "success";
            $log->save();

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $id;
            $log->operation = "SEND EMAIL DISTRIBUTORS, WHEN THEY ENROLL CUSTOMERS";
            $log->controller = "EcommRegisterController";
            $log->http_code = $th->getCode();
            $log->route = "finalize.register.order";
            $log->status = "ERROR";
            $log->save();
        }
    }

    public function sendEmailForPayedProduct($n_order)
    {
        $orders = EcommOrders::where('number_order', $n_order)->get();
        if (count($orders) < 1) {
            return;
        }
        $payment = PaymentOrderEcomm::where('id', $orders[0]->id_payment_order)->first();
        if ($orders[0]->client_backoffice == 1) {
            $user = User::where('id', $orders[0]->id_user)->first();
        } else {
            $user = EcommRegister::where('id', $orders[0]->id_user)->first();
        }

        $total_vat = 0;
        $total_shipping = $orders[0]->total_shipping;
        $status = $payment->status;
        $total = $payment->total_price;
        $produtos = "";

        foreach ($orders as $item) {
            $total_vat += $item->total_vat;
            $prod = Product::where('id', $item->id_product)->first();

            if ($item->client_backoffice == 1) {
                $price = $prod->backoffice_price;
            } else if ($item->smartshipping == 1) {
                $price = $prod->premium_price;
            } else {
                $price = $prod->price;
            }


            $totalThisProduct = floatval($item->total_vat) + floatval($item->total);

            $produtos .= "<tr style='text-align:center;width:100%;border-top:1px solid;'>
                            <td>$prod->name</td>
                            <td>R$$price</td>
                            <td>$item->amount</td>
                            <td>R$$item->total_vat</td>
                            <td>R$$totalThisProduct</td>
                        </tr>";
        }


        $client = new Client();

        $url = 'https://api.brevo.com/v3/smtp/email';

        $headers = [
            'Accept' => 'application/json',
            'api-key' => env('API_KEY_BREVO'),
            'Content-Type' => 'application/json',
        ];



        $data = [
            'sender' => [
                'name' => 'Pribonasorte',
                'email' => 'info@Pribonasorte.eu',
            ],
            'to' => [
                [
                    'email' => "$user->email",
                    'name' => "$user->name",
                ],
            ],
            'subject' => 'Order payed',
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $user->name
                                    <br>
                                    <br>
                                    Thank you for your order! Here's a summary of your purchase:
                                    <br>
                                    <br>
                                    <table class='table' style='border: 1px solid;width:100%;'>
                                        <thead>
                                            <tr>
                                                <th scope='col'>Product</th>
                                                <th scope='col'>Price</th>
                                                <th scope='col'>Quantity</th>
                                                <th scope='col'>VAT</th>
                                                <th scope='col'>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            $produtos
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                    Total VAT: R$$total_vat <br>
                                    Total Shipping+VAT: R$$total_shipping <br>
                                    Total Order: R$$total <br> <br>
                                    Order Status: paid <br>
                                    <br>
                                    <br>
                                    We appreciate your purchase with Pribonasorte. Your selected products will be shipped to your specified location as soon as possible.
                                    <br>
                                    <br>
                                    You will soon receive an email with a tracking number, allowing you to monitor the status and location of your shipment. Additionally, you can also find the tracking number in your purchase history. Simply go to your DASHBOARD and navigate to the 'Purchase - Order History' section.
                                    <br>
                                    <br>
                                    Communication is key, and it's very important to follow us on all social media platforms. Immediately after registration, the first steps should be to follow us on our private Facebook group and Telegram, or WhatsApp:
                                    <br>
                                    <ul>
                                       <li>Private Facebook Group <a href='https://www.facebook.com/groups/467430965762866'>https://www.facebook.com/groups/467430965762866/</a> </li>
                                       <li>Telegram <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0/</a> </li>
                                       <li>WhatsApp <a href='https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j'>https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    Additionally, you can stay connected through:
                                    <br>
                                    <br>
                                     <ul>
                                       <li>Instagram <a href='https://www.instagram.com/Pribonasorte_official'>https://www.instagram.com/Pribonasorte_official/</a> </li>
                                       <li>Facebook <a href='https://www.facebook.com/profile.php?id=61557861185751'>https://www.facebook.com/profile.php?id=61557861185751/</a> </li>
                                       <li>YouTube <a href='https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ'>https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        JURAJ MOJZIS<br>
                                        CEO of Pribonasorte<br>
                                        Support- +420234688024<br>
                                        WHATSAPP number- +420 721 530 732
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $log = new CustomLog;
            $log->content = 'EMAIL PAYED PRODUCTS';
            $log->user_id = $user->id;
            $log->operation = "EMAIL PAYED PRODUCTS";
            $log->controller = "EcommRegisterController";
            $log->http_code = 200;
            $log->route = "finalize.register.order";
            $log->status = "success";
            $log->save();

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $user->id;
            $log->operation = "EMAIL PAYED PRODUCTS";
            $log->controller = "EcommRegisterController";
            $log->http_code = $th->getCode();
            $log->route = "finalize.register.order";
            $log->status = "ERROR";
            $log->save();
        }
    }

    public function migrateUser()
    {
        if (session()->has('buyer')) {
            $user = session()->get('buyer');
            try {
                if (User::where('email', $user->email)->exists()) {
                    return redirect('/logginIn');
                }

                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->login = $user->username ?? '';
                $newUser->email = $user->email ?? '';
                $newUser->cell = preg_replace('/\D/', '', $user->phone ?? '');
                $newUser->country = $user->country;
                $newUser->financial_password = $user->password;
                $newUser->password = $user->password;
                $newUser->recommendation_user_id = $user->recommendation_user_id ?? null;
                $newUser->last_name = $user->last_name;
                $newUser->address1 = $user->address;
                $newUser->special_comission = 1;
                $newUser->special_comission_active = 0;
                $newUser->city = $user->city;
                $newUser->postcode = $user->zip;
                $newUser->activated = 0;
                $newUser->state = '';
                $newUser->birthday = $user->birth;
                $newUser->number_residence = $user->number;
                $newUser->save();

                $rede_recommedation = Rede::where('user_id', $user->recommendation_user_id)->first();

                $newUser->rede()->create([
                    "upline_id" => $rede_recommedation->id,
                    "qty" => 0,
                    "ciclo" => 1,
                    "saque" => 0
                ]);

                $rede_recommedation->update([
                    "qty" => $rede_recommedation->qty + 1
                ]);

                $sair = 1;
                $count = 1;
                $primeiro_id = $newUser->id;
                $fato_gerador = $newUser->id;
                // ini_set('max_execution_time', '300');
                while ($sair == 1) {
                    $nivel_self = User::where('id', $fato_gerador)->first();
                    if ($nivel_self->recommendation_user_id == NULL)
                        break;
                    $soma_qty1 = User::where('id', $nivel_self->recommendation_user_id)->first();
                    if ($soma_qty1 != NULL && $soma_qty1->recommendation_user_id >= 0) {
                        if ($nivel_self->name != "") {
                            $check_existe = HistoricScore::where('user_id_from', $primeiro_id)->where('user_id', $soma_qty1->id)->where('description', '6')->first();
                            if ($check_existe == NULL) {
                                if ($soma_qty1->qty == NULL) {
                                    User::where('id', $nivel_self->recommendation_user_id)->update(['qty' => 1]);
                                } else {
                                    User::where('id', $nivel_self->recommendation_user_id)->increment('qty', 1);
                                }
                            }
                        }
                        $nivel1 = User::where('id', $nivel_self->recommendation_user_id)->first();
                        $nivel12 = User::where('recommendation_user_id', $nivel1->recommendation_user_id)->first();
                        $count++;
                        $fato_gerador = $nivel12->id;
                    }
                }

                $registerController = new RegisterController;
                $registerController->sendPostRegisterUser($newUser->id, 1);
                $registerController->sendEmailRegister($newUser->name, $newUser->email, $newUser->id);
                $registerController->sendEmailRegisterNewDirectDistributor($newUser->id, $newUser->name, $newUser->login, $newUser->email, $newUser->cell, $newUser->recommendation_user_id);

                return redirect('/logginIn');
            } catch (\Throwable $th) {
                // dd($th);

                session()->flash('erro', 'Email or Login already exists!');
                return redirect()->back();
            }


        } else {
            return redirect()->route('page.login.ecomm');
        }
    }
}

