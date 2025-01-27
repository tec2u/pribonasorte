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
        'availability',
        'id_additional_archive'
    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class)->onDelete('cascade');
    }

    public function videoAdditional(){
        return $this->belongsTo(Video::class, 'id_additional_archive', 'id');
    }

    public function documentAdditional(){
        return $this->belongsTo(Documents::class, 'id_additional_archive', 'id');
    }
}
