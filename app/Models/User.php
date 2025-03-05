<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'login',
        'password',
        'recommendation_id',
        'telephone',
        'cell',
        'gender',
        'accept_terms',
        'accept_date',
        'country',
        'image_path',
        'financial_password',
        'active_network',
        'active_date',
        'rule',
        'recommendation_user_id',
        'number_residence',
        'complement',
        'area_residence',
        'ban',
        'last_name',
        'address1',
        'address2',
        'postcode',
        'state',
        'city',
        'birthday',
        'special_comission',
        'special_comission_active',
        'id_card',
        'activated',
        'country_code_cel',
        'country_code_fone',
        'qty',
        'contact_id',
        'validate_code',
        'tax_id',
        'vat_reg_no',
        'code_api'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'financial_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relacionamento tabelas
     */
    #region relacionamento


    public function recommendation()
    {
        return $this->hasMany(User::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orderPackage()
    {
        return $this->hasMany(OrderPackage::class);
    }

    public function withdraw()
    {
        return $this->hasMany(WithdrawRequest::class);
    }

    public function rede()
    {
        return $this->hasMany(Rede::class);
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function banco()
    {
        return $this->hasMany(Banco::class);
    }

    public function score()
    {
        return $this->hasMany(HistoricScore::class);
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    public function career_user()
    {
        return $this->hasMany(CareerUser::class);
    }

    public function lastCareerUser()
    {
        return $this->hasOne(CareerUser::class)->latestOfMany();
    }

    public function lastMonthCareerUser()
    {
        return $this->hasOne(CareerUser::class)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth()->subMonth(),
                Carbon::now()->startOfMonth()
            ])
            ->latest('id')
            ->with('career');
    }

    public function config_bonus()
    {
        return $this->hasMany(ConfigBonus::class);
    }
    #endregion

    public function getTotalAttribute()
    {
        $orderItems = $this->orderPackage()->where("status", 1)->where("payment_status", 1)->get();

        $total = 0;
        foreach ($orderItems as $item) {
            $total += ($item->price * $item->amount);
        }
        return $total;
    }

    public function getTotalBanco()
    {
        $bancoItems = $this->banco()->get();

        $total = 0;
        $saque = 0;
        $saldo = 0;

        foreach ($bancoItems as $item) {

            if ($item->status == 2) {
                $saque += $item->price;
            }

            if ($item->status == 1) {
                $saldo += $item->price;
            }
        }

        $calcule = $saldo - $saque;

        if ($calcule > 0) {
            $total = $saldo - $saque;
        } else {
            $total = 0;
        }

        return $total;
    }

    public function getTotalBancoComissao()
    {
        $bancoItems = $this->banco()->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getTotalBancoCurrent()
    {
        $bancoItems = $this->banco()->where('description', 3)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }

        $bancoItems = $this->banco()->where('description', 99)->where('description', 98)->get();

        $saque = 0;
        foreach ($bancoItems as $item) {
            $saque += $item->price;
        }
        $total = $total + $saque;
        return $total;
    }

    public function getTotalBancoIndicacao()
    {
        $bancoItems = $this->banco()->where('description', 1)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getTotalBancoIndicacaoDirectIndirect()
    {
        $bancoItems1 = $this->banco()->where('description', 1)->get();

        $total = 0;
        foreach ($bancoItems1 as $item) {
            $total += $item->price;
        }

        $bancoItems2 = $this->banco()->where('description', 2)->get();

        foreach ($bancoItems2 as $item) {
            $total += $item->price;
        }

        return $total;
    }



    public function getTotalBancoDirect()
    {
        $bancoItems = $this->banco()->where('description', 7)->where('description', 8)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getTotalBancoILevel()
    {
        $bancoItems = $this->banco()->where('description', 2)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getTotalBancoPool()
    {
        $bancoItems = $this->banco()->where('description', 5)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getTotalBancoDaily()
    {
        $bancoItems = $this->banco()->where('description', 3)->get();

        $total = 0;
        foreach ($bancoItems as $item) {
            $total += $item->price;
        }
        return $total;
    }

    public function getDirects($id)
    {
        $direct = User::where('recommendation_user_id', $id)->get()->count();
        return $direct;
    }

    public function getDirectsWithOrders($id)
    {
        $direct = DB::table('orders_package')
            ->join('users', 'orders_package.user_id', 'users.id')
            ->where("recommendation_user_id", $id)
            ->where("status", 1)
            ->where("payment_status", 1)
            ->count(DB::raw('DISTINCT user_id'));

        return $direct;
    }

    public function getDirectsActiveted($id)
    {
        $direct = User::where('recommendation_user_id', $id)->get()->count();
        return $direct;
    }

    public function getCards($id)
    {
        $cards = DB::table('orders_package')
            ->join('users', 'orders_package.user_id', 'users.id')
            ->where('user_id', $id)
            ->where("status", 1)
            ->where("payment_status", 1)
            ->count(DB::raw('user_id'));

        return $cards;
    }

    public function getTeam($id)
    {
        $team = HistoricScore::where('user_id', $id)->distinct()->get(['user_id_from'])->count();
        return $team;
    }

    public function getVolume($id)
    {
        $volume = HistoricScore::where('user_id', $id)->where('description', '!=', "9")->selectRaw('sum(score) as total')
            ->first();

        if (empty($volume->total)) {
            $volume->total = 0;
        }

        return $volume->total;
    }

    public function getPoll($id)
    {
        $poll = HistoricScore::where('user_id', $id)->where('description', "9")->selectRaw('sum(score) as total')
            ->first();

        if (empty($poll->total)) {
            $poll->total = 0;
        }

        return $poll->total;
    }

    public function getRede($id)
    {
        $rede = HistoricScore::where('user_id', $id)->where('score', "0")->where('description', "Contador")->selectRaw('count(*) as total')
            ->first();

        if (empty($rede->total)) {
            $rede->total = 0;
        }

        return $rede->total;
    }

    public function getAdessao($id)
    {
        $count = DB::table('orders_package')
            ->join('packages', 'orders_package.package_id', '=', 'packages.id')
            ->where('user_id', $id)
            ->where('payment_status', 1)
            ->where('type', 'activator')
            ->where('status', 1)
            ->count();

        return $count;
    }

    public function getPackages($id)
    {
        $count = DB::table('orders_package')
            ->join('packages', 'orders_package.package_id', '=', 'packages.id')
            ->where('user_id', $id)
            ->where('payment_status', 1)
            ->where('type', 'packages')
            ->where('status', 1)
            ->count();

        return $count;
    }

    public function isAllowed()
    {
        $is_allowed = DB::table('system_conf')->first();

        if ($is_allowed != null && $is_allowed->is_allowed_btn_box) {
            return true;
        } else {
            return false;
        }

        return false;
    }

    public function isActive()
    {
        $is_activated = User::where('id', $this->id)->where('activated', 1)->first();

        if ($is_activated != null && $is_activated->activated) {
            return true;
        } else {
            return false;
        }

        return false;
    }

    public function payFirstOrder()
    {
        $pay = OrderPackage::where('user_id', $this->id)->where('status', 1)->where('payment_status', 1)->first();

        if ($pay != null) {
            return true;
        } else {
            return false;
        }

        return false;
    }

    public function getTypeActivated($id)
    {

        $pay = OrderPackage::where('user_id', $id)->where('status', 1)->where('payment_status', 1)->first();

        $getadessao = $this->getAdessao($id);

        $getpackages = $this->getPackages($id);


        //   if ($getpackages > 0) {
        $tag = "AllCards";
        //   }

        return $tag;
    }

    public function getRedeAdessao($id)
    {
        $count = DB::table('orders_package')
            ->join('users', 'orders_package.user_id', 'users.id')
            ->join('packages', 'orders_package.package_id', '=', 'packages.id')
            ->where("recommendation_user_id", $id)
            ->where('type', 'activator')
            ->where("status", 1)
            ->where("payment_status", 1)
            ->count(DB::raw('DISTINCT user_id'));
        return $count;
    }

    public function directs() {
        $directs = User::where('recommendation_user_id', $this->id)->get();
        return $directs;
    }

    public function indirects() {
        $directs = $this->directs();
        $indirects = [];
        foreach($directs as $direct) {
           $indirects = array_merge($indirects, $this->getIndirects($direct, 'direct'));
        }
        return $indirects;
    }

    public function getIndirects($user, $origin) {
        $directs = User::where('recommendation_user_id', $user->id)->get();
        $indirects = $origin == 'indirect' ? [$user] : [];
        foreach($directs as $direct) {
            $indirects = array_merge($indirects, $this->getIndirects($direct, 'indirect'));
        }
        return $indirects;
    }

    public function getRedePackages($id)
    {
        $count =
            DB::table('orders_package')
            ->join('users', 'orders_package.user_id', 'users.id')
            ->join('packages', 'orders_package.package_id', '=', 'packages.id')
            ->where("recommendation_user_id", $id)
            ->where('type', 'packages')
            ->where("status", 1)
            ->where("payment_status", 1)
            ->count(DB::raw('DISTINCT user_id'));
        return $count;
    }
}
