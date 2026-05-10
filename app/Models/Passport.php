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
        'validity_years',
        'passport_number',
        'nationality',
    ];

    protected $casts = [
        'validity_years' => 'integer',
    ];

    protected $appends = [
        'issue_date',
    ];

    public function getDateOfBirthAttribute(?string $value): ?string
    {
        return $this->formatPassportDateValue($value);
    }

    public function getExpiryDateAttribute(?string $value): ?string
    {
        return $this->formatPassportDateValue($value);
    }

    public function getIssueDateAttribute(): ?string
    {
        $expiryDate = $this->attributes['expiry_date'] ?? null;

        if (blank($expiryDate)) {
            return null;
        }

        $parsedDate = $this->parsePassportDate(trim((string) $expiryDate));

        if ($parsedDate === null) {
            return null;
        }

        return $parsedDate['date']
            ->subYears($this->validityYears())
            ->addDay()
            ->format('d/m/Y');
    }

    public function validityYears(): int
    {
        return in_array($this->validity_years, [5, 10], true)
            ? $this->validity_years
            : 5;
    }

    private function formatPassportDateValue(?string $value): ?string
    {
        if (blank($value)) {
            return $value;
        }

        $parsedDate = $this->parsePassportDate(trim($value));

        return $parsedDate === null
            ? $value
            : $parsedDate['date']->format('d/m/Y');
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
                ];
            }
        }

        return null;
    }
}
