<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class FoodParcel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stock_id',
        'customer_id',
        'is_active',
        'note'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the customer that owns the food parcel.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the stock associated with the food parcel.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get all food parcels with customer and stock details using Eloquent.
     *
     * @param array $filters Optional filters (customer_id, is_active, search)
     * @return Collection
     */
    public static function getAllWithDetails(array $filters = []): Collection
    {
        try {
            $query = static::with(['customer', 'stock.productCategory.product']);

            // Apply filters
            if (isset($filters['customer_id']) && $filters['customer_id']) {
                $query->where('customer_id', $filters['customer_id']);
            }

            if (isset($filters['is_active']) && $filters['is_active'] !== '') {
                $query->where('is_active', (bool)$filters['is_active']);
            }

            $results = $query->orderBy('created_at', 'desc')->get();

            // Transform to match expected structure with proper stock data
            return $results->map(function ($parcel) {
                return (object)[
                    'id' => $parcel->id,
                    'customer_id' => $parcel->customer_id,
                    'stock_id' => $parcel->stock_id,
                    'is_active' => $parcel->is_active,
                    'note' => $parcel->note,
                    'created_at' => $parcel->created_at,
                    'updated_at' => $parcel->updated_at,
                    'customer_name' => 'Klant #' . $parcel->customer_id,
                    'customer_email' => '',
                    'customer_phone' => '',
                    'product_name' => $parcel->stock?->productCategory?->product?->product_name ?? 'Onbekend Product',
                    'category_name' => $parcel->stock?->productCategory?->category_name ?? 'Onbekende Categorie',
                    'stock_name' => ($parcel->stock?->productCategory?->product?->product_name ?? 'Onbekend') . ' - ' .
                                  ($parcel->stock?->productCategory?->category_name ?? 'Onbekende Categorie'),
                    'stock_quantity' => $parcel->stock?->quantity_in_stock ?? 0,
                    'stock_unit' => $parcel->stock?->unit ?? 'stuks',
                    'stock_received_date' => $parcel->stock?->received_date,
                    'stock_delivered_date' => $parcel->stock?->delivered_date,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to get food parcels with details: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get food parcel details by ID using Eloquent.
     *
     * @param int $id
     * @return object|null
     */
    public static function getDetailsByIdSP(int $id): ?object
    {
        try {
            $parcel = static::with(['customer', 'stock.productCategory.product'])->find($id);

            if (!$parcel) {
                return null;
            }

            return (object)[
                'id' => $parcel->id,
                'customer_id' => $parcel->customer_id,
                'stock_id' => $parcel->stock_id,
                'is_active' => $parcel->is_active,
                'note' => $parcel->note,
                'created_at' => $parcel->created_at,
                'updated_at' => $parcel->updated_at,
                'customer_name' => 'Klant #' . $parcel->customer_id,
                'customer_email' => '',
                'customer_phone' => '',
                'product_name' => $parcel->stock?->productCategory?->product?->product_name ?? 'Onbekend Product',
                'category_name' => $parcel->stock?->productCategory?->category_name ?? 'Onbekende Categorie',
                'stock_name' => ($parcel->stock?->productCategory?->product?->product_name ?? 'Onbekend') . ' - ' .
                              ($parcel->stock?->productCategory?->category_name ?? 'Onbekende Categorie'),
                'stock_quantity' => $parcel->stock?->quantity_in_stock ?? 0,
                'stock_unit' => $parcel->stock?->unit ?? 'stuks',
                'stock_received_date' => $parcel->stock?->received_date,
                'stock_delivered_date' => $parcel->stock?->delivered_date,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get food parcel details: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create food parcel using Eloquent.
     *
     * @param array $data
     * @return bool
     */
    public static function createWithSP(array $data): bool
    {
        try {
            static::create([
                'stock_id' => $data['stock_id'],
                'customer_id' => $data['customer_id'],
                'is_active' => $data['is_active'] ?? true,
                'note' => $data['note'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update food parcel using Eloquent.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateWithSP(int $id, array $data): bool
    {
        try {
            $parcel = static::findOrFail($id);
            $parcel->update([
                'stock_id' => $data['stock_id'],
                'customer_id' => $data['customer_id'],
                'is_active' => $data['is_active'] ?? true,
                'note' => $data['note'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete food parcel using Eloquent.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteWithSP(int $id): bool
    {
        try {
            $parcel = static::findOrFail($id);
            $parcel->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get food parcel statistics using Eloquent.
     *
     * @return object
     */
    public static function getStatistics(): object
    {
        try {
            $total = static::count();
            $active = static::where('is_active', true)->count();
            $inactive = static::where('is_active', false)->count();
            $thisMonth = static::whereYear('created_at', now()->year)
                              ->whereMonth('created_at', now()->month)
                              ->count();

            return (object)[
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'this_month' => $thisMonth
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get food parcel statistics: ' . $e->getMessage());
            return (object)[
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'this_month' => 0
            ];
        }
    }
}
