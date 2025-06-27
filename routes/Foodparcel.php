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

// Define individual routes instead of using resource
Route::get('food-parcels', [FoodParcelController::class, 'index'])->name('food-parcels.index');
Route::get('food-parcels/create', [FoodParcelController::class, 'create'])->name('food-parcels.create');
Route::post('food-parcels', [FoodParcelController::class, 'store'])->name('food-parcels.store');
Route::get('food-parcels/{id}', [FoodParcelController::class, 'show'])->name('food-parcels.show');
Route::get('food-parcels/{id}/edit', [FoodParcelController::class, 'edit'])->name('food-parcels.edit');
Route::put('food-parcels/{id}', [FoodParcelController::class, 'update'])->name('food-parcels.update');
Route::patch('food-parcels/{id}', [FoodParcelController::class, 'update']);
Route::delete('food-parcels/{id}', [FoodParcelController::class, 'destroy'])->name('food-parcels.destroy');
Route::post('food-parcels/{id}/delete', [FoodParcelController::class, 'destroy'])->name('food-parcels.destroy.post');

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

    // Fallback for DELETE requests (when method spoofing doesn't work)
    Route::post('/delete/{id}', [FoodParcelController::class, 'destroy'])->name('delete');
});
