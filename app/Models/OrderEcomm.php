<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEcomm extends Model
{
    protected $fillable = ['ip_order', 'id_product', 'price', 'amount', 'total'];
}
