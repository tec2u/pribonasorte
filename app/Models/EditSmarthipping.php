<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditSmarthipping extends Model
{
    use HasFactory;

    protected $table = 'edit_smarthipping';

    protected $fillable = [
        'number_order',
        'id_user',
        'id_product',
        'amount',
        'total',
        'total_vat',
        'total_shipping',
        'qv',
        'cv',
        'client_backoffice',
        'vat_product_percentage',
        'vat_shipping_percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
