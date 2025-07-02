<?php

namespace App\Http\Controllers;

use App\Models\FoodParcel;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
            $customers = Customer::where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number ?? $customer->id,
                        'first_name' => 'Klant',
                        'last_name' => '#' . $customer->id,
                        'name' => 'Klant #' . $customer->id,
                        'display_name' => 'Klant #' . $customer->id
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
            ])->with('error', 'Fout bij het laden van voedselpakketten. Probeer het opnieuw.');
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
            // Get customers for dropdown - simplified
            $customers = Customer::where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number ?? $customer->id,
                        'name' => 'Klant #' . $customer->id,
                        'display_name' => 'Klant #' . $customer->id
                    ];
                });

            // Get available stocks for dropdown - using stored procedure
            $stocks = Stock::getForDropdown();

            return view('food-parcels.create', compact('customers', 'stocks'));
        } catch (\Exception $e) {
            Log::error('Fout bij het laden van het aanmaakformulier. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het laden van het aanmaakformulier. Probeer het opnieuw.');
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
            // Validate the request with Dutch error messages
            $validatedData = $request->validate([
                'stock_id' => 'required|integer|exists:stocks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'is_active' => 'boolean',
                'note' => 'nullable|string|max:1000'
            ], [
                'stock_id.required' => 'Vergeet niet de voorraad in te vullen!',
                'stock_id.exists' => 'Het geselecteerde voorraaditem bestaat niet.',
                'customer_id.required' => 'Vergeet niet de klant in te vullen!',
                'customer_id.exists' => 'De geselecteerde klant bestaat niet.',
                'note.max' => 'De notitie mag maximaal 1000 tekens bevatten.'
            ]);

            // Create food parcel using stored procedure
            FoodParcel::createWithSP($validatedData);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Voedselpakket succesvol toegevoegd.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Fout bij het aanmaken van voedselpakket. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Fout bij het aanmaken van voedselpakket. Probeer het opnieuw.')
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
                    ->with('error', 'Voedselpakket niet gevonden.');
            }

            return view('food-parcels.show', compact('foodParcel'));
        } catch (\Exception $e) {
            Log::error('Fout bij het laden van voedselpakket details. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het laden van voedselpakket details. Probeer het opnieuw.');
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

            // Get customers for dropdown - simplified
            $customers = Customer::where('is_active', true)
                ->get()
                ->map(function ($customer) {
                    return (object)[
                        'id' => $customer->id,
                        'number' => $customer->number ?? $customer->id,
                        'name' => 'Klant #' . $customer->id,
                        'display_name' => 'Klant #' . $customer->id
                    ];
                });

            // Get available stocks for dropdown - include current stock even if inactive
            $stocks = Stock::getForDropdown();
            $currentStock = DB::select('SELECT * FROM stocks WHERE id = ?', [$foodParcel->stock_id]);

            if (!empty($currentStock)) {
                $currentStockData = $currentStock[0];
                $currentStockFormatted = (object)[
                    'id' => $currentStockData->id,
                    'product_name' => 'Huidig Voorraaditem',
                    'category_name' => 'Geselecteerd',
                    'quantity_in_stock' => $currentStockData->quantity_in_stock ?? 0,
                    'unit' => $currentStockData->unit ?? 'stuks',
                    'is_active' => $currentStockData->is_active,
                    'display_name' => 'Huidig Voorraaditem (ID: ' . $currentStockData->id . ')',
                    'received_date' => $currentStockData->received_date,
                    'delivered_date' => $currentStockData->delivered_date,
                ];

                // Add current stock if not already in the list
                if (!$stocks->contains('id', $foodParcel->stock_id)) {
                    $stocks->prepend($currentStockFormatted);
                }
            }

            return view('food-parcels.edit', compact('foodParcel', 'customers', 'stocks'));
        } catch (\Exception $e) {
            Log::error('Fout bij het laden van het bewerkingsformulier. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het laden van het bewerkingsformulier. Probeer het opnieuw.');
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
            // Get the current food parcel to check its status
            $currentParcel = FoodParcel::findOrFail($id);

            // Validate the request with Dutch error messages
            $validatedData = $request->validate([
                'stock_id' => 'required|integer|exists:stocks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'is_active' => 'boolean',
                'note' => 'nullable|string|max:1000'
            ], [
                'stock_id.required' => 'Vergeet niet de voorraad in te vullen!',
                'stock_id.exists' => 'Het geselecteerde voorraaditem bestaat niet.',
                'customer_id.required' => 'Vergeet niet de klant in te vullen!',
                'customer_id.exists' => 'De geselecteerde klant bestaat niet.',
                'note.max' => 'De notitie mag maximaal 1000 tekens bevatten.'
            ]);

            // Check if parcel is in active distribution (business rule)
            // Een pakket is "in distributie" als het actief is
            if ($currentParcel->is_active) {
                // Controleer of er belangrijke wijzigingen worden gemaakt
                $hasImportantChanges = (
                    $validatedData['customer_id'] != $currentParcel->customer_id ||
                    $validatedData['stock_id'] != $currentParcel->stock_id
                );

                if ($hasImportantChanges) {
                    return redirect()->back()
                        ->with('error', 'Wijzigen niet toegestaan: pakket is momenteel in distributie.')
                        ->withInput();
                }
            }

            // Update food parcel using stored procedure
            FoodParcel::updateWithSP($id, $validatedData);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Voedselpakket succesvol bijgewerkt.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Fout bij het bijwerken van voedselpakket. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Fout bij het bijwerken van voedselpakket. Probeer het opnieuw.')
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
            // Get the food parcel to check if it's in active distribution
            $foodParcel = FoodParcel::findOrFail($id);

            // Check if parcel is in active distribution (business rule)
            if ($foodParcel->is_active) {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'Kan voedselpakket niet verwijderen: gekoppeld aan actieve uitgifte.');
            }

            // Delete food parcel using stored procedure
            FoodParcel::deleteWithSP($id);

            return redirect()->route('food-parcels.index')
                ->with('success', 'Voedselpakket succesvol verwijderd.');
        } catch (\Exception $e) {
            Log::error('Fout bij het verwijderen van voedselpakket. Probeer het opnieuw: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het verwijderen van voedselpakket. Probeer het opnieuw.');
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
                    'Klant Naam',
                    'Klant Email',
                    'Voorraad Item',
                    'Categorie',
                    'Hoeveelheid',
                    'Status',
                    'Vervaldatum',
                    'Notitie',
                    'Aanmaakdatum'
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
                        $parcel->is_active ? 'Actief' : 'Inactief',
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
                ->with('error', 'Fout bij het exporteren van gegevens. Probeer het opnieuw.');
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
                ->with('error', 'Fout bij het exporteren van PDF. Probeer het opnieuw.');
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
                    ->with('success', "{$deletedCount} voedselpakket(ten) succesvol verwijderd.");
            } else {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'Geen voedselpakketten zijn verwijderd.');
            }
        } catch (\Exception $e) {
            Log::error('Error in bulk delete: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het verwijderen van geselecteerde voedselpakketten. Probeer het opnieuw.');
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
                $statusText = $newStatus ? 'geactiveerd' : 'gedeactiveerd';
                return redirect()->route('food-parcels.index')
                    ->with('success', "{$updatedCount} voedselpakket(ten) succesvol {$statusText}.");
            } else {
                return redirect()->route('food-parcels.index')
                    ->with('error', 'Geen voedselpakketten zijn bijgewerkt.');
            }
        } catch (\Exception $e) {
            Log::error('Error in bulk status update: ' . $e->getMessage());

            return redirect()->route('food-parcels.index')
                ->with('error', 'Fout bij het bijwerken van geselecteerde voedselpakketten. Probeer het opnieuw.');
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
