<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;

Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
Route::post('/stocks/{id}/update-quantities', [StockController::class, 'updateQuantities'])->name('stocks.updateQuantities');
Route::get('/stocks/{id}/edit', [StockController::class, 'edit'])->name('stocks.edit');
Route::delete('/stocks/{id}', [StockController::class, 'destroy'])->name('stocks.destroy');
Route::get('/stocks/{id}', [StockController::class, 'show'])->name('stocks.show');
