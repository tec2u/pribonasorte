<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartshippingPaymentsRecurring extends Model
{
    use HasFactory;

    protected $table = 'smartshipping_payments_recurring';

    protected $fillable = [
        'id_user',
        'id_payment_first',
        'status',
        'total_price',
        'transId',
        'payment_method',
        'number_order',
        'code',
    ];
}
