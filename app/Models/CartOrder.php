<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOrder extends Model
{
    protected $fillable = ['id_user', 'id_product', 'name', 'img', 'price', 'amount', 'total'];

    public function package(){
        return $this->belongsTo(Package::class, 'id_product', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }

}
