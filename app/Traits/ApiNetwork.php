<?php

namespace App\Traits;

use App\Http\Controllers\NetworkController;
use App\Models\Career;
use App\Models\CareerUser;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\OrderPackage;
use App\Models\Rede;
use App\Models\User;
use Illuminate\Support\Facades\DB;


trait ApiNetwork
{
    private function career(User $user)
    {
        $careers = Career::all();

        $latestCareerIds = CareerUser::selectRaw('MAX(id) as max_id')
            ->where('user_id', $user->id)
            ->groupBy('career_id')
            ->pluck('max_id');

        $careerAchieved = CareerUser::whereIn('id', $latestCareerIds)
            ->get();



        foreach ($careers as $carreira) {
            $carreira->achieved = false;
            foreach ($careerAchieved as $conquistada) {
                $carreira->img = asset("/images/Badges/" . $carreira->id . ".png");

                if ($carreira->id == $conquistada->career_id) {
                    $carreira->achieved = true;
                    $carreira->dt_achieved = date('d/m/Y', strtotime($conquistada->created_at));
                }
            }
        }

        $data_atual = date('Y-m');

        $logado = $user->id;
        $id_logado = $user->id;
        $directs = 0;
        $sum = 0;
        $conta = 0;
        $users_lista = [];

        $minha_pontuacao = HistoricScore::where('user_id', $id_logado)
            ->where('level_from', 0)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$data_atual])
            ->sum('score');

        $PersonalVolumeExternal = 0;
        $directCustomers = EcommRegister::where('recommendation_user_id', $id_logado)
            ->get('id');

        if (count($directCustomers) > 0) {
            foreach ($directCustomers as $value) {

                $idEcomm = $value->id;
                $paidOrders = DB::table('payments_order_ecomms')
                    ->where('status', 'paid')
                    ->pluck('number_order');

                $qvEcomm = EcommOrders::where('id_user', $idEcomm)
                    ->where('client_backoffice', 0)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')")
                    ->whereIn('number_order', $paidOrders)
                    ->sum('qv');

                $PersonalVolumeExternal += $qvEcomm ?: 0;
            }
        }

        $meus_pontos = $minha_pontuacao + $PersonalVolumeExternal;

        $carreira_atual = CareerUser::where('user_id', $id_logado)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$data_atual])
            ->orderByDesc('career_id')
            ->first();


        if (!isset($carreira_atual)) {
            $ordem = 1;
        } else {
            $ordem = $carreira_atual->career_id + 1;
        }

        $carreiras = Career::where('id', $ordem)->first();

        if (isset($carreiras)) {
            $verifica_diretos = User::where('recommendation_user_id', $id_logado)->get();

            foreach ($verifica_diretos as $array_verifica_diretos) {
                $id_indi_diretos = $array_verifica_diretos->id;


                $verifica_pontuacao_rede = DB::table('historic_score')
                    ->where('user_id', $id_indi_diretos)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$data_atual])
                    ->sum('score');

                if ($verifica_pontuacao_rede >= $carreiras->MaximumVolumeD) {
                    $valor_aproveitado = $carreiras->MaximumVolumeD;
                } else {
                    $valor_aproveitado = $verifica_pontuacao_rede;
                }

                if ($carreiras->bonus <= $verifica_pontuacao_rede) {
                    $directs++;
                }

                $sum += $valor_aproveitado;
                $conta++;

                array_push($users_lista, [
                    "name" => $array_verifica_diretos->name . " " . $array_verifica_diretos->last_name,
                    "total" => $verifica_pontuacao_rede ?? 0,
                    "after_mvl" => $valor_aproveitado,

                ]);

                // $users_lista[$id_indi_diretos] = [
                //     "name" => $array_verifica_diretos->name . " " . $array_verifica_diretos->last_name,
                //     "total" => $verifica_pontuacao_rede ?? 0,
                //     "after_mvl" => $valor_aproveitado,
                // ];
            }

            $quantDiretos = $conta;
            $soma = $sum + $meus_pontos;
        }

        if ($ordem == 1) {
            $proximaCarreira = Career::where('id', $ordem + 1)->first();
        } else {
            $proximaCarreira = Career::where('id', $ordem)->first();
            if (!isset($proximaCarreira)) {
                $proximaCarreira = $carreira_atual;
            }
        }

        $percent = 0;

        if ($soma < 1) {
            $soma = 1;
        }

        if ($proximaCarreira->volumeRequired > 0) {
            $obj = $proximaCarreira->volumeRequired;
        } else {
            $obj = 1;
        }

        $percent = ($soma / $obj) * 100;

        $data = [];
        $data["greatest_career"] = $this->getGreatestCareer($user);
        $data["performance_career"] = $this->getPerformanceCareer($user);
        $data["careers"] = $careers;
        $data["my_team"] = $users_lista;
        $data["my_career"] = [
            "my_career" => $this->getPerformanceCareer($user)["name"] ?? '',
            "percent_progress" => $percent,
            "all_points" => $soma,
            "my_directs" => $quantDiretos,
            "personal_volume" => $meus_pontos,
        ];
        $data["next_career"] = [
            "next_career" => $proximaCarreira->name,
            "required_volume" => $proximaCarreira->personalVolume - $meus_pontos > 0 ? number_format($proximaCarreira->personalVolume - $meus_pontos, 2, ',', '.') : 0,
            "points_left" => $proximaCarreira->volumeRequired - $soma > 0 ? number_format($proximaCarreira->volumeRequired - $soma, 2, ',', '.') : 0,
            "directs_left" => $proximaCarreira->directs - $quantDiretos > 0 ? $proximaCarreira->directs - $quantDiretos : 0,
            "personal_volume_left" => $proximaCarreira->personalVolume - $meus_pontos,
        ];

        return $data;
    }

    public function Tree(User $user, $parameter = null)
    {
        try {

            if (!isset($parameter)) {
                $parameter = $user->id;
            }


            $networkController = new NetworkController;

            $rede = Rede::where('user_id', $parameter)->first();

            if ($rede) {

                $name = empty($rede->upline_id) ? "" : Rede::find($rede->upline_id)->user->login;
                $login = $rede->user->login;
                $redename = $rede->user->name . ' ' . $rede->user->last_name ?? '';
                $id = $rede->id;
                $qty = $rede->qty;
                $email = $rede->user->email;
                $volume = $rede->user->getVolume($rede->user->id);
                $tag = '';
                $pay = OrderPackage::where('user_id', $rede->user->id)->where('status', 1)->where('payment_status', 1)->first();
                $getadessao = $rede->user->getAdessao($rede->user->id);
                $getpackages = $rede->user->getPackages($rede->user->id);
                if (!$pay) {
                    $tag = ["Inactive"];
                }
                if ($getadessao > 0) {
                    $tag = ["PreRegistration"];
                }
                if ($getpackages > 0) {
                    $tag = ["AllCards"];
                }
                $rede_users = Rede::where('upline_id', $id)->get()->count();

                if ($rede_users > 0) {
                    $network = $networkController->getNetwork($rede->id);
                    $networks[] = array(
                        "id" => "$id",
                        "login" => "$login",
                        "name" => "$redename",
                        "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                        "size" => ".$qty",
                        "referred" => $name,
                        "email" => $email,
                        "volume" => "Volume: $volume",
                        "tags" => $tag
                    );
                    $networks = array_merge($network, $networks);
                } else {
                    $network = $networkController->getNetwork($rede->id);
                    $networks = array(
                        array(
                            "id" => "$id",
                            "login" => "$login",
                            "name" => "$redename",
                            "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                            "size" => ".$qty",
                            "referred" => $name,
                            "volume" => "Volume: $volume",
                            "tags" => $tag
                        )
                    );
                }

                // $networks = json_encode($networks);

                return $networks;
            } else {
                $id_user = $user->id;
                $networks = 0;

                return $networks;
            }
        } catch (\Throwable $th) {
            $networks = 0;

            return $networks;
        }
    }
}
