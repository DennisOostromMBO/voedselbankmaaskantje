<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * FamilyMember Model
 * 
 * Represents a family member entity in the food bank system.
 * A family member can be associated with multiple families.
 */
class FamilyMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'relationship',
        'is_active',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'age' => 'integer',
    ];

    /**
     * Get the families for the family member.
     */
    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    /**
     * Scope a query to only include active family members.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the family member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the family member's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name . ' (' . $this->relationship . ')';
    }
}
