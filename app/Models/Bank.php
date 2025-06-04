<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $table = 'banks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'bank_id', 'enum_id', 'bank_name', 'taluk_town', 'email',
        'name_in_invoice', 'gst_no', 'invoice_to', 'bank_address',
        'contact_person', 'contact_number', 'customer_type', 'version_type',
        'license_expiry_date', 'business_amount', 'maintenance_amount',
        'maintenance_freq', 'our_support_person',
    ];

    protected $attributes = [
        'bank_name' => '',
        'taluk_town' => '',
        'email' => '',
        'name_in_invoice' => '',
        'gst_no' => '',
        'invoice_to' => '',
        'bank_address' => '',
        'contact_person' => '',
        'contact_number' => '',
        'customer_type' => '',
        'version_type' => '',
        'license_expiry_date' => null,
        'business_amount' => 0.00,
        'maintenance_amount' => 0.00,
        'maintenance_freq' => '',
        'our_support_person' => '',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'bank_id', 'bank_id');
    }

    public static function findByBankIdOrFail(string $bankId): Bank
    {
        return self::where('bank_id', $bankId)->firstOrFail();
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        $nullableFields = [
            'bank_name', 'bank_address', 'contact_person', 'our_support_person', 'license_expiry_date',
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