<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IpAccessApi;
use App\Models\Rede;
use App\Models\SystemConf;
use App\Models\User;
use App\Traits\ApiUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserController extends Controller
{

    use ApiUser;
    public function login(Request $request)
    {
        try {
            //code...

            $validatedData = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }


            $ip = $request->ip();

            $tryFailed = IpAccessApi::where('operation', 'api/app/validate/login/failed')->where('ip', $ip)->whereDate('created_at', Carbon::today())->get();

            if (count($tryFailed) > 5) {
                return response()->json(['error' => 'There were too many login attempts today'], 422);
            }

            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/validate/login";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {

                $user = Auth::user();
                $accessToken = $user->createToken('Login', ['*'], Carbon::now()->addDays(1));

                $token = $accessToken->plainTextToken;

                $parteAntesDoPipe = strstr($token, '|', true);

                if ($parteAntesDoPipe !== false) {
                    $ttk = PersonalAccessToken::where('id', $parteAntesDoPipe)->first();
                    if (!isset($ttk)) {
                        return response()->json(['error' => 'Error in generate token'], 401);
                    }

                    $userr = User::where('id', $user->id)->first();

                    $this->clearCart($userr);
                    $this->sendPostBonificacaoLogin($userr);
                    return response()->json(['token' => $ttk->token], 200);

                } else {
                    return response()->json(['error' => 'Error in generate token'], 401);
                }

            } else {
                $ip = $request->ip();
                $requestFormated = $request->all();

                $ipRequest = new IpAccessApi;
                $ipRequest->ip = $ip;
                $ipRequest->operation = "api/app/validate/login/failed";
                $ipRequest->request = json_encode($requestFormated);
                $ipRequest->save();
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => 'Error process login'], 401);
        }
    }

    public function register(Request $request)
    {
        try {

            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|string',
                'login' => 'required|string|string',
                'email' => 'required|string|email',
                'password' => 'required|string',
                'cell' => 'required',
                'country' => 'required|string',
                'city' => 'required|string',
                'last_name' => 'required|string',
                'recommendation_user_id' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }

            $ip = $request->ip();
            $requestFormated = $request->all();

            $ipRequest = new IpAccessApi;
            $ipRequest->ip = $ip;
            $ipRequest->operation = "api/app/register/user";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            // $system = SystemConf::first();
            // if (isset($system)) {
            //     if ($system->all == 0 || $system->all == 1 && $system->app == 0) {
            //         return response()->json(['error' => "System disabled"]);
            //     }
            // }

            $exists = User::where('email', $request->email)->orWhere('login', $request->login)->first();

            if (isset($exists)) {
                return response()->json(['error' => "User already exists"]);
            }

            $user_rec = User::where('id', $request->recommendation_user_id)->orWhere('login', $request->recommendation_user_id)->first();

            if (!isset($user_rec)) {
                return response()->json(['error' => "Referral invalid"]);
            }

            $recommendation = $user_rec != null ? $user_rec->id : '3';


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'login' => $request->login,
                'activated' => 0,
                'password' => Hash::make($request->password),
                'financial_password' => Hash::make($request->password),
                'recommendation_user_id' => $recommendation,
                'special_comission' => 1,
                'special_comission_active' => 0,
                'cell' => $request->cell,
                'country' => $request->country,
                'city' => $request->city,
                'last_name' => $request->last_name,

            ]);


            $rede_recommedation = Rede::where('user_id', $recommendation)->first();

            $user->rede()->create([
                "upline_id" => $rede_recommedation->id,
                "qty" => 0,
                "ciclo" => 1,
                "saque" => 0
            ]);

            $rede_recommedation->update([
                "qty" => $rede_recommedation->qty + 1
            ]);


            return $this->login($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => "Failed to create User"]);
        }

    }
    public function returnUser(Request $request)
    {
        $user = $this->getUser($request);

        $ip = $request->ip();
        $requestFormated = $request->all();

        $ipRequest = new IpAccessApi;
        $ipRequest->ip = $ip;
        $ipRequest->operation = "api/app/get/user";
        $ipRequest->request = json_encode($requestFormated);
        $ipRequest->save();

        if ($user == false) {
            return response()->json(['error' => "Invalid token"]);
        }

        return response()->json($user);

    }

    public function updateUser(Request $request)
    {

    }
}
