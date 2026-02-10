<?php

namespace App\Http\Controllers;

use App\Exports\PassportsExport;
use App\Models\Passport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function xlsx()
    {
        $filename = 'passports_' . now()->format('Y-m-d_His') . '.csv';
        
        return Excel::download(new PassportsExport, $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function pdf()
    {
        $passports = Passport::all();
        $filename = 'passports_' . now()->format('Y-m-d_His') . '.pdf';

        $pdf = Pdf::loadView('exports.passports-pdf', compact('passports'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
