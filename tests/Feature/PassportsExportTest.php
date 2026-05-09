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
        'expiry_date' => '09-02-29',
        'passport_number' => 'A10811226',
        'nationality' => 'NIGERIAN',
    ]);

    $export = new PassportsExport();
    $row = $export->collection()->first();

    expect($row['issue_date'])->toBe('10-02-24')
        ->and($export->headings())->toContain('Issue Date');
});
