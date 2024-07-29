<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IpAccessApi;
use App\Traits\ApiUser;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    use ApiUser;
    public function withdraw(Request $request)
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
            $ipRequest->operation = "api/app/get/withdraw";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["total_comissions"] = $this->getTotalComission($user);
            $data["available_comissions"] = $this->getTotalComissionAvailable($user);

            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }
}
