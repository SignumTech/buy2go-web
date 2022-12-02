<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $users;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($users){
        $this->users = $users;

    }

    public function collection()
    {
        $headers = ["Id", "Order No.", "Order Amount", "Order Status", "Payment Status", "Payment Method", "Order Type", "Order Placed"];
        return User::all();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
