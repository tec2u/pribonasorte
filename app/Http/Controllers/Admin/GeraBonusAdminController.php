<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Career;
use App\Models\CareerUser;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\PaymentOrderEcomm;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GeraBonusAdminController extends Controller
{
    protected $data1;
    protected $data2;

    public function functionsAvailable()
    {
        return [
            "GeraPontuacaoExterna",
            "GeraCarreira",
            "GeraUnilevel",
            "GeraGeneration",
            "GeraCustomerBonus",
            "Gera1stOrder",
            "FastStartBonus"
        ];
    }

    public function __construct()
    {
        $this->data1 = Carbon::now()->subMonth()->format('Y-m');
        $this->data2 = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
    }

    public function index($action = "GeraPontuacaoExterna")
    {
        try {
            $orders = $this->GetOrders();

            switch ($action) {
                case 'GeraPontuacaoExterna':
                    foreach ($orders as $order) {
                        $this->GeraPontuacaoExterna($order->number_order);
                    }
                    break;
                case 'GeraCarreira':
                    $users = User::all();
                    foreach ($users as $user) {
                        $this->GeraCarreira($user->id);
                    }
                    break;
                case 'GeraUnilevel':
                    $this->DeleteBonusDescription(1);
                    foreach ($orders as $order) {
                        $isBackoffice = EcommOrders::where('number_order', $order->number_order)->first();
                        if ($isBackoffice->client_backoffice == 1) {
                            $this->GeraUnilevel($order->number_order);
                        }
                    }
                    break;
                case 'GeraGeneration':
                    foreach ($orders as $order) {
                        $isBackoffice = EcommOrders::where('number_order', $order->number_order)->first();
                        if ($isBackoffice->client_backoffice == 1) {
                            $this->GeraGeneration($order->number_order);
                        }
                    }
                    break;
                case 'GeraCustomerBonus':
                    foreach ($orders as $order) {
                        $isBackoffice = EcommOrders::where('number_order', $order->number_order)->first();
                        if ($isBackoffice->client_backoffice == 0) {
                            $this->GeraCustomerBonus($order->number_order);
                        }
                    }
                    break;
                case 'Gera1stOrder':
                    foreach ($orders as $order) {
                        $this->Gera1stOrder($order->number_order);
                    }
                    break;
                case 'FastStartBonus':
                    $users = User::all();
                    foreach ($users as $user) {
                        $this->FastStartBonus($user->id);
                    }
                    break;
                default:
                    # code... 
                    break;
            }

            return;
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            throw new Exception("Error");
        }
    }
    protected function GetOrders()
    {
        $orders = EcommOrders::select('number_order', DB::raw('MIN(id) as id'))
            ->where('created_at', 'like', "%$this->data1%")
            ->whereIn('number_order', function ($query) {
                $query->select('number_order')
                    ->from('payments_order_ecomms')
                    ->whereRaw('LOWER(status) = ?', ['paid']);
            })
            ->groupBy('number_order')
            ->orderBy('id', 'asc')
            ->get();

        return $orders;
    }

    protected function GeraPontuacaoExterna($idpedido)
    {
        echo " \n\n##########################LOG VOLUME UPLINES $idpedido ###############################";
        $sair = true;
        $count = 1;
        $pega_pedido = EcommOrders::select(DB::raw('SUM(qv) as total'), 'number_order as id', 'id_user', 'created_at')
            ->where('number_order', $idpedido)
            ->where('client_backoffice', 0)
            ->groupBy('number_order', 'id_user', 'created_at')
            ->first();

        if (!isset($pega_pedido)) {
            return '<br';
        }

        $pega_patrocinador_externo = EcommRegister::where('id', $pega_pedido->id_user)->first();

        $user_original = $pega_patrocinador_externo->recommendation_user_id;
        $fato_gerador = $pega_patrocinador_externo->recommendation_user_id;

        while ($sair) {
            $nivel_self = User::where('id', $fato_gerador)->orderBy('id', 'asc')->first();

            $soma_qty1 = User::where('id', $nivel_self->recommendation_user_id)->orderBy('id', 'asc')->first();

            if (!empty($soma_qty1->login)) {
                $checker2 = HistoricScore::where('user_id', $soma_qty1->id)
                    ->where('orders_package_id', $idpedido)
                    ->first();

                if (isset($checker2)) {
                    echo "\n ########################## {$soma_qty1->login}  - value {$pega_pedido->total} ###############################";
                    HistoricScore::create([
                        'user_id' => $soma_qty1->id,
                        'orders_package_id' => $pega_pedido->id,
                        'description' => 'New Order',
                        'score' => $pega_pedido->total,
                        'status' => 1,
                        'user_id_from' => $user_original,
                        'level_from' => $count,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    echo "";
                }
            }

            if (empty($nivel_self->recommendation_user_id) || $nivel_self->recommendation_user_id == 1) {
                $sair = false;
            }

            $count++;
            $fato_gerador = $soma_qty1->id;
        }
    }

    protected function GeraCarreira($user_id)
    {
        echo "\n \n \n \n\n##########################LOG CAREER $user_id ############################### \n\n";

        $data_atual = $this->data1;
        $logado = $user_id;
        $id_logado = $user_id;
        $directs = 0;
        $sum = 0;
        $conta = 0;

        $minha_pontuacao = HistoricScore::where('user_id', $id_logado)
            ->where('created_at', 'like', "%$data_atual%")
            ->where('level_from', 0)
            ->sum('score');

        $PersonalVolumeExternal = EcommOrders::whereIn('id_user', function ($query) use ($id_logado) {
            $query->select('id')
                ->from('ecomm_registers')
                ->where('recommendation_user_id', $id_logado);
        })
            ->where('created_at', 'like', "%$data_atual%")
            ->whereIn('number_order', function ($query) {
                $query->select('number_order')
                    ->from('payments_order_ecomms')
                    ->whereRaw('LOWER(status) = ?', ['paid']);
            })
            ->sum('qv');

        echo "PONTOS PESSOAIS: $minha_pontuacao \n\n \n";
        $meus_pontos = $minha_pontuacao + $PersonalVolumeExternal;

        $carreira_atual = CareerUser::where('user_id', $id_logado)
            ->where('created_at', 'like', "%$data_atual%")
            ->orderBy('career_id', 'desc')
            ->first();

        $ordem = $carreira_atual ? $carreira_atual->career_id + 1 : 1;

        $carreiras = Career::find($ordem);

        echo "PROXIMA CARREIRA: {$carreiras->name} REQUIREMENTS--> MVL: {$carreiras->volumeRequired} -- MIN_Directs: {$carreiras->directs} -- MIN__Vol_Directs: {$carreiras->bonus} -- Min_personal_vol: {$carreiras->personalVolume} --  \n \n";

        if ($carreiras) {
            $verifica_diretos = User::where('recommendation_user_id', $logado)->get();

            foreach ($verifica_diretos as $direto) {
                $id_indi_diretos = $direto->id;
                $verifica_pontuacao_rede = HistoricScore::where('user_id', $id_indi_diretos)
                    ->where('created_at', 'like', "%$data_atual%")
                    ->sum('score');

                echo "\n";

                $valor_aproveitado = min($verifica_pontuacao_rede, $carreiras->MaximumVolumeD);

                if ($carreiras->bonus <= $verifica_pontuacao_rede) {
                    $directs++;
                }

                echo "LOGIN: {$direto->login} ID: $id_indi_diretos";
                echo "\n";
                echo "TOTAL PONTOS: $verifica_pontuacao_rede";
                echo "\n";
                echo "VALUE AFTER MVL: $valor_aproveitado";
                echo "\n";
                echo "\n";

                $sum += $valor_aproveitado;
                $conta++;
            }

            if ($meus_pontos > $carreiras->MaximumVolumeD && $carreiras->id != 1 && $carreiras->id != 2) {
                $meus_pontos = $carreiras->MaximumVolumeD;
            }

            $soma = $sum + $meus_pontos;
            echo "VALOR FINAL DOS USUARIOS: $sum, MEUS PONTOS: $meus_pontos, SOMA TOTAL: $soma";
            echo "<br>\n";
            echo "TOTAL DIRETOS: $conta";
            echo "<br>\n";
            $data = Carbon::parse($this->data2 . ' 23:59:59');

            if (empty($_SESSION['bloqueia_cron'])) {
                if ($soma >= $carreiras->volumeRequired && $directs >= $carreiras->directs && $meus_pontos >= $carreiras->personalVolume) {
                    echo "Você atingiu a carreira {$carreiras->name}";
                    CareerUser::create([
                        'user_id' => $id_logado,
                        'career_id' => $ordem,
                        'created_at' => $data,
                        'updated_at' => $data
                    ]);
                    echo "Você atingiu a carreira {$carreiras->name}";
                } else {
                    echo "Opss .. não foi possível atingir esta carreira!!! {$carreiras->name}";
                }
            }
        }
    }

    private function DeleteBonusDescription($description)
    {
        $dateExcluir = Carbon::parse($this->data1);
        // dd($dateExcluir);
        Banco::where('description', $description)
            ->whereYear('created_at', $dateExcluir->year)
            ->whereMonth('created_at', $dateExcluir->month)
            // ->where('order_id', $idpedido)
            ->delete();
    }

    protected function GeraUnilevel($idpedido)
    {
        echo "\n\n##########################LOG LEVEL BONUS###############################\n\n";

        $pega_pedido = EcommOrders::select(DB::raw('SUM(cv) as total'), 'number_order as id', 'id_user')
            ->where('number_order', $idpedido)
            ->groupBy('number_order', 'id_user')
            ->first();

        $id_dados = $pega_pedido->id_user;
        $data = Carbon::parse($this->data2 . ' 23:59:59');

        $valor_do_bonus = $pega_pedido->total;
        $referente = $idpedido;

        for ($i = 1; $i <= 5; $i++) {
            $k = 0;
            $pega_indicador = User::select('recommendation_user_id')
                ->where('id', $id_dados)
                ->first();

            if (!isset($pega_indicador->recommendation_user_id)) {
                continue;
            }

            $id_indicador = User::where('id', $pega_indicador->recommendation_user_id)
                ->first();

            if (!isset($id_indicador)) {
                continue;
            }

            $id_indicacao = $id_indicador->id;

            $checa_carreira = CareerUser::where('user_id', $id_indicacao)
                ->where('created_at', 'like', "%{$this->data1}%")
                ->orderBy('career_id', 'desc')
                ->first();

            $ativo = ($checa_carreira && $checa_carreira->career_id >= 2) ? 1 : 0;

            echo "PASSOU POR {$id_indicador->name} -- ACTV:$ativo\n";

            if ($ativo == 1) {
                $checa_bonus_config = DB::table('bonus_level')->where('level', $i)
                    ->where('id_career', '<=', $checa_carreira->career_id)
                    ->orderBy('id_career', 'desc')
                    ->first();

                $percentage = ($checa_bonus_config->percentage / 100);
                $valor_bonus = $pega_pedido->total * $percentage;

                echo "TOTAL: {$pega_pedido->total} -- %: $percentage \n";

                if ($id_indicacao && $valor_bonus > 0) {
                    $duplicicade = Banco::where('user_id', $id_indicacao)
                        ->where('order_id', $idpedido)
                        ->where('description', '1')
                        ->first();

                    if (!isset($duplicicade)) {
                        echo "INSERIU BONUS PARA {$id_indicador->name}\n";
                        Banco::create([
                            'user_id' => $id_indicacao,
                            'price' => $valor_bonus,
                            'order_id' => $idpedido,
                            'description' => '1',
                            'created_at' => $data,
                            'updated_at' => $data,
                            'status' => '1',
                            'level_from' => $i
                        ]);
                    }
                }
            } else {
                $i--;
                $k++;
            }

            $id_dados = $id_indicacao;
            if ($id_dados == "" || $k >= 100) {
                $i = 6;
            }
        }
    }

    protected function GeraGeneration($idpedido)
    {
        echo "\n\n##########################LOG GENERATION###############################\n\n";

        // Obtém o pedido
        $pega_pedido = EcommOrders::select(DB::raw('SUM(cv) as total'), 'number_order as id', 'id_user')
            ->where('number_order', $idpedido)
            ->groupBy('number_order', 'id_user')
            ->first();

        $id_dados = $pega_pedido->id_user;
        $data = Carbon::parse($this->data2 . ' 23:59:59');
        $valor_do_bonus = $pega_pedido->total;
        $referente = $idpedido;

        for ($i = 1; $i <= 5; $i++) {
            $k = 0;
            $pega_indicador = User::select('recommendation_user_id')
                ->where('id', $id_dados)
                ->first();

            if (!isset($pega_indicador->recommendation_user_id)) {
                continue;
            }

            $id_indicador = User::where('id', $pega_indicador->recommendation_user_id)
                ->first();

            if (!isset($id_indicador)) {
                continue;
            }

            $id_indicacao = $id_indicador->id;

            $checa_carreira_m1 = CareerUser::where('user_id', $id_indicacao)
                ->where('career_id', '>=', 6)
                ->orderBy('id', 'desc')
                ->first();

            $ativo = $checa_carreira_m1 ? 1 : 0;

            echo "PASSOU POR $id_indicacao - CHECA CARREIRA MAIOR M1: " . $ativo . "\n";

            if ($ativo == 1) {
                $checa_carreira = CareerUser::where('user_id', $id_indicacao)
                    ->where('created_at', 'like', "%{$this->data1}%")
                    ->orderBy('career_id', 'desc')
                    ->first();

                if (!isset($checa_carreira)) {
                    continue;
                }

                $checa_bonus_config = DB::table('bonus_generation')->where('level', $i)
                    ->where('id_career', '<=', $checa_carreira->career_id)
                    ->orderBy('id_career', 'desc')
                    ->first();


                if (!isset($checa_bonus_config)) {
                    continue;
                }

                $percentage = ($checa_bonus_config->percentage / 100);
                $valor_bonus = $pega_pedido->total * $percentage;

                echo "TOTAL: {$pega_pedido->total} -- %: $percentage \n";

                if ($id_indicacao) {
                    $duplicicade = Banco::where('user_id', $id_indicacao)
                        ->where('order_id', $idpedido)
                        ->where('description', '2')
                        ->first();

                    if (!$duplicicade) {
                        echo "\n INSERIU GENERATION PARA $id_indicacao \n";
                        Banco::create([
                            'user_id' => $id_indicacao,
                            'price' => $valor_bonus,
                            'order_id' => $idpedido,
                            'description' => '2',
                            'created_at' => $data,
                            'updated_at' => $data,
                            'status' => '1',
                            'level_from' => $i
                        ]);
                    } else {
                        echo "Duplicado";
                    }
                }
            } else {
                $i--;
                $k++;
            }

            $id_dados = $id_indicacao;
            if ($id_dados == "" || $k >= 100) {
                $i = 6;
            }
        }
    }

    protected function GeraCustomerBonus($idpedido)
    {
        try {
            //code...

            echo ("\n\n##########################LOG CUSTOMER VOLUME -- $idpedido ###############################\n\n");

            $multiplierPersonal = 0;

            // Obtém o pedido
            $pega_pedido = EcommOrders::select(DB::raw('SUM(qv) as total'), 'number_order as id', 'id_user', 'smartshipping')
                ->where('number_order', $idpedido)
                // ->where('client_backoffice', 0)
                ->groupBy('number_order', 'id_user', 'smartshipping')
                ->first();

            // dd($pega_pedido);

            if (!isset($pega_pedido)) {
                echo "Pedido não encontrado.";
                return;
            }

            // Obtém o patrocinador
            $pega_patrocinador = EcommRegister::where('id', $pega_pedido->id_user)->first();

            if (!$pega_patrocinador) {
                return;
            }

            $count_rows_pontos = Banco::where('order_id', $pega_pedido->id)
                ->where('user_id', $pega_patrocinador->recommendation_user_id)
                ->whereIn('description', [3, 4, 5])
                ->count();

            $PersonalVolumeExternal = EcommOrders::select(DB::raw('SUM(qv) as total'))
                ->whereIn('id_user', function ($query) use ($pega_patrocinador) {
                    $query->select('id')
                        ->from('ecomm_registers')
                        ->where('recommendation_user_id', $pega_patrocinador->recommendation_user_id);
                })
                ->where('created_at', 'like', "%{$this->data1}%")
                ->whereIn('number_order', function ($query) {
                    $query->select('number_order')
                        ->from('payments_order_ecomms')
                        ->whereRaw('LOWER(status) = ?', ['paid']);
                })
                ->first();

            $PersonalVolumeBonus = HistoricScore::select(DB::raw('SUM(score) as total'))
                ->where('user_id', $pega_patrocinador->recommendation_user_id)
                ->where('level_from', 0)
                ->where('created_at', 'like', "%{$this->data1}%")
                ->first();
            // dd($PersonalVolumeBonus);

            echo "\n\nEXTERNO: {$PersonalVolumeExternal->total} --- PESSOAL: {$PersonalVolumeBonus->total} \n\n";

            if (($PersonalVolumeBonus->total + $PersonalVolumeExternal->total) >= 800 && ($PersonalVolumeBonus->total + $PersonalVolumeExternal->total) <= 1999.99) {
                $multiplierPersonal = 0.05;
            }
            if (($PersonalVolumeBonus->total + $PersonalVolumeExternal->total) >= 2000) {
                $multiplierPersonal = 0.1;
            }

            if ($pega_pedido->smartshipping == 0) {
                $multiplier = 0.25;
                $bonus_categoria = 3;
            } else {
                $multiplier = 0.15;
                $bonus_categoria = 4;
            }

            echo "<br><br>PERSONAL P: {$PersonalVolumeBonus->total}, PERSONAL EXT: {$PersonalVolumeExternal->total} MULTIPLIER: $multiplier, CHECA SE EXISTE: $count_rows_pontos, CHECA SE PEDIDO EXISTE: {$pega_pedido->total} <br><br>";

            if ($count_rows_pontos == 0 && $pega_pedido->total > 0) {
                echo "INSERIU CUSTOMERB {$pega_patrocinador->recommendation_user_id} \n";
                Banco::create([
                    'user_id' => $pega_patrocinador->recommendation_user_id,
                    'order_id' => $pega_pedido->id,
                    'description' => $bonus_categoria,
                    'price' => $pega_pedido->total * $multiplier,
                    'status' => '1',
                    'level_from' => 0,
                    'created_at' => $this->data2,
                    'updated_at' => $this->data2
                ]);

                if ($multiplierPersonal > 0) {
                    Banco::create([
                        'user_id' => $pega_patrocinador->recommendation_user_id,
                        'order_id' => $pega_pedido->id,
                        'description' => 5,
                        'price' => $pega_pedido->total * $multiplierPersonal,
                        'status' => '1',
                        'level_from' => 0,
                        'created_at' => $this->data2,
                        'updated_at' => $this->data2
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    protected function Gera1stOrder($idpedido)
    {
        echo "\n\n##########################LOG 1ST ORDER###############################\n\n";

        // Obtém o pedido
        $pega_pedido = EcommOrders::select(DB::raw('SUM(cv) as total'), 'client_backoffice', 'id_user', 'id')
            ->where('number_order', $idpedido)
            ->groupBy('client_backoffice', 'id_user', 'id')
            ->first();

        if (!isset($pega_pedido)) {
            echo "Pedido não encontrado.";
            return;
        }

        // Obtém o patrocinador
        if ($pega_pedido->client_backoffice == 1) {
            $pega_patrocinador = User::find($pega_pedido->id_user);
        } else {
            $pega_patrocinador = EcommRegister::find($pega_pedido->id_user);
        }

        if (!$pega_patrocinador) {
            echo "Patrocinador não encontrado.";
            return;
        }

        $check_1st_order = PaymentOrderEcomm::whereRaw('LOWER(status) = ?', ['paid'])
            ->where('id_user', $pega_pedido->id_user)
            ->orderBy('id', 'asc')
            ->first();

        if ($check_1st_order && $check_1st_order->number_order == $idpedido) {
            $patrocinador_do_pedido = User::find($pega_patrocinador->recommendation_user_id);

            if (!$patrocinador_do_pedido) {
                echo "Patrocinador do pedido não encontrado.";
                return;
            }

            $bonificacao = $pega_pedido->total * 0.1;

            $check_ja_pagou = Banco::where('description', '12')
                ->where('order_id', $idpedido)
                ->where('user_id', $patrocinador_do_pedido->id)
                ->first();

            if (!$check_ja_pagou) {
                echo "PAGOU FIRST ORDER: $bonificacao | $pega_patrocinador->recommendation_user_id \n";

                Banco::create([
                    'description' => '12',
                    'order_id' => $idpedido,
                    'price' => $bonificacao,
                    'user_id' => $patrocinador_do_pedido->id,
                    'created_at' => Carbon::parse($this->data2 . ' 00:00:00'),
                    'updated_at' => Carbon::parse($this->data2 . ' 00:00:00'),
                    'status' => '1'
                ]);
            } else {
                echo "\n Pago";
            }
        }
    }

    protected function FastStartBonus($iduser)
    {
        echo "\n\n##########################LOG FAST START###############################\n\n";

        $actualDate = Carbon::now()->subMonth();
        $actual_month = $actualDate->format('m');
        $actual_year = $actualDate->format('Y');

        $fromDate = (clone $actualDate)->subMonths(3);
        $from_month = $fromDate->format('m');
        $from_year = $fromDate->format('Y');

        $teamCheckDate = (clone $actualDate)->subMonth();
        $team_check_month = $teamCheckDate->format('m');
        $team_check_year = $teamCheckDate->format('Y');

        $data = $actualDate->endOfMonth()->format('Y-m-t 00:00:10');

        $checa_4meses_de_cadastro = User::where('created_at', '>=', "$from_year-$from_month-01 00:00:00")
            ->where('id', $iduser)
            ->first();

        if (!$checa_4meses_de_cadastro) {
            echo "Usuário não encontrado ou fora do período de 4 meses.";
            return;
        }

        $patrocinador = $checa_4meses_de_cadastro->recommendation_user_id;
        $pass = 0;
        $data1 = $this->data1;

        $checa_carreira = CareerUser::where('user_id', $iduser)
            ->where('career_id', '>=', 3)
            ->where('created_at', 'like', "%{$this->data1}%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$checa_carreira) {
            echo "Carreira não encontrada.";
            return;
        }

        $checa_bonus_total = Banco::where('user_id', $checa_carreira->user_id)
            ->whereIn('description', [10])
            ->sum('price');

        if ($checa_bonus_total < 300) {
            if ($checa_carreira->career_id >= 5) {
                $check_if_received = Banco::where('user_id', $checa_carreira->user_id)
                    ->where('description', 10)
                    ->where(function ($query) use ($data1) {
                        $query->where('price', 150)
                            ->orWhere('created_at', 'like', "%$data1%");
                    })
                    ->first();

                if (!isset($check_if_received)) {
                    echo "PAID FASTSTART PERSONAL: $iduser - {$checa_4meses_de_cadastro->login} $150\n";

                    Banco::create([
                        'user_id' => $iduser,
                        'price' => 150,
                        'order_id' => $iduser,
                        'description' => 10,
                        'created_at' => $data,
                        'updated_at' => $data,
                        'status' => '1'
                    ]);

                    $checa_2meses_de_cadastro = User::where('created_at', '>=', "$team_check_year-$team_check_month-01 00:00:00")
                        ->where('id', $iduser)
                        ->first();

                    $patro_user = User::where('id', $patrocinador)->first();

                    if ($checa_2meses_de_cadastro) {
                        echo "PAID FASTSTART TEAM: $patrocinador - {$patro_user->login} $150\n";

                        Banco::create([
                            'user_id' => $patrocinador,
                            'price' => 150,
                            'order_id' => $iduser,
                            'description' => 11,
                            'created_at' => $data,
                            'updated_at' => $data,
                            'status' => '1'
                        ]);
                    }
                    $pass = 1;
                }
            }

            if ($checa_carreira->career_id >= 4 && $pass == 0) {
                $check_if_received = Banco::where('user_id', $checa_carreira->user_id)
                    ->where('description', 10)
                    ->where(function ($query) use ($data1) {
                        $query->where('price', 100)
                            ->orWhere('created_at', 'like', "%$data1%");
                    })
                    ->first();

                if (!$check_if_received) {
                    echo "PAID FASTSTART PERSONAL: $iduser - {$checa_4meses_de_cadastro->login} $100 \n";

                    Banco::create([
                        'user_id' => $iduser,
                        'price' => 100,
                        'order_id' => $iduser,
                        'description' => 10,
                        'created_at' => $data,
                        'updated_at' => $data,
                        'status' => '1'
                    ]);
                    $pass = 1;
                }
            }

            if ($checa_carreira->career_id >= 3 && $pass == 0) {
                $check_if_received = Banco::where('user_id', $checa_carreira->user_id)
                    ->where('description', 10)
                    ->where(function ($query) use ($data1) {
                        $query->where('price', 50)
                            ->orWhere('created_at', 'like', "%$data1%");
                    })
                    ->first();

                if (!$check_if_received) {
                    echo "PAID FASTSTART PERSONAL: $iduser - {$checa_4meses_de_cadastro->login} $50\n";

                    Banco::create([
                        'user_id' => $iduser,
                        'price' => 50,
                        'order_id' => $iduser,
                        'description' => 10,
                        'created_at' => $data,
                        'updated_at' => $data,
                        'status' => '1'
                    ]);
                }
            }
        }
    }

    public function generate()
    {
        $methods = $this->functionsAvailable();
        return view('admin.bonus.generate', compact('methods'));
    }

    public function generateAjax(Request $request)
    {
        try {
            //code...
            $method = $request->method;
            $this->index($method);
            return response(true);
        } catch (\Throwable $th) {
            throw new Exception("Error");
        }

    }
}
