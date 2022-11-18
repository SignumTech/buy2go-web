<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $products;
    public function __construct($products)
    {
        $this->products = $products;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $headers = ["Id", "Product Name", "Price", "description", "Commission Percentage", "SKU", "Supplier", "Feature status", "Created at", "Updated at", "stock"];
        return collect([$headers,$this->products]);
        
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
