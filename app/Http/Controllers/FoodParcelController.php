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

            // Get food parcels with pagination (5 per page)
            $foodParcels = FoodParcel::getAllWithDetailsPaginated($filters, 5);

            // Get statistics for dashboard
            $statistics = FoodParcel::getStatistics();

            // Get customers for filter dropdown using Dennis's stored procedure
            $customersData = Customer::getAllFromSP();
            $customers = collect($customersData)->map(function ($customer) {
                return (object)[
                    'id' => $customer->id,
                    'number' => $customer->customer_number ?? $customer->id,
                    'first_name' => $customer->first_name ?? 'Klant',
                    'last_name' => '#' . $customer->id,
                    'name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'display_name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'email' => $customer->email ?? '',
                    'family_name' => $customer->family_name ?? ''
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
            // Get customers for dropdown using Dennis's stored procedure
            $customersData = Customer::getAllFromSP();
            $customers = collect($customersData)->map(function ($customer) {
                return (object)[
                    'id' => $customer->id,
                    'number' => $customer->customer_number ?? $customer->id,
                    'name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'display_name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'email' => $customer->email ?? '',
                    'family_name' => $customer->family_name ?? ''
                ];
            });

            // Get available stocks for dropdown using Mahdi's stored procedure
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
            // Try to get food parcel details using stored procedure, fallback to Eloquent
            $foodParcel = FoodParcel::getDetailsByIdSP($id);

            // If stored procedure fails, fallback to Eloquent with joins including contacts
            if (!$foodParcel) {
                $foodParcelData = FoodParcel::query()
                    ->leftJoin('customers', 'food_parcels.customer_id', '=', 'customers.id')
                    ->leftJoin('families', 'customers.family_id', '=', 'families.id')
                    ->leftJoin('persons', 'families.person_id', '=', 'persons.id')
                    ->leftJoin('contacts', 'food_parcels.customer_id', '=', 'contacts.customer_id')
                    ->leftJoin('stocks', 'food_parcels.stock_id', '=', 'stocks.id')
                    ->leftJoin('product_categories', 'stocks.product_category_id', '=', 'product_categories.id')
                    ->leftJoin('products', 'product_categories.product_id', '=', 'products.id')
                    ->select([
                        'food_parcels.*',
                        'customers.number as customer_number',
                        'persons.first_name as customer_first_name',
                        'persons.last_name as customer_last_name',
                        'persons.infix as customer_infix',
                        'persons.full_name as customer_full_name',
                        'persons.age as customer_age',
                        'families.name as family_name',
                        'contacts.email as customer_email',
                        'contacts.mobile as customer_phone',
                        'stocks.is_active as stock_is_active',
                        'stocks.note as stock_note',
                        'stocks.quantity_in_stock',
                        'stocks.unit as stock_unit',
                        'stocks.received_date as stock_received_date',
                        'stocks.delivered_date as stock_delivered_date',
                        'product_categories.category_name as category_name',
                        'products.product_name as product_name'
                    ])
                    ->where('food_parcels.id', $id)
                    ->first();

                if (!$foodParcelData) {
                    return redirect()->route('food-parcels.index')
                        ->with('error', 'Voedselpakket niet gevonden.');
                }

                // Transform to match expected structure
                $customerName = $foodParcelData->customer_full_name ??
                              trim(($foodParcelData->customer_first_name ?? '') . ' ' .
                              ($foodParcelData->customer_infix ? $foodParcelData->customer_infix . ' ' : '') .
                              ($foodParcelData->customer_last_name ?? ''));

                $foodParcel = (object)[
                    'id' => $foodParcelData->id,
                    'customer_id' => $foodParcelData->customer_id,
                    'stock_id' => $foodParcelData->stock_id,
                    'is_active' => (bool)$foodParcelData->is_active,
                    'note' => $foodParcelData->note,
                    'created_at' => $foodParcelData->created_at,
                    'updated_at' => $foodParcelData->updated_at,
                    'customer_name' => $customerName ?: 'Onbekende Klant',
                    'customer_first_name' => $foodParcelData->customer_first_name ?? '',
                    'customer_last_name' => $foodParcelData->customer_last_name ?? '',
                    'customer_infix' => $foodParcelData->customer_infix ?? '',
                    'customer_number' => $foodParcelData->customer_number ?? '',
                    'customer_email' => $foodParcelData->customer_email ?? '',
                    'customer_phone' => $foodParcelData->customer_phone ?? '',
                    'customer_age' => $foodParcelData->customer_age ?? '',
                    'family_name' => $foodParcelData->family_name ?? '',
                    'category_name' => $foodParcelData->category_name ?? 'Onbekende Categorie',
                    'product_name' => $foodParcelData->product_name ?? '',
                    'stock_is_active' => (bool)($foodParcelData->stock_is_active ?? false),
                    'stock_note' => $foodParcelData->stock_note ?? '',
                    'stock_quantity' => $foodParcelData->quantity_in_stock ?? 0,
                    'stock_unit' => $foodParcelData->stock_unit ?? 'stuks',
                    'stock_received_date' => $foodParcelData->stock_received_date,
                    'stock_delivered_date' => $foodParcelData->stock_delivered_date,
                    'status_text' => $foodParcelData->is_active ? 'Actief' : 'Inactief',
                ];
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

            // Get customers for dropdown using Dennis's stored procedure
            $customersData = Customer::getAllFromSP();
            $customers = collect($customersData)->map(function ($customer) {
                return (object)[
                    'id' => $customer->id,
                    'number' => $customer->customer_number ?? $customer->id,
                    'name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'display_name' => ($customer->first_name ?? 'Klant') . ' ' . ($customer->last_name ?? '#' . $customer->id),
                    'email' => $customer->email ?? '',
                    'family_name' => $customer->family_name ?? ''
                ];
            });

            // Get available stocks for dropdown using Mahdi's stored procedure
            $stocks = Stock::getForDropdown();

            // Get current stock details even if it's not active anymore
            $currentStockResults = DB::select('CALL get_all_stocks()');
            $currentStock = collect($currentStockResults)->firstWhere('id', $foodParcel->stock_id);

            if ($currentStock && !$stocks->contains('id', $foodParcel->stock_id)) {
                $currentStockFormatted = (object)[
                    'id' => $currentStock->id,
                    'product_name' => $currentStock->product_name ?? 'Huidig Product',
                    'category_name' => $currentStock->category_name ?? 'Huidige Categorie',
                    'quantity_in_stock' => $currentStock->quantity_in_stock ?? 0,
                    'unit' => $currentStock->unit ?? 'stuks',
                    'is_active' => $currentStock->is_active,
                    'display_name' => 'Huidig: ' . ($currentStock->product_name ?? 'Product') . ' - ' . ($currentStock->category_name ?? 'Categorie') . ' (ID: ' . $currentStock->id . ')',
                    'received_date' => $currentStock->received_date,
                    'delivered_date' => $currentStock->delivered_date,
                ];

                // Add current stock to the beginning of the list
                $stocks = $stocks->prepend($currentStockFormatted);
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

            // Delete food parcel using stored procedure, fallback to Eloquent
            try {
                FoodParcel::deleteWithSP($id);
            } catch (\Exception $spException) {
                Log::warning('Stored procedure delete failed, using Eloquent fallback: ' . $spException->getMessage());
                // Fallback to regular Eloquent delete
                $foodParcel->delete();
            }

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
