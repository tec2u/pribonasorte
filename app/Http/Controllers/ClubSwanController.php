<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CustomLogTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;
use Illuminate\Support\Facades\DB;

class ClubSwanController extends Controller
{
    use CustomLogTrait;
    public function singUp(array $data)
    {
        try {
            
            
            $data['cell'] = "+".preg_replace('/[^A-Za-z0-9]/', '', str_replace(' ', '', $data['cell']));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v2/auth/signup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "email": "' . $data['email'] . '",
                    "password": "' . $data['password'] . '",
                    "brand" : "Club Swan",
                    "firstName": "' . $data['name'] . '",
                    "lastName": "' . $data['last_name'] . '",
                    "latinFirstName": "' . $data['name'] . '",
                    "latinLastName": "' . $data['last_name'] . '",
                    "dateOfBirth": "' . $data['birthday'] . '",
                    "phone": "' . $data['cell'] . '",
                    "postCode": "' . $data['postcode'] . '",
                    "countryCode": "' . $data['country'] . '",
                    "city": "' . $data['city'] . '",
                    "addressLine1": "' . $data['address1'] . '",
                    "addressLine2": "' . $data['address2'] . '",
                    "state": "' . (($data['country'] == 'US') ? $data['state'] : '') . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if (isset($response->status)) {
                if ($response->status == 'success') {
                    $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                    Alert::success('User Integrated');
                    return $response;
                } else {
                    throw new Exception(json_encode($response));
                }
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage() . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            return $e->getMessage();
        }
    }

    public function login(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/auth/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "email": "' . $data['email'] . '",
                    "password": "' . $data['password'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error("API Error: " . $error->message);
            return $e;
        }
    }

    public function subscribe(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/membership/subscribe',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "planId": "' . $data['planId'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json',
                    'contactId: ' . $data['contactId']
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function kyc(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/kyc/status',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json',
                    'contactId: ' . $data['contactId']
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    static public function kycStatic(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/kyc/status',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json',
                    'contactId: ' . $data['contactId']
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                DB::table('custom_log')->insert(
                    [
                        'content'   => $response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response),
                        'user_id'   => '0',
                        'operation' => 'KYC Status Check',
                        'controller' => 'ClubSwanController',
                        'http_code' => '200',
                        'route'     => 'N',
                        'status'    => 'success',
                    ]
                );
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            DB::table('custom_log')->insert(
                [
                    'content'   => $error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(),
                    'user_id'   => '0',
                    'operation' => 'KYC Status Check',
                    'controller' => 'ClubSwanController',
                    'http_code' => '500',
                    'route'     => 'N',
                    'status'    => 'error',
                ]
            );
            return $e;
        }
    }

    public function availableCrypto(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/crypto/available-currencies',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function depositQuote(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/crypto/deposit-quote?fiatCurrency=' . $data['fiatCurrency'] . '&fiatAmount=' . $data['fiatAmount'] . '&cryptoCurrency=' . $data['cryptoCurrency'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function depositCreate(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/crypto/deposit',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "fiatAmount": ' . $data['fiatAmount'] . ',
                    "fiatCurrency": "' . $data['fiatCurrency'] . '",
                    "cryptoCurrency": "' . $data['cryptoCurrency'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function deposit(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/crypto/deposit/' . $data['depositId'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function depositCancel(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/crypto/deposit/' . $data['depositId'] . '/cancel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function getWalletList(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v2/account/ewallet',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json',
                    'contactId: ' . $data['contactId']
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function m2m(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/transaction/m2m',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "senderCurrency": "' . $data['senderCurrency'] . '",
                    "receiverAccountNumber": ' . $data['receiverAccountNumber'] . ',
                    "amount": ' . $data['amount'] . ',
                    "reference": "' . $data['reference'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }

    public function paymentRequest(array $data)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://partner.prd.auws.cloud/v1/transaction/payment-request',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_USERAGENT => 'infinityclubcard/1.0 (https://infinityclubcardmembers.com)',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "sourceMemberEmail": "' . $data['sourceMemberEmail'] . '",
                    "beneficiaryAccountNumber": "' . $data['beneficiaryAccountNumber'] . '",
                    "amount": ' . $data['amount'] . ',
                    "currency": "' . $data['currency'] . '",
                    "reference": "' . $data['reference'] . '",
                    "paymentDate": "' . $data['paymentDate'] . '",
                    "recurringPeriod": "' . $data['recurringPeriod'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'X-AU-Key: c70c2beb-193f-414d-b962-c80d6c61fea9',
                    'X-AU-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImM3MGMyYmViLTE5M2YtNDE0ZC1iOTYyLWM4MGQ2YzYxZmVhOSJ9.eyJrZXkiOnsia2V5SWQiOiJjNzBjMmJlYi0xOTNmLTQxNGQtYjk2Mi1jODBkNmM2MWZlYTkiLCJwYXJ0bmVySWQiOiIzOThmN2FmYy01YjYwLTQ3ZjctOGRiYS1iYjljOGFkNWZkNTMiLCJjb250YWN0SWQiOiJlNDVjNTYzYS1kM2Q2LTQ1MTktYjQ5Mi0zMjExYmRhMTkxYmYifSwic2NvcGVzIjpbImFkbWluIiwidXNlciJdLCJpYXQiOjE2NzU5OTc5NzUsImV4cCI6MTcwNzUzMzk3NSwiYXVkIjoicGFydG5lcnMiLCJpc3MiOiJBVSIsImp0aSI6ImM4NzY5ZThhLThlY2ItNDNmMC1hZmY3LWI0MjVmMGJiZmVhMCJ9.ndWaH5XgEp4V4cgZwMiQ_cOWRrn_q1427kHjNQPCCFw',
                    'Content-Type: application/json'
                ),
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status == 'success') {
                $this->createLog($response->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . json_encode($response), 200, 'success', auth()->user()->id);
                Alert::success($response->message);
                return $response;
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            $this->errorCatch($error->message . ' - PayLoad: ' . json_encode($data) . ' - Response: ' . $e->getMessage(), auth()->user()->id);
            Alert::error($error->message);
            return $e;
        }
    }
}
