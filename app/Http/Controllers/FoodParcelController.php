<?php

namespace App\Http\Controllers;

use App\Models\FoodParcel;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Food Parcel Controller
 * 
 * Handles CRUD operations for food parcels with proper error handling,
 * validation, and responsive design support.
 * 
 * @author Wassim
 * @version 1.0
 */
class FoodParcelController extends Controller
{
    /**
     * Display a listing of food parcels with filtering and search.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            // Get filter parameters
            $filters = [
                'customer_id' => $request->get('customer_id'),
                'is_active' => $request->get('is_active'),
                'search' => $request->get('search')
            ];

            // Remove null values from filters
            $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

            // Get food parcels with details using stored procedure
            $foodParcels = FoodParcel::getAllWithDetails($filters);

            // Get statistics for dashboard
            $statistics = FoodParcel::getStatistics();

            // Get customers for filter dropdown
            $customers = Customer::with(['family.person'])
                ->where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number,
                        'name' => $customer->family->person->full_name ?? 'Customer #' . $customer->number,
                        'display_name' => 'Customer #' . $customer->number . ' - ' . ($customer->family->person->full_name ?? 'Unknown')
                    ];
                });

            return view('food-parcels.index', compact(
                'foodParcels',
                'statistics', 
                'customers',
                'filters'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading food parcels index: ' . $e->getMessage());
            
            return view('food-parcels.index', [
                'foodParcels' => collect([]),
                'statistics' => (object)[
                    'total' => 0,
                    'active' => 0, 
                    'inactive' => 0,
                    'this_month' => 0
                ],
                'customers' => collect([]),
                'filters' => []
            ])->with('error', 'Failed to load food parcels. Please try again.');
        }
    }

    /**
     * Show the form for creating a new food parcel.
     * 
     * @return View|RedirectResponse
     */
    public function create()
    {
        try {
            // Get customers for dropdown
            $customers = Customer::with(['family.person'])
                ->where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number,
                        'name' => $customer->family->person->full_name ?? 'Customer #' . $customer->number,
                        'display_name' => 'Customer #' . $customer->number . ' - ' . ($customer->family->person->full_name ?? 'Unknown')
                    ];
                });

            // Get available stocks for dropdown
            $stocks = Stock::with(['productCategory.product'])
                ->where('is_active', true)
                ->get()
                ->map(function ($stock) {
                    return (object)[
                        'id' => $stock->id,
                        'name' => $stock->productCategory->product->name ?? 'Stock #' . $stock->id,
                        'category' => $stock->productCategory->category_name ?? 'Unknown Category',
                        'description' => $stock->productCategory->product->description ?? '',
                        'display_name' => 'Stock #' . $stock->id . ' - ' . ($stock->productCategory->category_name ?? 'Unknown') . ' (' . ($stock->productCategory->product->name ?? 'Unknown Product') . ')'
                    ];
                });

            return view('food-parcels.create', compact('customers', 'stocks'));
        } catch (\Exception $e) {
            Log::error('Error loading food parcel create form: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to load create form. Please try again.');
        }
    }

    /**
     * Store a newly created food parcel.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'stock_id' => 'required|integer|exists:stocks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'is_active' => 'boolean',
                'note' => 'nullable|string|max:1000'
            ]);

            // Create food parcel using stored procedure
            FoodParcel::createWithSP($validatedData);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Food parcel created successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating food parcel: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create food parcel. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified food parcel.
     * 
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        try {
            // Get food parcel details using stored procedure
            $foodParcel = FoodParcel::getDetailsByIdSP($id);

            if (!$foodParcel) {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'Food parcel not found.');
            }

            return view('food-parcels.show', compact('foodParcel'));
        } catch (\Exception $e) {
            Log::error('Error showing food parcel: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to load food parcel details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified food parcel.
     * 
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            // Get food parcel details
            $foodParcel = FoodParcel::findOrFail($id);

            // Get customers for dropdown
            $customers = Customer::with(['family.person'])
                ->where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number,
                        'name' => $customer->family->person->full_name ?? 'Customer #' . $customer->number,
                        'display_name' => 'Customer #' . $customer->number . ' - ' . ($customer->family->person->full_name ?? 'Unknown')
                    ];
                });

            // Get available stocks for dropdown
            $stocks = Stock::with(['productCategory.product'])
                ->where('is_active', true)
                ->orWhere('id', $foodParcel->stock_id)
                ->get()
                ->map(function ($stock) {
                    return (object)[
                        'id' => $stock->id,
                        'name' => $stock->productCategory->product->name ?? 'Stock #' . $stock->id,
                        'category' => $stock->productCategory->category_name ?? 'Unknown Category',
                        'description' => $stock->productCategory->product->description ?? '',
                        'display_name' => 'Stock #' . $stock->id . ' - ' . ($stock->productCategory->category_name ?? 'Unknown') . ' (' . ($stock->productCategory->product->name ?? 'Unknown Product') . ')'
                    ];
                });

            return view('food-parcels.edit', compact('foodParcel', 'customers', 'stocks'));
        } catch (\Exception $e) {
            Log::error('Error loading food parcel edit form: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to load edit form. Please try again.');
        }
    }

    /**
     * Update the specified food parcel.
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'stock_id' => 'required|integer|exists:stocks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'is_active' => 'boolean',
                'note' => 'nullable|string|max:1000'
            ]);

            // Update food parcel using stored procedure
            FoodParcel::updateWithSP($id, $validatedData);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Food parcel updated successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating food parcel: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update food parcel. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified food parcel.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            // Delete food parcel using stored procedure
            FoodParcel::deleteWithSP($id);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Food parcel deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting food parcel: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to delete food parcel. Please try again.');
        }
    }

    /**
     * Export food parcels to CSV format.
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        try {
            // Get filter parameters
            $filters = [
                'customer_id' => $request->get('customer_id'),
                'is_active' => $request->get('is_active'),
                'search' => $request->get('search')
            ];

            // Remove null values from filters
            $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

            // Get food parcels data
            $foodParcels = FoodParcel::getAllWithDetails($filters);

            $filename = 'food_parcels_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($foodParcels) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Customer Name',
                    'Customer Email',
                    'Stock Item',
                    'Category',
                    'Quantity',
                    'Status',
                    'Expiry Date',
                    'Note',
                    'Created Date'
                ]);

                // CSV Data
                foreach ($foodParcels as $parcel) {
                    fputcsv($file, [
                        $parcel->id,
                        $parcel->customer_name ?? 'N/A',
                        $parcel->customer_email ?? 'N/A',
                        $parcel->product_name ?? 'N/A',
                        $parcel->category_name ?? 'N/A',
                        $parcel->stock_quantity ?? 0,
                        $parcel->is_active ? 'Active' : 'Inactive',
                        $parcel->stock_expiry_date ? 
                            \Carbon\Carbon::parse($parcel->stock_expiry_date)->format('Y-m-d') : 'N/A',
                        $parcel->note ?? '',
                        \Carbon\Carbon::parse($parcel->created_at)->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting food parcels to CSV: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to export data. Please try again.');
        }
    }

    /**
     * Export food parcels to PDF format.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportPdf(Request $request)
    {
        try {
            // Get filter parameters
            $filters = [
                'customer_id' => $request->get('customer_id'),
                'is_active' => $request->get('is_active'),
                'search' => $request->get('search')
            ];

            // Remove null values from filters
            $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

            // Get food parcels data
            $foodParcels = FoodParcel::getAllWithDetails($filters);
            $statistics = FoodParcel::getStatistics();

            // For now, return a simple view that can be printed as PDF
            // In production, you might want to use a package like dompdf or wkhtmltopdf
            return view('food-parcels.export-pdf', compact('foodParcels', 'statistics'));
        } catch (\Exception $e) {
            Log::error('Error exporting food parcels to PDF: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to export PDF. Please try again.');
        }
    }

    /**
     * Bulk delete food parcels.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'selected_ids' => 'required|array',
                'selected_ids.*' => 'integer|exists:food_parcels,id'
            ]);

            $selectedIds = $request->get('selected_ids', []);
            $deletedCount = 0;

            foreach ($selectedIds as $id) {
                try {
                    FoodParcel::deleteWithSP($id);
                    $deletedCount++;
                } catch (\Exception $e) {
                    Log::warning("Failed to delete food parcel ID {$id}: " . $e->getMessage());
                }
            }

            if ($deletedCount > 0) {
                return redirect()->route('food-parcels.index')
                    ->with('success', "Successfully deleted {$deletedCount} food parcel(s).");
            } else {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'No food parcels were deleted.');
            }
        } catch (\Exception $e) {
            Log::error('Error in bulk delete: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to delete selected food parcels. Please try again.');
        }
    }

    /**
     * Bulk update status of food parcels.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'selected_ids' => 'required|array',
                'selected_ids.*' => 'integer|exists:food_parcels,id',
                'status' => 'required|boolean'
            ]);

            $selectedIds = $request->get('selected_ids', []);
            $newStatus = $request->boolean('status');
            $updatedCount = 0;

            foreach ($selectedIds as $id) {
                try {
                    // Get current food parcel data
                    $currentParcel = FoodParcel::findOrFail($id);
                    
                    // Update with new status
                    FoodParcel::updateWithSP($id, [
                        'stock_id' => $currentParcel->stock_id,
                        'customer_id' => $currentParcel->customer_id,
                        'is_active' => $newStatus,
                        'note' => $currentParcel->note
                    ]);
                    
                    $updatedCount++;
                } catch (\Exception $e) {
                    Log::warning("Failed to update food parcel ID {$id}: " . $e->getMessage());
                }
            }

            if ($updatedCount > 0) {
                $statusText = $newStatus ? 'activated' : 'deactivated';
                return redirect()->route('food-parcels.index')
                    ->with('success', "Successfully {$statusText} {$updatedCount} food parcel(s).");
            } else {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'No food parcels were updated.');
            }
        } catch (\Exception $e) {
            Log::error('Error in bulk status update: ' . $e->getMessage());
            
            return redirect()->route('food-parcels.index')
                ->with('error', 'Failed to update selected food parcels. Please try again.');
        }
    }

    /**
     * API endpoint - Get food parcels as JSON.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        try {
            // Get filter parameters
            $filters = [
                'customer_id' => $request->get('customer_id'),
                'is_active' => $request->get('is_active'),
                'search' => $request->get('search')
            ];

            // Remove null values from filters
            $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

            // Get food parcels with details
            $foodParcels = FoodParcel::getAllWithDetails($filters);
            $statistics = FoodParcel::getStatistics();

            return response()->json([
                'success' => true,
                'data' => [
                    'food_parcels' => $foodParcels,
                    'statistics' => $statistics
                ],
                'message' => 'Food parcels retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in API food parcels index: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve food parcels',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint - Get single food parcel as JSON.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow(int $id)
    {
        try {
            $foodParcel = FoodParcel::getDetailsByIdSP($id);

            if (!$foodParcel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Food parcel not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $foodParcel,
                'message' => 'Food parcel retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in API food parcel show: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve food parcel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
