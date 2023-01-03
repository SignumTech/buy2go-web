<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class productSalesExport implements FromCollection,ShouldAutoSize,WithStyles

{
    public $sales;
    public $start_date;
    public $end_date;

    public function __construct($sales, $start_date, $end_date){
        $this->sales = $sales;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
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
            ["Product Sales Report: ".$text],
            ["Name", "Total Order", "Total Sales", "Rank"]
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
