<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'banco';

    protected $fillable = [
        'user_id',
        'order_id',
        'description',
        'price',
        'status',
        'level_from',
        'created_at',
        'updated_at',
    ];

    /*
    Relacionamento de Tabelas
    */

    #region
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function config_bonus()
    {
        return $this->belongsTo(ConfigBonus::class, 'description');
    }

    public function getUserOrder($id)
    {
        $id_user = EcommOrders::find($id)->first()->id_user;
        $user = User::find($id_user);

        if ($user)
            return $user->name;

        return "";

    }

    public function getUserOrderLogin($id)
    {
        $order = EcommOrders::where('number_order', $id)->first();
        if (isset($order)) {
            # code...
            if ($order->client_backoffice == 1) {
                $user = User::where('id', $order->id_user)->first();
                $userfrom = $user->login;
            } else {
                $user = EcommRegister::where('id', $order->id_user)->first();
                $userfrom = $user->name;
            }
            return $userfrom;
        }

        return '';

    }

    public function getUserOrderPackage($id)
    {
        // $package = OrderPackage::find($id);
        // return $package->package->name;

        return "Product Purchase";
    }

    public function ecommOrder()
    {
        return $this->belongsTo(EcommOrders::class, 'order_id', 'number_order');
    }

    public function getUserOrderPackageValue($id)
    {
        $order = EcommOrders::where('number_order', $id)->first();

        if (isset($order)) {
            $id_payment_order = $order->id_payment_order;

            $price = PaymentOrderEcomm::where('id', $id_payment_order)->first()->total_price;
        } else {
            $price = null;
        }

        return $price;
    }

    #endregion
}
