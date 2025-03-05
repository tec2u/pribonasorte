<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartOrder;
use App\Models\OrderPackage;
use App\Models\User;
use App\Traits\OrderBonusTrait;
use App\Traits\PaymentLogTrait;
use Exception;
use Illuminate\Http\Request;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class PaymentController extends Controller
{
    use PaymentLogTrait, OrderBonusTrait;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notity(Request $request)
    {
        $content = $request->getContent();
        $dados =  json_decode(json_encode($request->all()), false);

        if (!empty($dados)) {
            $codepayment = $dados->merchant_id;
            $status = $dados->status_code;

            $id = "";

            $order = OrderPackage::where('transaction_code', $codepayment)->first();

            try {

                if (!empty($order)) {
                    $data = [
                        "status" => $status == 1 ? 1 : 0,
                        "payment_status" => $status
                    ];
                    $id = $order->id;
                    $order->update($data);

                    if ($status == 1) {
                        $this->bonus_compra(0, $order->user_id, $order->price, $order->id);
                        $this->createPaymentLog('Payment processed successfully', 200, 'success',  $id, $content);
                    }
                }
            } catch (Exception $e) {

                $this->errorPaymentCatch($e->getMessage(), $id);
            }
        }
    }
    public function createPayment($data)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => floatval($data->total_price),
                    ],
                    'custom_id' => 'order_' . $data->id,
                ],
            ],
            'application_context' => [
                'brand_name' => 'Pribonasorte', // Nome da sua loja
                'return_url' => route('confirmPayment', ['id' => $data->id]),
                'cancel_url' => route('home'),
            ],
        ]);
        // Retorne o link para o usuário completar o pagamento
        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $response = ['payment_link' => $link['href'], 'message' => 'success'];
                return $response;
            }
        }
        $response = ['message' => 'error'];
        return $response;
    }

    public function createPaymentMP(Request $request, $user_id)
    {
        try {
            // Configura a chave de acesso do Mercado Pago
            MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

            // Criar um cliente de preferências
            $client = new PreferenceClient();

            $cartItems = CartOrder::with('product')->where('id_user', $user_id)->get();
            // return $cartItems;

            $items = [];
            foreach ($cartItems as $item) {
                $obj = [
                    "title" => $item->product->name,
                    "quantity" => $item->amount,
                    "unit_price" => floatval($item->price),
                    "currency_id" => "BRL"
                ];
                $items[] = $obj;
                unset($obj);
            }
            $data = [
                "items" => $items,
                "back_urls" => [
                    "success" => route('home.home'),
                    "failure" => route('home.home'),
                    "pending" => route('home.home')
                ],
                "auto_return" => "approved" // Retorno automático após aprovação
            ];

            // Criar a preferência de pagamento
            $preference = $client->create($data);

            // Redirecionar o cliente para o link de pagamento
            return $preference;
        } catch (MPApiException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'details' => $e->getApiResponse() // Resposta detalhada da API
            ], 500);
        }
    }
}
