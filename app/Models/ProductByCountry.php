<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductByCountry extends Model
{
    use HasFactory;
    protected $table = 'product_by_country';

    protected $fillable = ['id_product', 'id_country'];
}
