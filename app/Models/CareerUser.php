<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CareerUser extends Model
{
    use HasFactory;

    protected $table = 'career_users';

    protected $fillable = [
        'user_id',
        'career_id',
    ];

    /**
     * Relacionamentos de Tabelas
     */
    #region relacionamento
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCountRede()
    {
        return DB::table('orders_package')
            ->join('users', 'orders_package.user_id', 'users.id')
            ->where("recommendation_user_id", Auth::user()->id)
            ->where("status", 1)
            ->where("payment_status", 1)
            ->count();
    }


    public function getLastOrder($price)
    {
        return OrderPackage::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->where('payment_status', 1)
            ->where('price', $price)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getStage()
    {
        $pay = $this->getLastOrder(249); // Verifica se houve um pagamento de R$249
        $directs = $this->getCountRede(); // Obtém a quantidade de diretos

        if ($pay || $directs >= 15) {
            return 4;
        } elseif ($this->getLastOrder(149) || $directs >= 6) {
            return 3;
        } elseif ($this->getLastOrder(39) || $this->getLastOrder(79) || $directs >= 3) {
            return 2;
        } elseif (OrderPackage::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->where('payment_status', 1)->get()
        ) {
            return 1;
        } else {
            return 0;
        }
    }

    public function showStage(){
        return $this->join('career','career.id','=','career_users.career_id')
        ->select('name')
        ->first();
    }
}
