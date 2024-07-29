<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IpAccessApi;
use App\Traits\ApiUser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiUser;
    public function home(Request $request)
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

            $purchaseController = new PurchaseController;
            $products = $purchaseController->getProducts($user, ['id', 'name', 'img_1']);

            $data = [];
            $data["products"] = $products;
            $data["quant_products_cart"] = $this->getQuantCart($user);
            $data["total_comissions"] = $this->getTotalComission($user);
            $data["available_comissions"] = $this->getTotalComissionAvailable($user);
            $data["downline_volume"] = $this->getDownlineVolume($user);
            $data["personal_volume"] = $this->getPersonalVolume($user);
            $data["indirect_distributors"] = $this->getTotalIndirect($user);
            $data["total_distributors"] = $this->getTotalDistributor($user);
            $data["direct_distributors"] = $this->getTotalDirectDistributor($user);
            $data["downline_volume_last_month"] = $this->getDownlineVolumeLastMonth($user);
            $data["personal_volume_last_month"] = $this->getPersonalVolumeLastMonth($user);
            $data["smartshipping_status"] = $user->smartshipping;
            $data["status"] = $this->getStatus($user);
            $data["greatest_career"] = $this->getGreatestCareer($user);
            $data["performance_career"] = $this->getPerformanceCareer($user);
            $data["my_directs"] = $this->getMyDirectsWithQV($user);
            $data["my_orders_smartshipping"] = $this->getMyOrdersSmart($user);
            $data["new_enrollments"] = $this->getNewEnrollments($user);
            $data["new_rank_Advancement"] = $this->getNewRankAdvancement($user);
            $data["bonus_list"] = $this->bonusList($user);


            return response()->json($data);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()], 401);
            return response()->json(['error' => "Failed in get data"]);
            //throw $th;
        }
    }
}
