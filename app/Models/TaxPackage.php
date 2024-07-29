<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxPackage extends Model
{
    use HasFactory;

    protected $table = 'tax_package';

    protected $fillable = [
        'package_id',
        'country',
        'value',
    ];
}