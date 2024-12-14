<?php

namespace App\Http\Controllers;

use App\Models\BestDatePaymentSmartshipping;
use App\Models\ChosenPickup;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\EditSmarthipping;
use App\Models\PaymentLog;
use App\Models\PaymentOrderEcomm;
use App\Models\PriceCoin;
use App\Models\Product;
use App\Models\SendedAlertSmart;
use App\Models\SmartshippingPaymentsRecurring;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SmartshippingPaymentsRecurringController extends Controller
{
    public function updateCoin()
    {
        try {
            $response = Http::get('https://economia.awesomeapi.com.br/last/EUR-USD');
            if ($response->successful()) {
                $data = $response->json();

                $savedMxn = PriceCoin::where('name', 'eur')->first();
                $savedMxn->one_in_usd = $data['EURUSD']['ask'];
                $savedMxn->save();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function index()
    {
        $this->updateCoin();

        try {
            //code...
            $log = new CustomLog;
            $log->content = "Executou cron smartshpping";
            $log->user_id = 1;
            $log->operation = "Executou cron smartshpping";
            $log->controller = "app/controller/SmartshippingPaymentsRecurringController";
            $log->http_code = 200;
            $log->route = "Executou cron smartshpping";
            $log->status = "SUCCESS";
            $log->save();
        } catch (\Throwable $th) {
        }

        $pedidosSmart = \DB::table('ecomm_orders')
            ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
            ->where('ecomm_orders.smartshipping', 1)
            ->where('payments_order_ecomms.status', 'paid')
            // ->where('payments_order_ecomms.number_order', '91001442')
            ->select('payments_order_ecomms.*')
            ->distinct()
            ->get();


        foreach ($pedidosSmart as $pedido) {
            $diaPreferido = BestDatePaymentSmartshipping::where('number_order', $pedido->number_order)->first();
            if (isset($diaPreferido)) {
                $diaEscolhido = $diaPreferido->day;
                $quandoEscolheu = $diaPreferido->updated_at;
                $currentMonth = date('m');
                $currentDate = now();
                $mesesDiferenca = $currentDate->diffInMonths($quandoEscolheu);


                if ($mesesDiferenca >= 1) { // no mes seguinte após ter feito a escolha
                    if (date('j') == $diaEscolhido) {
                        $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);
                        if ($segundoPagamento != false) { // se existir no minimo uma segunda cobrança
                            if ($segundoPagamento->updated_at->format('m') < $currentMonth) { // se a cobrança for do mes passado
                                if ($this->temEstoque($pedido->number_order)) {
                                    $this->cobrar($pedido->id);
                                }
                            }
                        } else {
                            if ($pedido->updated_at->format('m') < $currentMonth) {
                                if ($this->temEstoque($pedido->number_order)) {
                                    $this->cobrar($pedido->id);
                                }
                            }
                        }
                    } else {
                        $dataCobranca = Carbon::create(null, null, $diaEscolhido);
                        if (Carbon::now()->diffInDays($dataCobranca, false) === 7 || Carbon::now()->diffInDays($dataCobranca, false) === 1) {
                            $this->enviaAvisoSmart($pedido->id, $dataCobranca, Carbon::now()->diffInDays($dataCobranca, false));
                        }
                    }
                } else {
                    $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);

                    if ($segundoPagamento != false) {
                        $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();

                        if (Carbon::now()->gte($umMesDepois)) {
                            if ($this->temEstoque($pedido->number_order)) {
                                $this->cobrar($segundoPagamento->id_payment_first);
                            }
                        } else {
                            $dataCobranca = Carbon::parse($umMesDepois);
                            if (Carbon::now()->diffInDays($dataCobranca, false) === 7 || Carbon::now()->diffInDays($dataCobranca, false) === 1) {
                                $this->enviaAvisoSmart($pedido->id, $dataCobranca, Carbon::now()->diffInDays($dataCobranca, false));
                            }
                        }
                    } else {
                        $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();

                        if (Carbon::now()->gte($umMesDepois)) {
                            if ($this->temEstoque($pedido->number_order)) {
                                $this->cobrar($pedido->id);
                            }
                        } else {
                            $dataCobranca = Carbon::parse($umMesDepois);
                            if (Carbon::now()->diffInDays($dataCobranca, false) === 7 || Carbon::now()->diffInDays($dataCobranca, false) === 1) {
                                $this->enviaAvisoSmart($pedido->id, $dataCobranca, Carbon::now()->diffInDays($dataCobranca, false));
                            }
                        }
                    }
                }

            } else {
                $segundoPagamento = $this->existeSegundoPagamento($pedido->number_order);

                if ($segundoPagamento != false) {
                    $umMesDepois = Carbon::parse($segundoPagamento->created_at)->addMonth();

                    if (Carbon::now()->gte($umMesDepois)) {
                        if ($this->temEstoque($pedido->number_order)) {
                            $this->cobrar($segundoPagamento->id_payment_first);
                        }
                    } else {
                        $dataCobranca = Carbon::parse($umMesDepois);
                        if (Carbon::now()->diffInDays($dataCobranca, false) === 7 || Carbon::now()->diffInDays($dataCobranca, false) === 1) {
                            $this->enviaAvisoSmart($pedido->id, $dataCobranca, Carbon::now()->diffInDays($dataCobranca, false));
                        }

                    }
                } else {
                    $umMesDepois = Carbon::parse($pedido->updated_at)->addMonth();

                    if (Carbon::now()->gte($umMesDepois)) {
                        if ($this->temEstoque($pedido->number_order)) {
                            $this->cobrar($pedido->id);
                        }
                    } else {
                        $dataCobranca = Carbon::parse($umMesDepois);
                        if (Carbon::now()->diffInDays($dataCobranca, false) === 7 || Carbon::now()->diffInDays($dataCobranca, false) === 1) {
                            $this->enviaAvisoSmart($pedido->id, $dataCobranca, Carbon::now()->diffInDays($dataCobranca, false));
                        }
                    }
                }
            }

        }
    }

    public function enviaAvisoSmart($id, $date, $diff)
    {
        $payment = PaymentOrderEcomm::where('id', $id)->first();
        $order = EcommOrders::where('number_order', $payment->number_order)->first();

        $today = Carbon::today();

        $sended = SendedAlertSmart::where('number_order', $payment->number_order)
            ->whereDate('created_at', $today) // Filtra pela data de hoje
            ->first();

        if (isset($sended)) {
            return;
        } else {
            $user = null;
            if (isset($order)) {
                if ($order->client_backoffice == 1) {
                    $user = User::where('id', $order->id_user)->first();
                } else {
                    $user = EcommRegister::where('id', $order->id_user)->first();
                }
            }

            $dataFormatada = $date->format('d/m/Y');

            $this->sendEmailSmartshipping($user->id, $dataFormatada, $order->client_backoffice, $payment->number_order, $diff);
        }
    }

    public function existeSegundoPagamento($order)
    {
        $exists = SmartshippingPaymentsRecurring::where('number_order', $order)->latest('created_at')
            ->first();
        if (isset($exists)) {
            return $exists;
        } else {
            return false;
        }
    }

    public function gerarPedido($number_order, $id, $new_order, $transId)
    {
        $editSmart = EditSmarthipping::where('number_order', $number_order)->get();
        $orderMtShip = EcommOrders::where('number_order', $number_order)->first();

        if (count($editSmart) > 0) {
            $editTotal = 0;

            foreach ($editSmart as $edit) {
                $editTotal += $edit->total + $edit->total_vat;
            }

            $editTotal += $editSmart[0]->total_shipping;

            $pay = PaymentOrderEcomm::where('number_order', $number_order)->first();
            $new_pay = $pay->replicate();
            $new_pay->id_payment_gateway = $new_order;
            $new_pay->total_price = $editTotal;
            $new_pay->number_order = $new_order;
            $new_pay->id_invoice_trans = $transId;
            $new_pay->save();



            $orders = EcommOrders::where('number_order', $number_order)->get();

            $idsExiste = [];
            $idsEdit = [];

            foreach ($orders as $order) {
                array_push($idsExiste, $order->id_product);
                foreach ($editSmart as $edit) {
                    array_push($idsEdit, $edit->id_product);
                    if ($order->id_product == $edit->id_product) {

                        $novoPedido = $order->replicate();
                        $novoPedido->amount = $edit->amount;
                        $novoPedido->total = $edit->total;
                        $novoPedido->total_vat = $edit->total_vat;
                        $novoPedido->total_shipping = $edit->total_shipping;
                        $novoPedido->qv = $edit->qv;
                        $novoPedido->cv = $edit->cv;
                        $novoPedido->vat_product_percentage = $edit->vat_product_percentage;
                        $novoPedido->vat_shipping_percentage = $edit->vat_shipping_percentage;

                        $novoPedido->number_order = $new_order;
                        $novoPedido->status_order = 'order placed';
                        $novoPedido->shipmentNumber = null;
                        $novoPedido->smartshipping = 0;
                        $novoPedido->log_ppl = null;
                        $novoPedido->id_payment_order = $new_pay->id;
                        $novoPedido->id_payment_recurring = $id;
                        $novoPedido->save();
                    }
                }
            }

            $idNotExists = array_diff($idsEdit, $idsExiste);
            $idNotExists = array_unique($idNotExists);


            foreach ($idNotExists as $idNotCreated) {
                foreach ($editSmart as $edit) {
                    if ($idNotCreated == $edit->id_product) {

                        $orders = new EcommOrders;

                        $orders->number_order = $new_order;
                        $orders->id_user = $edit->id_user;
                        $orders->id_product = $edit->id_product;
                        $orders->amount = $edit->amount;
                        $orders->total = $edit->total;
                        $orders->total_vat = $edit->total_vat;
                        $orders->status_order = "order placed";
                        $orders->total_shipping = $edit->total_shipping;
                        $orders->client_backoffice = $edit->client_backoffice;
                        $orders->method_shipping = $orderMtShip->method_shipping;
                        $orders->id_payment_order = $new_pay->id;
                        $orders->smartshipping = 0;
                        $orders->id_payment_recurring = $id;
                        $orders->vat_product_percentage = $edit->vat_product_percentage;
                        $orders->vat_shipping_percentage = $edit->vat_shipping_percentage;
                        $orders->qv = $edit->qv;
                        $orders->cv = $edit->cv;

                        $orders->save();

                    }
                }
            }
        } else {

            $pay = PaymentOrderEcomm::where('number_order', $number_order)->first();
            $new_pay = $pay->replicate();
            $new_pay->id_payment_gateway = $new_order;
            $new_pay->number_order = $new_order;
            $new_pay->id_invoice_trans = $transId;
            $new_pay->save();


            $orders = EcommOrders::where('number_order', $number_order)->get();
            foreach ($orders as $order) {
                $novoPedido = $order->replicate();
                $novoPedido->number_order = $new_order;
                $novoPedido->status_order = 'order placed';
                $novoPedido->shipmentNumber = null;
                $novoPedido->smartshipping = 0;
                $novoPedido->log_ppl = null;
                $novoPedido->id_payment_order = $new_pay->id;
                $novoPedido->id_payment_recurring = $id;
                $novoPedido->save();
            }
        }



        if ($orderMtShip->method_shipping == 'pickup') {
            $chosenPickup = ChosenPickup::where('number_order', $number_order)->first();
            $novoPickup = $chosenPickup->replicate();
            $novoPickup->number_order = $new_order;
            $novoPickup->save();
        }

        $this->retiraEstoque($number_order, $new_order);

        $this->sendPostBonificacao($new_order, 1);
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

    public function cobrar($id)
    {

        $payment = PaymentOrderEcomm::where('id', $id)->first();
        $order = EcommOrders::where('number_order', $payment->number_order)->first();
        $user = null;
        if (isset($order)) {
            if ($order->client_backoffice == 1) {
                $user = User::where('id', $order->id_user)->first();
            } else {
                $user = EcommRegister::where('id', $order->id_user)->first();
            }
        }

        $email = $user->email;

        $editSmart = EditSmarthipping::where('number_order', $payment->number_order)->get();
        if (count($editSmart) > 0) {
            $editTotal = 0;

            foreach ($editSmart as $edit) {
                $editTotal += $edit->total + $edit->total_vat;
            }

            $editTotal += $editSmart[0]->total_shipping;
            $newPrice = str_replace(['.', ','], '', $editTotal);

        } else {
            $newPrice = str_replace(['.', ','], '', $payment->total_price);
        }



        $url = '';
        $data = [
            'merchant' => '475067',
            'secret' => '4PREBqiKpnBSmQf3VH6RRJ9ZB8pi7YnF',
            'price' => $newPrice,
            'curr' => 'EUR',
            'label' => "Order " . $payment->number_order,
            'email' => $email,
            'refId' => $payment->number_order,
            'prepareOnly' => 'true',
            "initRecurringId" => $payment->id_invoice_trans,
            // "initRecurringId" => "DXHD-Y6NW-SR5E",
            'test' => 'false',
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



        if (isset($responseData["code"])) {

            $newPayment = new SmartshippingPaymentsRecurring;
            $newPayment->id_user = $user->id;
            $newPayment->id_payment_first = $payment->id;

            if (isset($responseData["code"]) && $responseData["code"] == "0") {
                $status = 'paid';
                if (isset($responseData["transId"])) {
                    $trans = $responseData["transId"];
                }
            } else {
                $status = 'cancelled';
                $trans = 'ERROR';
            }

            $newPayment->status = $status;
            $newPayment->total_price = $payment->total_price;
            $newPayment->transId = $trans;
            $newPayment->payment_method = 'CARD_CZ_CSOB_2';
            $newPayment->number_order = $payment->number_order;
            $newPayment->code = $responseData["code"];

            $new_order = $this->genNumberOrder();
            $newPayment->new_order = $new_order;
            $newPayment->save();

            if (isset($responseData["code"]) && $responseData["code"] == "0") {
                $this->gerarPedido($payment->number_order, $newPayment->id, $new_order, $trans);
                $user->smartshipping = 1;
                $user->save();
            } else {
                $user->smartshipping = 0;
                $user->save();
            }


            try {
                $log = new PaymentLog;
                $log->content = $status;
                $log->order_package_id = $payment->id;
                $log->operation = "Smartshipping payment id = " . $newPayment->id . "";
                $log->controller = "SmartshippingPaymentsRecurringController";
                $log->http_code = "200";
                $log->route = "cron-smartshpping";
                $log->status = "success";
                $log->json = json_encode($responseData);
                $log->save();
            } catch (\Throwable $th) {
            }

        }
    }

    public function temEstoque($order)
    {

        $pedidos = EcommOrders::where('number_order', $order)->get();

        foreach ($pedidos as $item) {
            $stock = 1;

            $product = Product::where('id', $item->id_product)->first();

            if ($product->kit != 0) {

                $parts = explode('|', $product->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"

                    $temStock = DB::table('stock')
                        ->where('product_id', $id_produto)
                        ->sum('amount');

                    if ($temStock < ($quantidade * $item->amount)) {
                        $stock = 0;
                        return false;
                    }
                }

            } else {

                $stock = DB::table('stock')
                    ->where('product_id', $product->id)
                    ->sum('amount');

                if ($stock < $item->amount) {
                    return false;
                }
            }

            if ($stock > 0) {
                return true;
            } else {
                return false;
            }
        }

    }
    public function retiraEstoque($order, $new_order)
    {
        $pedidos = EcommOrders::where('number_order', $order)->get();

        foreach ($pedidos as $item) {
            $price_product = Product::where('id', $item->id_product)->first();

            if ($price_product->kit == 2) {
                $parts = explode('|', $price_product->kit_produtos); // Divide a string pelo caractere "|"

                foreach ($parts as $part) {
                    list($id_produto, $quantidade) = explode('-', $part); // Divide cada parte pelo caractere "-"
                    $separado[$id_produto] = $quantidade;

                    $stock = new Stock;
                    $stock->user_id = $item->id_user;
                    $stock->product_id = $id_produto;
                    $stock->amount = -$item->amount * $quantidade;
                    $stock->number_order = $new_order;
                    if ($item->client_backoffice == 0) {
                        $stock->ecommerce_externo = 1;
                    } else {
                        $stock->ecommerce_externo = 0;
                    }
                    $stock->save();
                }

            } else if ($price_product->kit == 0 || $price_product->kit == 1) {
                $stock = new Stock;
                $stock->user_id = $item->id_user;
                $stock->product_id = $item->id_product;
                $stock->amount = -$item->amount;
                $stock->number_order = $new_order;
                if ($item->client_backoffice == 0) {
                    $stock->ecommerce_externo = 1;
                } else {
                    $stock->ecommerce_externo = 0;
                }
                $stock->save();
            }
        }
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
            $log->controller = "app/controller/SmartshippingPaymentsRecurringController";
            $log->http_code = 200;
            $log->route = "Executou cron smartshpping";
            $log->status = "SUCCESS";
            $log->save();
        } catch (\Throwable $th) {
            return;
        }

        return $responseData;
    }

    public function sendEmailSmartshipping($id, $date, $isBackoffice, $order, $diff)
    {
        if ($isBackoffice == 1) {
            $distributor = User::where('id', $id)->first();
        } else {
            $distributor = EcommRegister::where('id', $id)->first();
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
                    'email' => "$distributor->email",
                    // 'email' => "gabriel.almeiidda@gmail.com",
                    'name' => "$distributor->name",
                ],
            ],
            'subject' => "Subject- Your Pribonasorte Smartship will process in $diff days ($date)",
            'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Dear, $distributor->name
                                    <br>
                                    <br>
                                    Your Pribonasorte SmartShip order will be processed in 5 days or on $date.
                                    <br>
                                    <br>
                                    If you wish to make changes to your SmartShip order, please log in to your Pribonasorte back office, click on 'My SmartShip,' and follow the provided prompts.
                                    <br>
                                    <br>
                                    Thank you!
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

            if ($order) {
                $novoRegistro = new SendedAlertSmart;
                $novoRegistro->number_order = $order;
                $novoRegistro->save();
            }

            return $response;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = $id;
            $log->operation = "SEND EMAIL ALERT SMARTSHIPPING";
            $log->controller = "SmartShippingPaymentRecurringController";
            $log->http_code = $th->getCode();
            $log->route = "smartshipping";
            $log->status = "ERROR";
            $log->save();
        }
    }

}
