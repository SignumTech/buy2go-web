<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class driverSalesExport implements FromCollection,ShouldAutoSize,WithStyles
{
    public $sales;
    public $start_date;
    public $end_date;
    public $sort_by;

    public function __construct($sales, $start_date, $end_date, $sort_by){
        $this->sales = $sales;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->sort_by = $sort_by;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->start_date == null && $this->end_date == null){
            $text = "Total";
        }
        else{
            $text = $this->start_date."-".$this->end_date;
        }
        $headers = [
            ["Customer Sales Report: ".$text." - Ranked By ".(($this->sort_by == 'total_quantity')?'Total Order':(($this->sort_by == 'total_sold')?"Total Sales":(($this->sort_by == 'visits')?"Visits Completed":(($this->sort_by == 'commission')?"Total Commission":(($this->sort_by == 'distance_covered')?"Total Distance Traveled":"Average Delivery Time")))))],
            ["First Name", "Last Name", "Total Order", "Total Sales (ETB)", "Visits Completed","Commission (ETB)", "Total Distance Traveled (KM)", "Average Delivery Time (Hrs)", "Rank"]
        ];
        return collect([$headers,$this->sales]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
