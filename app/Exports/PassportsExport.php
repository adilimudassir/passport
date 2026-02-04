<?php

namespace App\Exports;

use App\Models\Passport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PassportsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Passport::all([
            'lga',
            'lastname',
            'givennames',
            'gender',
            'date_of_birth',
            'expiry_date',
            'passport_number',
            'nationality',
            'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'LGA',
            'Last Name',
            'Given Names',
            'Gender',
            'Date of Birth',
            'Expiry Date',
            'Passport Number',
            'Nationality',
            'Captured At',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
