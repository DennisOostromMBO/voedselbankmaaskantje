<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

/**
 * FoodParcel Model
 *
 * Represents food parcels in the food bank system. A food parcel connects
 * a customer with stock items, indicating what food items have been allocated
 * or distributed to which customers.
 *
 * Database relationships:
 * - Belongs to Customer (customer_id -> customers.id)
 * - Belongs to Stock (stock_id -> stocks.id)
 * - Customer connects to Family -> Person for customer details
 * - Stock connects to ProductCategory for product information
 *
 * This model uses a hybrid approach:
 * - Primary: Stored procedures for optimized database operations
 * - Fallback: Eloquent queries for reliability when procedures fail
 */
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
     * Get all food parcels with customer and stock details using stored procedure.
     * Falls back to Eloquent queries if stored procedure fails.
     *
     * @param array $filters Optional filters (customer_id, is_active, search)
     * @return Collection
     */
    public static function getAllWithDetails(array $filters = []): Collection
    {
        try {
            // Extract and sanitize filter parameters
            $customerFilter = $filters['customer_id'] ?? null;
            $isActiveFilter = isset($filters['is_active']) && $filters['is_active'] !== '' ? (bool)$filters['is_active'] : null;
            $searchFilter = $filters['search'] ?? null;

            // Primary method: Use stored procedure for optimal performance
            // Stored procedures are pre-compiled and optimized by the database
            $results = DB::select('CALL sp_get_food_parcels_with_details(?, ?, ?)', [
                $customerFilter,
                $isActiveFilter,
                $searchFilter
            ]);

            // Transform stored procedure results to standardized object structure
            return collect($results)->map(function ($parcel) {
                return (object)[
                    // Core food parcel data
                    'id' => $parcel->id,
                    'customer_id' => $parcel->customer_id,
                    'stock_id' => $parcel->stock_id,
                    'is_active' => (bool)$parcel->is_active,
                    'note' => $parcel->note,
                    'created_at' => $parcel->created_at,
                    'updated_at' => $parcel->updated_at,

                    // Customer information with safe fallbacks
                    'customer_name' => $parcel->customer_name ?? 'Onbekende Klant', // "Unknown Customer"
                    'customer_first_name' => $parcel->customer_first_name ?? '',
                    'customer_last_name' => $parcel->customer_last_name ?? '',
                    'customer_number' => $parcel->customer_number ?? '',
                    'family_name' => $parcel->family_name ?? '',

                    // Product/Stock information
                    'product_name' => $parcel->category_name ?? 'Onbekend Product', // "Unknown Product"
                    'category_name' => $parcel->category_name ?? 'Onbekende Categorie', // "Unknown Category"
                    'stock_name' => ($parcel->category_name ?? 'Onbekend') . ' - Stock #' . $parcel->stock_id,
                    'stock_is_active' => $parcel->stock_is_active ?? false,
                    'status_text' => $parcel->status_text ?? 'Unknown',
                ];
            });
        } catch (\Exception $e) {
            // Log stored procedure failure for debugging
            Log::error('Stored procedure failed, falling back to Eloquent query: ' . $e->getMessage());

            // Graceful fallback: Use Eloquent queries if stored procedure fails
            // This ensures the application continues working even if database procedures have issues
            return self::getWithEloquentQuery($filters);
        }
    }

    /**
     * Fallback method using Eloquent queries with joins.
     * This method is used when the stored procedure fails to ensure data retrieval continues.
     *
     * Database relationship chain:
     * food_parcels -> customers -> families -> persons (for customer details)
     * food_parcels -> stocks -> product_categories (for stock/product details)
     *
     * @param array $filters Optional filters (customer_id, is_active, search)
     * @return Collection
     */
    private static function getWithEloquentQuery(array $filters = []): Collection
    {
        try {
            // Build complex query with multiple table joins to get all related data
            // This mirrors the data structure returned by the stored procedure
            $query = self::query()
                // Join customer information
                ->leftJoin('customers', 'food_parcels.customer_id', '=', 'customers.id')
                // Join family information through customer
                ->leftJoin('families', 'customers.family_id', '=', 'families.id')
                // Join person details through family (contains names, age, etc.)
                ->leftJoin('persons', 'families.person_id', '=', 'persons.id')
                // Join stock information
                ->leftJoin('stocks', 'food_parcels.stock_id', '=', 'stocks.id')
                // Join product category information through stock
                ->leftJoin('product_categories', 'stocks.product_category_id', '=', 'product_categories.id')
                ->select([
                    // Core food parcel data
                    'food_parcels.*',
                    // Customer identification and contact info
                    'customers.number as customer_number',
                    'persons.first_name as customer_first_name',
                    'persons.last_name as customer_last_name',
                    'persons.infix as customer_infix',
                    'persons.full_name as customer_full_name',
                    'persons.age as customer_age',
                    // Family information
                    'families.name as family_name',
                    // Stock status and details
                    'stocks.is_active as stock_is_active',
                    'stocks.note as stock_note',
                    'stocks.quantity_in_stock',
                    'stocks.unit as stock_unit',
                    'stocks.received_date as stock_received_date',
                    'stocks.delivered_date as stock_delivered_date',
                    // Product categorization
                    'product_categories.category_name as category_name'
                ]);

            // Apply dynamic filters based on request parameters
            if (!empty($filters['customer_id'])) {
                $query->where('food_parcels.customer_id', $filters['customer_id']);
            }

            if (isset($filters['is_active']) && $filters['is_active'] !== '') {
                $query->where('food_parcels.is_active', (bool)$filters['is_active']);
            }

            // Global search across multiple fields for flexible filtering
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    // Search in person names (first, last, full name)
                    $q->where('persons.first_name', 'LIKE', "%{$search}%")
                      ->orWhere('persons.last_name', 'LIKE', "%{$search}%")
                      ->orWhere('persons.full_name', 'LIKE', "%{$search}%")
                      // Search in customer number and parcel notes
                      ->orWhere('customers.number', 'LIKE', "%{$search}%")
                      ->orWhere('food_parcels.note', 'LIKE', "%{$search}%")
                      // Search in family and product category names
                      ->orWhere('families.name', 'LIKE', "%{$search}%")
                      ->orWhere('product_categories.category_name', 'LIKE', "%{$search}%");
                });
            }

            // Order by creation date (newest first) for better UX
            $results = $query->orderBy('food_parcels.created_at', 'desc')->get();

            // Transform database results to standardized object structure
            // This ensures consistency between stored procedure and Eloquent results
            return $results->map(function ($parcel) {
                // Build customer full name with proper handling of infix (middle name/prefix)
                // Example: "Jan van der Berg" where "van der" is the infix
                $customerName = $parcel->customer_full_name ??
                              trim(($parcel->customer_first_name ?? '') . ' ' .
                              ($parcel->customer_infix ? $parcel->customer_infix . ' ' : '') .
                              ($parcel->customer_last_name ?? ''));

                return (object)[
                    // Core food parcel identifiers and status
                    'id' => $parcel->id,
                    'customer_id' => $parcel->customer_id,
                    'stock_id' => $parcel->stock_id,
                    'is_active' => (bool)$parcel->is_active,
                    'note' => $parcel->note,
                    'created_at' => $parcel->created_at,
                    'updated_at' => $parcel->updated_at,

                    // Customer information with fallbacks for missing data
                    'customer_name' => $customerName ?: 'Onbekende Klant', // "Unknown Customer" fallback
                    'customer_first_name' => $parcel->customer_first_name ?? '',
                    'customer_last_name' => $parcel->customer_last_name ?? '',
                    'customer_infix' => $parcel->customer_infix ?? '',
                    'customer_number' => $parcel->customer_number ?? '',
                    'customer_email' => '', // Not available in current database schema
                    'customer_age' => $parcel->customer_age ?? '',
                    'family_name' => $parcel->family_name ?? '',

                    // Product/Stock information with intelligent naming
                    'product_name' => $parcel->category_name ?? 'Stock Item #' . $parcel->stock_id,
                    'category_name' => $parcel->category_name ?? 'Algemeen', // "General" category fallback
                    'stock_name' => ($parcel->category_name ?? 'Stock') . ' #' . $parcel->stock_id,
                    'stock_is_active' => (bool)($parcel->stock_is_active ?? false),
                    'stock_note' => $parcel->stock_note ?? '',
                    'stock_quantity' => $parcel->quantity_in_stock ?? 0,
                    'stock_unit' => $parcel->stock_unit ?? 'stuks', // "pieces" default unit
                    'stock_received_date' => $parcel->stock_received_date,
                    'stock_delivered_date' => $parcel->stock_delivered_date,

                    // Human-readable status text for UI display
                    'status_text' => $parcel->is_active ? 'Actief' : 'Inactief', // "Active" / "Inactive"
                ];
            });
        } catch (\Exception $e) {
            // Log the error for debugging but don't expose database errors to users
            Log::error('Eloquent fallback query failed: ' . $e->getMessage());
            // Return empty collection to prevent application crash
            return collect([]);
        }
    }

    /**
     * Get food parcel details by ID using stored procedure.
     * Retrieves comprehensive information about a single food parcel including
     * customer details, stock information, and product categories.
     *
     * @param int $id The food parcel ID to retrieve
     * @return object|null Returns food parcel object with details or null if not found
     */
    public static function getDetailsByIdSP(int $id): ?object
    {
        try {
            // Call stored procedure to get detailed food parcel information
            $results = DB::select('CALL sp_get_food_parcel_by_id(?)', [$id]);

            // Return null if no results found (food parcel doesn't exist)
            if (empty($results)) {
                return null;
            }

            $parcel = $results[0];

            // Transform database result to standardized object structure
            return (object)[
                'id' => $parcel->id,
                'customer_id' => $parcel->customer_id,
                'stock_id' => $parcel->stock_id,
                'is_active' => (bool)$parcel->is_active,
                'note' => $parcel->note,
                'created_at' => $parcel->created_at,
                'updated_at' => $parcel->updated_at,
                'customer_name' => $parcel->customer_name ?? 'Onbekende Klant',
                'customer_first_name' => $parcel->customer_first_name ?? '',
                'customer_last_name' => $parcel->customer_last_name ?? '',
                'customer_infix' => $parcel->customer_infix ?? '',
                'customer_number' => $parcel->customer_number ?? '',
                'customer_age' => $parcel->customer_age ?? '',
                'family_name' => $parcel->family_name ?? '',
                'category_id' => $parcel->category_id ?? null,
                'category_name' => $parcel->category_name ?? 'Onbekende Categorie',
                'category_description' => $parcel->category_description ?? '',
                'stock_is_active' => $parcel->stock_is_active ?? false,
                'stock_note' => $parcel->stock_note ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get food parcel details: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create food parcel using stored procedure.
     * Uses database stored procedure for optimized insert operation with validation.
     *
     * @param array $data Food parcel data (stock_id, customer_id, is_active, note)
     * @return bool True if creation successful
     * @throws \Exception If creation fails
     */
    public static function createWithSP(array $data): bool
    {
        try {
            // Call stored procedure with validated parameters
            // Stored procedure handles foreign key validation and business logic
            DB::statement('CALL sp_create_food_parcel(?, ?, ?, ?)', [
                $data['stock_id'],
                $data['customer_id'],
                $data['is_active'] ?? true, // Default to active if not specified
                $data['note'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            // Log detailed error information for debugging
            Log::error('Failed to create food parcel: ' . $e->getMessage());
            // Re-throw exception to let calling code handle it appropriately
            throw $e;
        }
    }

    /**
     * Update food parcel using stored procedure.
     * Uses database stored procedure for optimized update operation with validation.
     *
     * @param int $id Food parcel ID to update
     * @param array $data Updated food parcel data
     * @return bool True if update successful
     * @throws \Exception If update fails
     */
    public static function updateWithSP(int $id, array $data): bool
    {
        try {
            // Call stored procedure with all required parameters
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
     * Uses database stored procedure for safe deletion with proper cleanup.
     *
     * @param int $id Food parcel ID to delete
     * @return bool True if deletion successful
     * @throws \Exception If deletion fails
     */
    public static function deleteWithSP(int $id): bool
    {
        try {
            // Call stored procedure for safe deletion
            // Stored procedure may handle cascading deletes or validation
            DB::statement('CALL sp_delete_food_parcel(?)', [$id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete food parcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get food parcel statistics using stored procedure.
     * Retrieves aggregated statistics for dashboard and reporting purposes.
     * Includes counts for total, active, inactive parcels and time-based metrics.
     *
     * @return object Statistics object with various counts and metrics
     */
    public static function getStatistics(): object
    {
        try {
            // Call stored procedure that calculates comprehensive statistics
            $results = DB::select('CALL sp_get_food_parcel_stats()');

            if (!empty($results)) {
                $stats = $results[0];
                return (object)[
                    'total' => $stats->total ?? 0,           // Total food parcels count
                    'active' => $stats->active ?? 0,         // Active parcels count
                    'inactive' => $stats->inactive ?? 0,     // Inactive parcels count
                    'this_month' => $stats->this_month ?? 0, // Parcels created this month
                    'today' => $stats->today ?? 0,           // Parcels created today
                    'this_week' => $stats->this_week ?? 0    // Parcels created this week
                ];
            }

            // Return default statistics object if no data available
            return (object)[
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'this_month' => 0,
                'today' => 0,
                'this_week' => 0
            ];
        } catch (\Exception $e) {
            // Log error but return safe default values to prevent UI crashes
            Log::error('Failed to get food parcel statistics: ' . $e->getMessage());
            return (object)[
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'this_month' => 0,
                'today' => 0,
                'this_week' => 0
            ];
        }
    }
}
