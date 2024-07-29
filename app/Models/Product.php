<?php

namespace App\Models;

use App\Traits\HasStockCheck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use HasStockCheck;

    protected $fillable = [
        'name',
        'resume',
        'description',
        'img_1',
        'img_2',
        'img_3',
        'type',
        'unity',
        'price',
        'height',
        'width',
        'depth',
        'weight',
        'amount',
        'score',
        'activated',
        'active',
        'video',
        'sequence',
        'availability'

    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class)->onDelete('cascade');
    }

}
