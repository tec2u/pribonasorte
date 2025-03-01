<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommOrders extends Model
{
    protected $fillable = ['number_order', 'id_user', 'id_product', 'amount', 'total', 'status_order', 'id_payment_order', 'smartshipping','payment_link'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }
}
