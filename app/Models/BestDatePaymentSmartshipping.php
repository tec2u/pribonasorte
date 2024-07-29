<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestDatePaymentSmartshipping extends Model
{
    use HasFactory;

    protected $table = 'bestDatePaymentSmartshipping';

    protected $fillable = [
        'user_id',
        'number_order',
        'day',
    ];
}
