<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesFakturoid extends Model
{
    use HasFactory;

    protected $table = 'invoices_fakturoid';

    protected $fillable = [
        'user_id',
        'number_order',
        'invoice_id',
    ];
}
