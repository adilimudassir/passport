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

        if (preg_match('/^\d{6}$/', $expiryDate)) {
            $parsedDate = CarbonImmutable::createFromFormat('ymd', $expiryDate);

            if ($parsedDate !== false) {
                return $parsedDate->subYears(5)->addDay()->format('ymd');
            }
        }

        try {
            return CarbonImmutable::parse($expiryDate)
                ->subYears(5)
                ->addDay()
                ->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
