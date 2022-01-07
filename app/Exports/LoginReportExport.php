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


class LoginReportExport implements FromView, ShouldAutoSize, WithEvents
{ 
 	private $users;
    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {

    } 
    public function view(): View
    {
		return view('super-admin.report.login-report-excel', ['users' => $this->users]);
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
