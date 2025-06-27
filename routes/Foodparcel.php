<?php

use App\Http\Controllers\FoodParcelController;
use Illuminate\Support\Facades\Route;

/**
 * Food Parcel Routes
 *
 * RESTful routes for food parcel management with proper naming conventions
 * and middleware protection.
 *
 * @author Wassim
 */

// Food Parcels Resource Routes
Route::resource('food-parcels', FoodParcelController::class)->names([
    'index' => 'food-parcels.index',
    'create' => 'food-parcels.create',
    'store' => 'food-parcels.store',
    'show' => 'food-parcels.show',
    'edit' => 'food-parcels.edit',
    'update' => 'food-parcels.update',
    'destroy' => 'food-parcels.destroy'
]);

// Additional custom routes if needed
Route::prefix('food-parcels')->name('food-parcels.')->group(function () {
    // Export routes
    Route::get('/export/csv', [FoodParcelController::class, 'exportCsv'])->name('export.csv');
    Route::get('/export/pdf', [FoodParcelController::class, 'exportPdf'])->name('export.pdf');

    // Bulk operations
    Route::post('/bulk-delete', [FoodParcelController::class, 'bulkDelete'])->name('bulk.delete');
    Route::post('/bulk-update-status', [FoodParcelController::class, 'bulkUpdateStatus'])->name('bulk.update-status');

    // API endpoints for AJAX/JSON responses
    Route::get('/api/list', [FoodParcelController::class, 'apiIndex'])->name('api.index');
    Route::get('/api/{id}', [FoodParcelController::class, 'apiShow'])->name('api.show');
});
