<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Stock Model
 * 
 * Represents a stock entity in the food bank system.
 * A stock belongs to a product category and can be included in multiple food parcels.
 */
class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
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
    ];

    /**
     * Get the product category that owns the stock.
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get the food parcels that include this stock.
     */
    public function foodParcels(): HasMany
    {
        return $this->hasMany(FoodParcel::class);
    }

    /**
     * Scope a query to only include active stocks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the stock's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->productCategory->display_name ?? 'Unknown Product';
    }

    /**
     * Get the stock's product name through product category relationship.
     */
    public function getProductNameAttribute(): string
    {
        return $this->productCategory->product->name ?? 'Unknown';
    }

    /**
     * Get the stock's category name.
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->productCategory->category_name ?? 'Unknown';
    }
}
