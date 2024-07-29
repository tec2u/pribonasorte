<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\SearchRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\User;
use App\Traits\CustomLogTrait;
use Illuminate\Support\Facades\DB;


class UsernameUpToMasterController extends Controller
{

    public function index()
    {
    $user_search = User::latest()->first();
    $indicador = $user_search['id'];
    $contador = 0;
     while($indicador!=""){
 
             $user = User::where('id', $indicador)->get();
             
             foreach ($user as $row)
             { 
                
                $directs_active = OrderPackage::select(DB::raw('count(*) as amount'))
                ->whereIn('user_id', User::select('id')->where('recommendation_user_id',$row->id))
                ->where('status',1)
                ->where('payment_status',1)
                ->get();

                $preReg = OrderPackage::select('id')
                            ->where('user_id', $row->id)
                            ->where('status', 1)
                            ->where('package_id', 14)
                            ->get();
 
                if(count($preReg)>0){$preRegText='PreRegistered';}else{$preRegText='Not PreReg';}
                             
                 $data[$contador] = [ "id"       => $row->id ,
                                            "login"       => $row->login ,
                                            "name"       => $row->name ,
                                            "email"       => $row->email ,
                                            "country"       => $row->country , 
                                            "total_directs_active" => $directs_active[0]['amount'] ,
                                            "prereg" => $preRegText , 
                                            "activated" => $row->activated , 
                                            "level"       => $contador   ]; 
                 
                 $indicador = $row->recommendation_user_id;
 
                 $contador++;
                
 
             }
     }
     
     $data=array_reverse($data);
     return view('admin.reports.UsernameUpToMaster', compact('data'));
    
    }
    public function searchUserToMaster(SearchRequest $request)
    {

        

        try {
            $data1 = $request->search;
           
            $user_search = User::where('login', '=',  $data1 )->get();

           

                $indicador = $user_search[0]['id'];
                $contador = 0;
                while($indicador!=""){

                        $user = User::where('id', $indicador)->get();
                        
                        foreach ($user as $row)
                        { 
                            $directs_active = OrderPackage::select(DB::raw('count(*) as amount'))
                                        ->whereIn('user_id', User::select('id')->where('recommendation_user_id',$row->id))
                                        ->where('status',1)
                                        ->where('payment_status',1)
                                        ->get();
                   #teste
                            $preReg = OrderPackage::select('id')
                            ->where('user_id', $row->id)
                            ->where('status', 1)
                            ->where('package_id', 14)
                            ->get();
                           
                            


                            if(count($preReg)>0){$preRegText='PreRegistered';}else{$preRegText='Not PreReg';}
                            

                            $data[$contador] = [ "id"       => $row->id ,
                                                 "login"       => $row->login ,
                                                "name"       => $row->name ,
                                                "email"       => $row->email ,
                                                "country"       => $row->country , 
                                                "total_directs_active" => $directs_active[0]['amount'] ,
                                                "prereg" => $preRegText , 
                                                "activated" => $row->activated , 
                                                "level"       => $contador  ]; 
                            
                            $indicador = $row->recommendation_user_id;

                            $contador++;
                            

                        }


                        


                    }

                    $data=array_reverse($data);
                    return view('admin.reports.UsernameUpToMaster', compact('data'));


        }catch (Exception $e) {
        $this->errorCatch($e->getMessage(), auth()->user()->id);
        flash(__('admin_alert.usernotfound'))->error();
        return redirect()->back();
    }
    
    
    
    }


}
