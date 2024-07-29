<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBilling extends Model
{
    use HasFactory;
    protected $table = 'address_billing';

    protected $fillable = [
        'user_id',
        'country',
        'city',
        'zip',
        'state',
        'address',
        'number_residence',
        'backoffice'
    ];
}
