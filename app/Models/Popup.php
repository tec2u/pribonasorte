<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $table = 'popup';

    use HasFactory;
    
    protected $fillable = [
        'title', 'path'
    ];
}
