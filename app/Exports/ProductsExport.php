<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
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
        $headers = ["Id", "Product Name", "Price", "description", "Commission Percentage", "Product Status", "SKU", "Stock", "status", "Feature status", "Discount", ''];
        return collect([$headers,$this->products]);
        //return $this->products;
    }
}
