<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function callStoredProcedure()
    {
        $result = Stock::callMyStoredProcedure();
        return response()->json($result);
    }
    
    public function index()
    {
        $stocks = Stock::all();
        return view('Stocks.Index', compact('stocks'));
    }
}
