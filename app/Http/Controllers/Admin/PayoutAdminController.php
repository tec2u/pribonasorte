<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomLog;
use App\Models\ShippingPrice;
use App\Models\User;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayoutAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // private $url = 'https://admin.i-payout.com/eWalletWS/ws_JsonAdapter.aspx';
    private $url = 'https://www.i-payout.net/eWalletWS/ws_JsonAdapter.aspx';
    private $MerchantGUID = '6fa2b84f-0c7f-4fcf-a3f6-5c54cf55c170';
    private $MerchantPassword = 'hFucByBbEy';
    public function index()
    {
        abort(404);
        // dd($this->createUser(116159));
        // $this->getBalance(116015);
        // $this->payUser(116015, 50);
    }

    public function createUser($id)
    {
        try {
            //code...

            $user = User::where('id', $id)->first();

            if (!isset($user)) {
                return false;
            }

            $country = ShippingPrice::where('country', $user->country)->first();

            if (!isset($country)) {
                return;
            }

            if (isset($user->ipayout_id)) {
                return true;
            }

            $data = [
                "fn" => "eWallet_RegisterUser",
                // "Merchant" => 'Lifeprosper',
                "MerchantGUID" => str_replace(' ', '', $this->MerchantGUID),
                "MerchantPassword" => $this->MerchantPassword,
                // "UserName" => "testando",
                "UserName" => $user->login,
                "FirstName" => $user->name,
                "Address1" => $user->address1,
                "City" => $user->city,
                "ZipCode" => $user->postcode,
                "State" => $user->state ?? '',
                "LastName" => $user->last_name ?? '',
                "Country2xFormat" => $country->country_code,
                // "EmailAddress" => "testedevstecu@gmail.com",
                "EmailAddress" => $user->email,
                "DateOfBirth" => $user->birthday,
                "DefaultCurrency" => "EUR",
            ];

            // dd($data);

            $client = new Client();

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $response = $client->post($this->url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            parse_str($body, $responseData);

            $log = new CustomLog;
            $log->content = json_encode($body);
            $log->user_id = $id;
            $log->operation = "create user ipayout";
            $log->controller = "app/controller/PayoutAdminController";
            $log->http_code = $statusCode;
            $log->route = "sendAmountUserIpayout";
            $log->status = "SUCCESS";
            $log->save();

            if (isset($responseData)) {
                $responseData = json_decode($body)->response;

                $log = new CustomLog;
                $log->content = json_encode($responseData);
                $log->user_id = $id;
                $log->operation = "create user ipayout";
                $log->controller = "app/controller/PayoutAdminController";
                $log->http_code = 200;
                $log->route = "createuseripayout";
                $log->status = "SUCCESS";
                $log->save();

                if ($responseData->m_Code == 0) {
                    $user->ipayout_id = $responseData->TransactionRefID;
                    $user->save();

                    return true;
                }
            } else {
                $log = new CustomLog;
                $log->content = json_encode($body);
                $log->user_id = $id;
                $log->operation = "create user ipayout";
                $log->controller = "app/controller/PayoutAdminController";
                $log->http_code = 200;
                $log->route = "sendAmountUserIpayout";
                $log->status = "ERROR";
                $log->save();
            }

            return false;

        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = json_encode($th->getMessage());
            $log->user_id = $id;
            $log->operation = "create user ipayout";
            $log->controller = "app/controller/PayoutAdminController";
            $log->http_code = 200;
            $log->route = "sendAmountUserIpayout";
            $log->status = "ERROR";
            $log->save();

            return false;
        }
    }

    public function getBalance($id)
    {

        $user = User::where('id', $id)->first();

        if (!isset($user)) {
            return false;
        }

        if (!isset($user->ipayout_id)) {
            return false;
        }

        $data = [
            "fn" => "eWallet_GetCurrencyBalance",
            "MerchantGUID" => str_replace(' ', '', $this->MerchantGUID),
            "MerchantPassword" => $this->MerchantPassword,
            "UserName" => $user->login,
            "CurrencyCode" => "EUR"
        ];

        $client = new Client();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $response = $client->post($this->url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        parse_str($body, $responseData);

        if (isset($responseData)) {
            $responseData = json_decode($body);
            if ($responseData->response->m_Code == 0) {
                return $responseData->Balance;
            }
        }

        return false;
    }

    public function payUser($id, WithdrawRequest $withdrawRequest)
    {
        try {
            //code...

            $this->createUser($id);

            $user = User::where('id', $id)->first();

            if (!isset($user)) {
                return null;
            }

            if (!isset($user->ipayout_id)) {
                return null;
            }

            if (isset($withdrawRequest->idpayout)) {
                return "Already exists in ipayout";
            }

            $today = Carbon::now()->toDateString();

            $message = "Withdraw $withdrawRequest->id to user $user->login - $today";

            $data = [
                "fn" => "eWallet_Load",
                "MerchantGUID" => str_replace(' ', '', $this->MerchantGUID),
                "MerchantPassword" => $this->MerchantPassword,
                "PartnerBatchID" => "$message",
                // "PoolID" => null,
                "arrAccounts" => [
                    "UserName" => $user->login,
                    "Amount" => $withdrawRequest->value,
                    "Comments" => $message,
                    "MerchantReferenceID" => $user->ipayout_id
                ],
                "AllowDuplicates" => true,
                "AutoLoad" => false,
                "CurrencyCode" => "EUR"
            ];

            // dd($data);

            $client = new Client();

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $response = $client->post($this->url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            parse_str($body, $responseData);

            if (isset($responseData)) {
                $responseData = json_decode($body);

                $log = new CustomLog;
                $log->content = json_encode($responseData->response);
                $log->user_id = $id;
                $log->operation = "send amount user ipayout";
                $log->controller = "app/controller/PayoutAdminController";
                $log->http_code = 200;
                $log->route = "sendAmountUserIpayout";
                $log->status = "SUCCESS";
                $log->save();

                if ($responseData->response->m_Code == 0) {
                    $with = WithdrawRequest::where('id', $withdrawRequest->id)->first();
                    $with->idpayout = $responseData->response->TransactionRefID;
                    $with->save();
                }

                return $responseData->response->m_Text;
            } else {
                $log = new CustomLog;
                $log->content = json_encode($body);
                $log->user_id = $id;
                $log->operation = "send amount user ipayout";
                $log->controller = "app/controller/PayoutAdminController";
                $log->http_code = 200;
                $log->route = "sendAmountUserIpayout";
                $log->status = "ERROR";
                $log->save();
            }

            return null;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = json_encode($th->getMessage());
            $log->user_id = $id;
            $log->operation = "send amount user ipayout";
            $log->controller = "app/controller/PayoutAdminController";
            $log->http_code = 200;
            $log->route = "sendAmountUserIpayout";
            $log->status = "ERROR";
            $log->save();

            return null;
        }
    }
}
