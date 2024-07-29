<?php

namespace App\Http\Controllers;

use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\CustomLogTrait;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\ClubSwanController;

class PaymentController extends Controller
{
    use CustomLogTrait;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($package, $value)
    {
        $package = Package::find($package);

        $price = $package->price;

        $name = substr(str_replace(' ', '', $package->name), 0, 15);


        //$wallet = Wallet::where('user_id',auth()->user()->id)->first();

        // if(empty($wallet)){
        //     flash("Please register your wallet to complete the order")->warning();
        //     return redirect()->route('packages.detail', ['id' => $package->id]);
        // }

        try {

            $paymentConfig = [
                "api_url" => "https://coinremitter.com/api/v3/USDTTRC20/create-invoice",
                "api_key" => '$2y$10$KW3Ztn7BkukH7aLL6BNLF.UOVGJrEtjFSl4H39uaRTNgthCl/ZgZK',
                "password" => "TYxYo39kmL",
                "currency" => "USD",
                "expire_time" => "30"
            ];

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

            $this->createOrder($package, $codepayment, $invoiceid, $wallet_OP, '0');

            $coin = $raw->data->coin;

            $paymentInfo = [
                "coin" => $raw->data->coin,
                "value" => strval($raw->data->total_amount->$coin),
                "USD" => strval($raw->data->total_amount->USD),
                "address" => $raw->data->address
            ];

            return view('payment', compact('paymentInfo'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->route('packages.detail', ['id' => $package->id]);
        }
    }


    public function indexUSDTERC($package, $value)
    {
        $package = Package::find($package);

        $price = $package->price;

        $name = substr(str_replace(' ', '', $package->name), 0, 15);


        //$wallet = Wallet::where('user_id',auth()->user()->id)->first();

        // if(empty($wallet)){
        //     flash("Please register your wallet to complete the order")->warning();
        //     return redirect()->route('packages.detail', ['id' => $package->id]);
        // }

        try {

            $paymentConfig = [
                "api_url" => "https://coinremitter.com/api/v3/USDTERC20/create-invoice",
                "api_key" => '$2y$10$2rBBbuwX0z6zuqk26bhxS.9.j/zQ32Jp9powhH7mkuFEA9RqZ3iIK',
                "password" => "TYxYo39kmL",
                "currency" => "USD",
                "expire_time" => "30"
            ];

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

            $this->createOrder($package, $codepayment, $invoiceid, $wallet_OP, '0');

            $coin = $raw->data->coin;

            $paymentInfo = [
                "coin" => $raw->data->coin,
                "value" => strval($raw->data->total_amount->$coin),
                "USD" => strval($raw->data->total_amount->USD),
                "address" => $raw->data->address
            ];

            return view('payment', compact('paymentInfo'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->route('packages.detail', ['id' => $package->id]);
        }
    }

    public function indexBTC($package, $value)
    {
        $package = Package::find($package);

        $price = $package->price;

        $name = substr(str_replace(' ', '', $package->name), 0, 15);


        //$wallet = Wallet::where('user_id',auth()->user()->id)->first();

        // if(empty($wallet)){
        //     flash("Please register your wallet to complete the order")->warning();
        //     return redirect()->route('packages.detail', ['id' => $package->id]);
        // }


        try {

            $paymentConfig = [
                "api_url" => "https://coinremitter.com/api/v3/BTC/create-invoice",
                "api_key" => '$2y$10$Yxo5QuZjcvBfgxMLTHosxugpUFX8bRHgc6HPHLDrfitEut670jbNS',
                "password" => "TYxYo39kmL",
                "currency" => "USD",
                "expire_time" => "30"
            ];

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

            $this->createOrder($package, $codepayment, $invoiceid, $wallet_OP, '0');

            $coin = $raw->data->coin;

            $paymentInfo = [
                "coin" => $raw->data->coin,
                "value" => strval($raw->data->total_amount->$coin),
                "USD" => strval($raw->data->total_amount->USD),
                "address" => $raw->data->address
            ];

            return view('payment', compact('paymentInfo'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->route('packages.detail', ['id' => $package->id]);
        }
    }





    public function indexPost(Request $request)
    {
        $paymentConfig = [
            "api_url" => "https://coinremitter.com/api/v3/BTC/create-invoice",
            "api_key" => '$2y$10$zPCs9SvbKdfOH3wZ924a2eJIveS7e3j1GIGzXU13my89YvsuNXfoa',
            "password" => "UbIyw80yH8OkmZd"
        ];

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
                "amount": "' . $request->value . '",
                "name": "' . $request->package . '",
                "currency": "' . $request->currency . '",
                "expire_time": "' . $request->expire_time . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $raw = json_decode(curl_exec($curl));

        curl_close($curl);

        $paymentInfo = [
            "USD" => strval($raw->data->total_amount->USD),
            "BTC" => strval($raw->data->total_amount->BTC),
            "EUR" => strval($raw->data->total_amount->EUR),
            "address" => $raw->data->address
        ];

        return view('payment', compact('paymentInfo'));
    }

    public function subscriptionClub($package)
    {
        try {
            $package = Package::find($package);
            $price = $package->price;
            $name = substr(str_replace(' ', '', $package->name), 0, 15);
            $club = new ClubSwanController;
            $user = User::find(auth()->user()->id);
            if ($user->contact_id != NULL) {
                $data = array('planId' => $package->plan_id, 'contactId' => $user->contact_id);
                $clubResponse = $club->subscribe($data);
                if ($clubResponse->status == 'success') {
                    $subId = $clubResponse->data->id;
                } else {
                    throw new Exception("Subscription Failed: Confirm the planId registered in the package!");
                }
            }
            $this->createOrder($package, '0', '0', '0', $subId);
            return redirect('https://infinityclubcardmembers.com');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('backoffice_alert.unable_to_process_your_order'))->error();
            return redirect()->route('packages.detail', ['id' => $package->id]);
        }
    }

    public function createOrder($package, $payment, $invoiceid, $wallet, $subId)
    {
        $user = User::find(auth()->user()->id);

        $user->orderPackage()->create([
            "reference"             => $package->name,
            "payment_status"        => 0,
            "transaction_code"      => $payment,
            "package_id"            => $package->id,
            "price"                 => $package->price,
            "amount"                => 1,
            "transaction_wallet"    => $invoiceid,
            "wallet"                => $wallet,
            "subscription_id"       => $subId
        ]);
    }
}
