<?php

namespace App\Exports;

use App\Models\ReportCommission;
use Maatwebsite\Excel\Concerns\FromCollection;

class CommissionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ReportCommission::all();
    }
}
