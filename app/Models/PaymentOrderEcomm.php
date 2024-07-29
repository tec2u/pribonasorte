<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrderEcomm extends Model
{
    use HasFactory;

    protected $table = 'payments_order_ecomms';

    protected $fillable = [
        'id_user',
        'id_payment_gateway',
        'id_invoice_trans',
        'status',
        'total_price',
        'total_paid',
        'payment_method',
        'number_order'
    ];
}