<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public static function GetAllStocks()
     {
        return DB::select('CALL get_all_stocks()');
     }
     public static function createStocks()
        {
            return DB::select('CALL create_stocks()');
        }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
        'is_active',
        'note',
        'received_date',
        'delivered_date',
        'unit',
        'quantity_in_stock',
        'quantity_delivered',
        'quantity_supplied',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'received_date' => 'date',
        'delivered_date' => 'date',
        'quantity_in_stock' => 'integer',
        'quantity_delivered' => 'integer',
        'quantity_supplied' => 'integer',
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

    /**
     * Get stocks formatted for dropdowns using stored procedure data.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getForDropdown()
    {
        $stocks = self::GetAllStocks();
        
        return collect($stocks)->map(function ($stock) {
            return (object)[
                'id' => $stock->id,
                'product_name' => $stock->product_name ?? 'Onbekend Product',
                'category_name' => $stock->category_name ?? 'Onbekende Categorie',
                'quantity_in_stock' => $stock->quantity_in_stock ?? 0,
                'unit' => $stock->unit ?? 'stuks',
                'is_active' => $stock->is_active,
                'display_name' => ($stock->product_name ?? 'Onbekend Product') . ' - ' . 
                                ($stock->category_name ?? 'Onbekende Categorie') . 
                                ' (' . ($stock->quantity_in_stock ?? 0) . ' ' . ($stock->unit ?? 'stuks') . ')',
                'received_date' => $stock->received_date,
                'delivered_date' => $stock->delivered_date,
            ];
        })->where('is_active', true);
    }

    /**
     * Create a new stock using stored procedure.
     *
     * @param array $data
     * @return bool
     */
    public static function createWithSP(array $data): bool
    {
        try {
            DB::statement('CALL create_stocks(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $data['product_category_id'],
                $data['is_active'] ?? true,
                $data['note'] ?? null,
                $data['received_date'] ?? null,
                $data['delivered_date'] ?? null,
                $data['unit'] ?? null,
                $data['quantity_in_stock'] ?? 0,
                $data['quantity_delivered'] ?? 0,
                $data['quantity_supplied'] ?? 0,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create stock via stored procedure: ' . $e->getMessage());
            return false;
        }
    }

}
