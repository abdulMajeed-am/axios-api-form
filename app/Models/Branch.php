<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $fillable = [
        'bank_id', 'BranchId', 'taluk_town', 'bank_address', 'contact_person',
        'contact_number', 'license_expiry_date', 'business_amount',
        'maintenance_amount', 'maintenance_freq', 'our_support_person',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }
}
