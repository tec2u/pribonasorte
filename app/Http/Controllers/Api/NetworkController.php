<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CareerUser;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\IpAccessApi;
use App\Models\User;
use App\Traits\ApiNetwork;
use App\Traits\ApiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetworkController extends Controller
{
    use ApiUser;
    use ApiNetwork;

    public function networks(Request $request)
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
            $ipRequest->operation = "api/app/get/home";
            $ipRequest->request = json_encode($requestFormated);
            $ipRequest->save();

            $data = [];

            $data["direct_distributors"] = $this->getTotalDirectDistributor($user);
            $data["indirect_distributors"] = $this->getTotalIndirect($user);
            $data["total_distributors"] = $this->getTotalDistributor($user);
            $data["greatest_career"] = $this->getGreatestCareer($user);
            $data["performance_career"] = $this->getPerformanceCareer($user);


            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }

    public function myCareer(Request $request)
    {
        $user = $this->getUser($request);

        if ($user == false) {
            return response()->json(['error' => "Invalid token"]);
        }

        $ip = $request->ip();
        $requestFormated = $request->all();

        $ipRequest = new IpAccessApi;
        $ipRequest->ip = $ip;
        $ipRequest->operation = "api/app/get/mycareer";
        $ipRequest->request = json_encode($requestFormated);
        $ipRequest->save();

        $data = $this->career($user);

        return $data;
    }

    public function mytree(Request $request)
    {
        $user = $this->getUser($request);

        if ($user == false) {
            return response()->json(['error' => "Invalid token"]);
        }

        $ip = $request->ip();
        $requestFormated = $request->all();

        $ipRequest = new IpAccessApi;
        $ipRequest->ip = $ip;
        $ipRequest->operation = "api/app/get/mytree";
        $ipRequest->request = json_encode($requestFormated);
        $ipRequest->save();

        if (isset($request->id)) {
            $data = $this->Tree($user, $request->id);
        } else {
            $data = $this->Tree($user);
        }

        return $data;
    }
}
