<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectsFakturoid extends Model
{
    use HasFactory;

    protected $table = 'subjects_fakturoid';

    protected $fillable = [
        'user_id',
        'subject_id',
    ];
}
