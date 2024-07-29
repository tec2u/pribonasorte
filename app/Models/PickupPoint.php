<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    protected $fillable = ['country', 'kg2', 'kg5', 'kg10', 'kg20', 'kg31_5'];
}
