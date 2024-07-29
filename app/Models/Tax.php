<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $table = 'tax';

    protected $fillable = ['product_id', 'country', 'value'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}