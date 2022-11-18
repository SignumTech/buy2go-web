<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class categoryExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $categories;

    public function __construct($categories){
        $this->categories = $categories;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $headers = ["Id", "Category Name", "Parent Category", "Created at", "Items"];
        return collect([$headers,$this->categories]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
