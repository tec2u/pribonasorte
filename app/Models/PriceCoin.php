<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCoin extends Model
{
    protected $table = 'price_coin';

    use HasFactory;

    protected $fillable = [
        'name',
        'one_in_usd'
    ];
}
