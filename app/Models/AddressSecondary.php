<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressSecondary extends Model
{
    protected $fillable = [
        'id_user',
        'phone',
        'zip',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
        'backoffice',
        'first_name',
        'last_name'
    ];
}
