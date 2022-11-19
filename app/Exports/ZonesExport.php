<?php

namespace App\Exports;

use App\Models\Zone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ZonesExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $zones;
    public function __construct($zones)
    {
        $this->zones = $zones;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $headers = ['Id', 'Zone Name', 'Sub City', 'City', 'Country', 'area'];
        return collect([$headers,$this->zones]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
