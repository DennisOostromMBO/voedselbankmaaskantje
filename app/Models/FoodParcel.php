<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

/**
 * Food Parcel Model
 *
 * Handles food parcel data and relationships with customers and stocks.
 * Includes stored procedures for complex queries with joins.
 *
 * @property int $id
 * @property int $stock_id
 * @property int $customer_id
 * @property bool $is_active
 * @property string|null $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class FoodParcel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'stock_id',
        'customer_id',
        'is_active',
        'note'
    ];

    /**
     * The attributes that should be cast.
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
     * Get all food parcels with customer and stock details using stored procedure.
     *
     * @param array $filters Optional filters (customer_id, is_active, search)
     * @return Collection
     */
    public static function getAllWithDetails(array $filters = []): Collection
    {
        try {
            $customerFilter = $filters['customer_id'] ?? null;
            $activeFilter = $filters['is_active'] ?? null;
            $searchTerm = $filters['search'] ?? null;

            $results = DB::select('CALL sp_get_food_parcels_with_details(?, ?, ?)', [
                $customerFilter,
                $activeFilter,
                $searchTerm
            ]);

            return collect($results);
        } catch (\Exception $e) {
            Log::error('Failed to get food parcels with details: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get food parcel details by ID using stored procedure.
     *
     * @param int $id
     * @return object|null
     */
    public static function getDetailsByIdSP(int $id): ?object
    {
        try {
            $results = DB::select('CALL sp_get_food_parcel_by_id(?)', [$id]);
            return $results[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get food parcel details by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create food parcel using stored procedure.
     *
     * @param array $data
     * @return bool
     */
    public static function createWithSP(array $data): bool
    {
        try {
            DB::statement('CALL sp_create_food_parcel(?, ?, ?, ?)', [
                $data['stock_id'],
                $data['customer_id'],
                $data['is_active'] ?? true,
                $data['note'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update food parcel using stored procedure.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateWithSP(int $id, array $data): bool
    {
        try {
            DB::statement('CALL sp_update_food_parcel(?, ?, ?, ?, ?)', [
                $id,
                $data['stock_id'],
                $data['customer_id'],
                $data['is_active'] ?? true,
                $data['note'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete food parcel using stored procedure.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteWithSP(int $id): bool
    {
        try {
            DB::statement('CALL sp_delete_food_parcel(?)', [$id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get food parcel statistics using stored procedure.
     *
     * @return object
     */
    public static function getStatistics(): object
    {
        try {
            $results = DB::select('CALL sp_get_food_parcel_stats()');
            return $results[0] ?? (object)[
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'this_month' => 0
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get food parcel statistics: ' . $e->getMessage());
            throw $e;
        }
    }
}
