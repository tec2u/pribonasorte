<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCommission extends Model
{
    protected $table = 'report_commissions';
    protected $fillable = ['id_commission', 'user_commission', 'total_commission'];
}
