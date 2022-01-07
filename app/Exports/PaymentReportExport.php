<?php

namespace App\Exports;

use App\Models\LoginReport; 
  
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet; 
use Illuminate\Contracts\View\View;

use Illuminate\Http\Request; 
use  Maatwebsite\Excel\Facades\Excel;


class PaymentReportExport implements FromView, ShouldAutoSize, WithEvents
{ 
 	private $payments;
    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {

    } 
    public function view(): View
    {
		return view('super-admin.report.payment-report-excel', ['payments' => $this->payments]);
    }
 
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(false);
            },
        ];
    }
}
