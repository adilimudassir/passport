<?php

use App\Exports\PassportsExport;
use App\Models\Passport;

test('passport export includes issue date in the exported rows and headings', function () {
    Passport::create([
        'lga' => 'Sokoto North',
        'lastname' => 'AHMAD',
        'givennames' => 'MUDASSIR ADILI',
        'gender' => 'M',
        'date_of_birth' => '06-06-95',
        'expiry_date' => '09-02-34',
        'passport_number' => 'A10811226',
        'nationality' => 'NIGERIAN',
        'validity_years' => 10,
    ]);

    $export = new PassportsExport();
    $row = $export->collection()->first();

    expect($row['date_of_birth'])->toBe('06/06/1995')
        ->and($row['issue_date'])->toBe('10/02/2024')
        ->and($row['expiry_date'])->toBe('09/02/2034')
        ->and($export->headings())->toContain('Issue Date');
});
