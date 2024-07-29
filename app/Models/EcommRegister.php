<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommRegister extends Model
{
    protected $fillable = ['username', 'name', 'email', 'corporate_name', 'fantasy_name', 'id_corporate', 'identity_card', 'birth', 'sex', 'phone', 'zip', 'address', 'number', 'complement', 'neighborhood', 'city', 'state', 'country', 'password', 'replice_code', 'recommendation_user_id', 'last_name'];
}
