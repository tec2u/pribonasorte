<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidateVatViesapi extends Model {

    use HasFactory;

    protected $table = 'validate_vat_viesapi';

    protected $fillable = [
        'uid',
        'country_code',
        'vat_number',
        'valid',
        'trader_name',
        'trader_company_type',
        'trader_address',
        'return_id',
        'date',
        'source',
    ];
}
