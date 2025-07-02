<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'customer_id',
        'supplier_id',
        'street',
        'postcode',
        'house_number',
        'addition',
        'city',
        'mobile',
        'email',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the customer that owns the contact.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the family that owns the contact.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the supplier that owns the contact.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
