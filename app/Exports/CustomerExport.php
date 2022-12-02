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
        $headers = ["Id", "First Name", "Last Name", "Country Code", "Phone Number", "Shop Status", "Registration Date", "Shop Locations"];
        return collect([$headers,$this->users]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
