<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
 
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');