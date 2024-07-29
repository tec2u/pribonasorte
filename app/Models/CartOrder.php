<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOrder extends Model
{
    protected $fillable = ['id_user', 'id_product', 'name', 'img', 'price', 'amount', 'total'];
}
