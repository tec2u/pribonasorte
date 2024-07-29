<?php

namespace App\Traits;

use App\Models\Banco;
use App\Models\ConfigBonusunilevel;
use App\Models\HistoricScore;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Route;

trait OrderBonusTrait
{
    use CustomLogTrait;
    function bonusDiretoIndireto_e_Volume($user_id, $price, $order_package_id, $array_unilevel, $array_unilevel_peoples)
    {
        $sair = 1;
        $count = 1;
        $package = OrderPackage::find($order_package_id);
        $pricePackage = Package::find($package->package_id);
        if ($package->payment_method == 'CC') {
            $price = $price - ($pricePackage->price * 0.1);
        }
        while ($sair == 1) {
            $user = User::where('id', $user_id)->where('activated', 1)->orderBy('id', 'ASC')->first();
            if ($user != null && $user->recommendation_user_id >= 0 && !empty($user->recommendation_user_id) && ($count <= count($array_unilevel))) {
                $result = User::where('id', $user->recommendation_user_id)->where('activated', 1)->first();
                if ($result != null) {
                    $pay = OrderPackage::where('status', 1)->where('payment_status', 1)->where('user_id', $result->id)->count();
                    if ($pay > 0) {
                        $valor = 0;
                        ####SE A ARRAY COM A CHAVE SENDO O COUNT TIVER VALOR COLOCA O VALOR DIVIDIDO POR 100 PARA GERAR A PORCENTAGEM
                        if ($array_unilevel[$count] != "") {
                            if ($array_unilevel_peoples[$count] != "") {
                                if ($count <= 5) {
                                    $ativadorcard = OrderPackage::where('status', 1)->where('payment_status', 1)->where('user_id', $result->id)->where('price', 39.90)->orWhere('price', 10.00)->count();
                                    $addusers = 3;
                                } else {
                                    $ativadorcard = 0;
                                    $addusers = 0;
                                }
                                $ativadorMaster = OrderPackage::where('status', 1)->where('payment_status', 1)->where('user_id', $result->id)->where('package_id', 17)->count();
                                if ($ativadorMaster > 0) {
                                    $addusers = 10;
                                }
                                if ((($result->getDirectsActiveted($result->id)  + $addusers) >= $array_unilevel_peoples[$count]) || ($ativadorcard > 0) || ($ativadorMaster > 0)) {
                                    if ($count == 1) {
                                        if ((($result->getDirectsActiveted($result->id)  + $addusers) >= 3) && (($result->getDirectsActiveted($result->id) + $addusers) < 6)) {
                                            $array_unilevel[$count] = 20;
                                        } elseif ((($result->getDirectsActiveted($result->id)  + $addusers) >= 0) && (($result->getDirectsActiveted($result->id) + $addusers) < 3)) {
                                            $array_unilevel[$count] = 10;
                                        } elseif (($result->getDirectsActiveted($result->id)  + $addusers) >= 6) {
                                            $array_unilevel[$count] = 25;
                                        }
                                    }
                                    $valor = $array_unilevel[$count] / 100;
                                }
                            }
                        }
                        $valor = $valor * $price;
                        if ((isset($result->special_comission) && $result->special_comission_active == 1)) {
                            $valorespecial = $result->special_comission;
                        } else {
                            $valorespecial = 0;
                        }
                        ###CASO O COUNT FOR 1 ELE É 1( SIGNUP COMISSION ) CASO O COUNT FOR DIFERENTE É 2 ( LEVEL COMISSION )
                        if ($count == 1) {
                            $desc = 1;
                        } else {
                            $desc = 2;
                        }
                        if ($valor > 0) {
                            $data = [
                                "price"       => $valor,
                                "description" => "$desc",
                                "status"      => 1,
                                "user_id"     => $result->id,
                                "order_id"    => $order_package_id,
                                "level_from"  => "$count",
                            ];
                            $banco = Banco::create($data);
                        }
                        if ($valorespecial > 0) {
                            $valorespecialcommition = $valorespecial / 100 * $price;
                            if ($valorespecialcommition > 0) {
                                $data = [
                                    "price"       => $valorespecialcommition,
                                    "description" => "8",
                                    "status"      => 1,
                                    "user_id"     => $result->id,
                                    "order_id"    => $order_package_id,
                                    "level_from"  => "$count",
                                ];
                                $banco = Banco::create($data);
                            }
                        }
                        $data = [
                            "score"             => $price,
                            "status"            => 1,
                            "description"       => "6",
                            "user_id"           => $result->id,
                            "orders_package_id" => $order_package_id,
                            "user_id_from"      => $package->user->id,
                            "level_from"        => "$count",
                        ];
                        // $score = HistoricScore::create($data);
                    }
                }
                $user_id = $user->recommendation_user_id;
            } elseif ($count > count($array_unilevel)) {
                $sair = 0;
            } else {
                $nivel1 = User::where('id', $user_id)->first();
                if ($nivel1 != NULL) {
                    $user_id = $nivel1->recommendation_user_id;
                }
            }
            $count++;
        }
    }
    static function bonusDiretoIndireto_e_VolumeStatic($user_id, $price, $order_package_id, $array_unilevel, $array_unilevel_peoples)
    {
        $sair = 1;
        $count = 1;
        $package = OrderPackage::find($order_package_id);
        $pricePackage = Package::find($package->package_id);
        if ($package->payment_method == 'CC') {
            $price = $price - ($pricePackage->price * 0.1);
        }
        while ($sair == 1) {
            $user = User::where('id', $user_id)->where('activated', 1)->orderBy('id', 'ASC')->first();
            if ($user != null && $user->recommendation_user_id >= 0 && !empty($user->recommendation_user_id) && ($count <= count($array_unilevel))) {
                $result = User::where('id', $user->recommendation_user_id)->where('activated', 1)->first();
                if ($result != null) {
                    $pay = OrderPackage::where('status', 1)->where('payment_status', 1)->where('user_id', $result->id)->count();
                    if ($pay > 0) {
                        $valor = 0;
                        ####SE A ARRAY COM A CHAVE SENDO O COUNT TIVER VALOR COLOCA O VALOR DIVIDIDO POR 100 PARA GERAR A PORCENTAGEM
                        if ($array_unilevel[$count] != "") {
                            if ($array_unilevel_peoples[$count] != "") {
                                if ($count <= 5) {
                                    $ativadorcard = OrderPackage::where('status', 1)->where('payment_status', 1)->where('user_id', $result->id)->where('price', 39.90)->orWhere('price', 10.00)->count();
                                    $addusers = 3;
                                } else {
                                    $ativadorcard = 0;
                                    $addusers = 0;
                                }
                                if ((($result->getDirectsActiveted($result->id)  + $addusers) >= $array_unilevel_peoples[$count]) || ($ativadorcard > 0)) {
                                    if ($count == 1) {
                                        if ((($result->getDirectsActiveted($result->id)  + $addusers) >= 3) && (($result->getDirectsActiveted($result->id) + $addusers) < 6)) {
                                            $array_unilevel[$count] = 20;
                                        } elseif ((($result->getDirectsActiveted($result->id)  + $addusers) >= 0) && (($result->getDirectsActiveted($result->id) + $addusers) < 3)) {
                                            $array_unilevel[$count] = 10;
                                        } elseif (($result->getDirectsActiveted($result->id)  + $addusers) >= 6) {
                                            $array_unilevel[$count] = 25;
                                        }
                                    }
                                    $valor = $array_unilevel[$count] / 100;
                                }
                            }
                        }
                        $valor = $valor * $price;
                        if ((isset($result->special_comission) && $result->special_comission_active == 1)) {
                            $valorespecial = $result->special_comission;
                        } else {
                            $valorespecial = 0;
                        }
                        ###CASO O COUNT FOR 1 ELE É 1( SIGNUP COMISSION ) CASO O COUNT FOR DIFERENTE É 2 ( LEVEL COMISSION )
                        if ($count == 1) {
                            $desc = 1;
                        } else {
                            $desc = 2;
                        }
                        if ($valor > 0) {
                            $data = [
                                "price"       => $valor,
                                "description" => "$desc",
                                "status"      => 1,
                                "user_id"     => $result->id,
                                "order_id"    => $order_package_id,
                                "level_from"  => "$count",
                            ];
                            $banco = Banco::create($data);
                        }
                        if ($valorespecial > 0) {
                            $valorespecialcommition = $valorespecial / 100 * $price;
                            if ($valorespecialcommition > 0) {
                                $data = [
                                    "price"       => $valorespecialcommition,
                                    "description" => "8",
                                    "status"      => 1,
                                    "user_id"     => $result->id,
                                    "order_id"    => $order_package_id,
                                    "level_from"  => "$count",
                                ];
                                $banco = Banco::create($data);
                            }
                        }
                        $data = [
                            "score"             => $price,
                            "status"            => 1,
                            "description"       => "6",
                            "user_id"           => $result->id,
                            "orders_package_id" => $order_package_id,
                            "user_id_from"      => $package->user->id,
                            "level_from"        => "$count",
                        ];
                        // $score = HistoricScore::create($data);
                    }
                }
                $nivel1 = User::where('id', $user->recommendation_user_id)->first();
                $user_id = $nivel1->id;
            } elseif ($count > count($array_unilevel)) {
                $sair = 0;
            } else {
                $nivel1 = User::where('id', $user_id)->first();
                if ($nivel1 != NULL) {
                    $user_id = $nivel1->recommendation_user_id;
                }
            }
            $count++;
        }
    }
    protected function bonus_compra($contador, $indicador, $package_valor, $order_id, array $lista = array())
    {
        if ($contador < 15) {
            $user = User::where('id', $indicador)->first();
            $result = User::where('id', $user->recommendation_user_id)->get();
            $valorBonus = 0;
            foreach ($result as $row) {
                $var = $row->id;
                $lista[] = $var;
                $contador++;
                switch ($contador) {
                    case 1:
                        $valorBonus = $package_valor * 0.10;
                        break;
                    case 2:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 3:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 4:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 5:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 6:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 7:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 8:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 9:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 10:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 11:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 12:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 13:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 14:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                    case 15:
                        $valorBonus = $package_valor * 0.0005;
                        break;
                }
                $user = User::find($var);
                if ($contador == 1) {
                    $data = [
                        "price"       => $valorBonus,
                        "description" => "Referral Bonus",
                        "status"      => 0,
                        "order_id"    => $order_id
                    ];
                } else {
                    $data = [
                        "price"       => $valorBonus,
                        "description" => "Unilevel Bonus #$contador",
                        "status"      => 0,
                        "order_id"    => $order_id
                    ];
                }
                $banco = $user->banco()->create($data);
                $this->bonus_compra($contador, $row->id, $package_valor, $order_id, $lista);
            }
        }
    }
}
