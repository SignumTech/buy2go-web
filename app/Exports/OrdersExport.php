<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $orders;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($orders){
        $this->orders = $orders;
    }

    public function collection()
    {
        $headers = ["Id", "Order No.", "Order Amount", "Order Status", "Payment Status", "Payment Method", "Order Type", "Order Placed"];
        return collect([$headers,$this->orders]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
