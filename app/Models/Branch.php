<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $table = 'branches';
    protected $guarded = [];
    public $timestamps = true;

    protected $attributes = [
        'taluk_town' => '',
        'bank_address' => '',
        'contact_person' => '',
        'contact_number' => '',
        'license_expiry_date' => null,
        'business_amount' => 0.00,
        'maintenance_amount' => 0.00,
        'maintenance_freq' => '',
        'our_support_person' => '',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        $nullableFields = [
            'bank_address', 'contact_person', 'our_support_person', 'license_expiry_date',
        ];

        if (in_array($key, $nullableFields) && (is_null($value) || trim($value) === '')) {
            if ($key === 'our_support_person') {
                return 'Not assigned';
            }
            if ($key === 'license_expiry_date') {
                return 'Not set';
            }
            return 'Not provided';
        }

        return $value;
    }
}