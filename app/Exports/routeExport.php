<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class routeExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $routes;
    public function __construct($routes)
    {
        $this->routes = $routes;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $headers = ["Id", "Route Name", "Zone", "Drivers", "Shops"];
        return collect([$headers,$this->routes]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
