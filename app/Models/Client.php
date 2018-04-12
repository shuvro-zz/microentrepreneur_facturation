<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'company_name',
        'siren',
        'address',
        'postal_code',
        'city',
        'country',
        'email',
        'phone_number',
    ];
}
