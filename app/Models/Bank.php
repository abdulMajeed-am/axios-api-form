<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'license_expiry_date',
        'support_person',
    ];
}
