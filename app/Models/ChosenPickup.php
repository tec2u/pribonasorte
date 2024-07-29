<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChosenPickup extends Model
{
    use HasFactory;
    protected $table = 'chosen_pickup'; // Nome da tabela correspondente no banco de dados

    protected $fillable = [
        'id_user',
        'accessPointType',
        'code',
        'dhlPsId',
        'depot',
        'depotName',
        'name',
        'street',
        'city',
        'zipCode',
        'country',
        'parcelshopName',
        'number_order',
    ];
}