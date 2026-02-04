<?php

namespace App\Models;

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
}
