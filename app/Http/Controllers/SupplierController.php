<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // You can pass suppliers from the database here if needed
        return view('suppliers.index');
    }
}
