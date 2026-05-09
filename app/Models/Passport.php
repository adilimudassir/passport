<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $fillable = [
        'lga',
        'lastname',
        'givennames',
        'gender',
        'date_of_birth',
        'expiry_date',
        'passport_number',
        'nationality',
    ];

    protected $appends = [
        'issue_date',
    ];

    public function getIssueDateAttribute(): ?string
    {
        if (blank($this->expiry_date)) {
            return null;
        }

        $expiryDate = trim((string) $this->expiry_date);
        $parsedDate = $this->parsePassportDate($expiryDate);

        if ($parsedDate === null) {
            return null;
        }

        return $parsedDate['date']
            ->subYears(5)
            ->addDay()
            ->format($parsedDate['output_format']);
    }

    private function parsePassportDate(string $value): ?array
    {
        $formats = [
            ['d-m-y', 'd-m-y'],
            ['d/m/y', 'd/m/y'],
            ['d.m.y', 'd.m.y'],
            ['dmy', 'dmy'],
            ['d-m-Y', 'd-m-Y'],
            ['d/m/Y', 'd/m/Y'],
            ['d.m.Y', 'd.m.Y'],
            ['Y-m-d', 'Y-m-d'],
            ['Y/m/d', 'Y/m/d'],
            ['Y.m.d', 'Y.m.d'],
            ['Ymd', 'Ymd'],
        ];

        foreach ($formats as [$inputFormat, $outputFormat]) {
            $parsedDate = CarbonImmutable::createFromFormat('!' . $inputFormat, $value);

            if ($parsedDate !== false && $parsedDate->format($inputFormat) === $value) {
                return [
                    'date' => $parsedDate,
                    'output_format' => $outputFormat,
                ];
            }
        }

        return null;
    }
}
