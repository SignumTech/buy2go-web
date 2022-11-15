<?php

namespace App\Exports;

use App\Models\PaymentRequest;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentRequestExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PaymentRequest::all();
    }
}
