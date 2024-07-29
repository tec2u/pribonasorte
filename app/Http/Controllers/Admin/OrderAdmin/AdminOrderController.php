<?php

namespace App\Http\Controllers\Admin\OrderAdmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.order-admin.index', compact('packages'));
    }


    public function payment(Request $request)
    {
        $user = User::orwhere("login", $request->username)
            ->orWhere('email', $request->username)
            ->orWhere('contact_id', $request->username)
            ->first();

        if (!$user) {
            flash(__('user Not Found: '))->error();
            return redirect()->back();
        }
        $id_user = $user->id;
        $package = Package::find($request->package);
        if ($request->has('price_new')) {
            if ($request->price_new >= $package->price) {
                $price = $request->price_new;
            } else {
                $price = $package->price;
            }
        } else {
            $price = $package->price;
        }

        $name = substr(str_replace(' ', '', $package->name), 0, 15);

        try {

            if ($request->payment == "BTC") {
                $paymentConfig = [
                    "api_url" => "https://coinremitter.com/api/v3/BTC/create-invoice",
                    "api_key" => '$2y$10$Yxo5QuZjcvBfgxMLTHosxugpUFX8bRHgc6HPHLDrfitEut670jbNS',
                    "password" => "TYxYo39kmL",
                    "currency" => "USD",
                    "expire_time" => "30"
                ];
            } else {
                $paymentConfig = [
                    "api_url" => "https://coinremitter.com/api/v3/USDTERC20/create-invoice",
                    "api_key" => '$2y$10$2rBBbuwX0z6zuqk26bhxS.9.j/zQ32Jp9powhH7mkuFEA9RqZ3iIK',
                    "password" => "TYxYo39kmL",
                    "currency" => "USD",
                    "expire_time" => "30"
                ];
            }


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $paymentConfig['api_url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "api_key": "' . $paymentConfig['api_key'] . '",
                    "password": "' . $paymentConfig['password'] . '",
                    "amount": "' . $price . '",
                    "name": "' . $name . '",
                    "currency": "' . $paymentConfig['currency'] . '",
                    "expire_time": "' . $paymentConfig['expire_time'] . '",
                    "notify_url" :"http://phpstack-884761-3067332.cloudwaysapps.com/notifyUrlPayment.php"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $raw = json_decode(curl_exec($curl));

            curl_close($curl);

            $codepayment = $raw->data->id;
            $invoiceid = $raw->data->invoice_id;
            $wallet_OP = $raw->data->address;

            $this->createOrder($id_user, $package, $codepayment, $invoiceid, $wallet_OP, '0', $price);

            $coin = $raw->data->coin;

            $paymentInfo = [
                "coin" => $raw->data->coin,
                "value" => strval($raw->data->total_amount->$coin),
                "USD" => strval($raw->data->total_amount->USD),
                "address" => $raw->data->address
            ];

            return view('admin.order-admin.detail-payment', compact('paymentInfo'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            // $this->errorCatch($e->getMessage(), auth()->user()->id);
            // flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->back();
        }
    }


    public function createOrder($id_user, $package, $payment, $invoiceid, $wallet, $subId, $price)
    {
        $user = User::find($id_user);

        $user->orderPackage()->create([
            "reference"             => $package->name,
            "payment_status"        => 0,
            "transaction_code"      => $payment,
            "package_id"            => $package->id,
            "price"                 => $price,
            "amount"                => 1,
            "transaction_wallet"    => $invoiceid,
            "wallet"                => $wallet,
            "subscription_id"       => $subId
        ]);
    }
}
