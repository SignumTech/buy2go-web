<?php

namespace App\Exports;

use App\Models\DriverDetail;
use Maatwebsite\Excel\Concerns\FromCollection;

class DriverExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DriverDetail::all();
    }
}
