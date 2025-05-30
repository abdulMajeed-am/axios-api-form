<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';
    protected $primaryKey = 'bank_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'bank_id', 'enum_id', 'bank_name', 'taluk_town', 'email',
        'name_in_invoice', 'gst_no', 'invoice_to', 'bank_address',
        'contact_person', 'contact_number', 'customer_type', 'version_type',
        'license_expiry_date', 'business_amount', 'maintenance_amount',
        'maintenance_freq', 'our_support_person',
    ];
    public function branches()
    {
        return $this->hasMany(Branch::class, 'bank_id', 'bank_id');
    }
}
