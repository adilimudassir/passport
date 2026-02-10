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
        return Passport::all()->map(function ($passport, $index) {
            return [
                'sn' => $index + 1,
                'givennames' => $passport->givennames,
                'lastname' => $passport->lastname,
                'gender' => $passport->gender,
                'date_of_birth' => $passport->date_of_birth,
                'lga' => $passport->lga,
                'nationality' => $passport->nationality,
                'passport_number' => $passport->passport_number,
                'document_number' => $passport->document_number ?? '',
                'document_expiry_date' => $passport->document_expiry_date ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S/N',
            'Given Names',
            'Last Name',
            'Gender',
            'Date of Birth',
            'LGA',
            'Nationality',
            'Passport No.',
            'Document Number',
            'Document Expiry Date',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
