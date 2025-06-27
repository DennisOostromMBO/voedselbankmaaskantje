<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $stocks = DB::select('CALL get_all_stocks()');
        return view('Stocks.Index', compact('stocks'));
    }
    public function create()
    {
        $stocks = Stock::createStocks();
        return view('Stocks.Create', compact('stocks'));
    }
    
    public function store(Request $request)
    {
        DB::statement('CALL create_stocks(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->input('product_category_id'),
            $request->input('ontvangdatum'),
            $request->input('uigeleverddatum'),
            $request->input('eenheid'),
            $request->input('aantalOpVoorad'),
            $request->input('aantalUigegeven'),
            $request->input('aantalBijgeleverd'),
            $request->input('is_active'),
            $request->input('note')
        ]);
        return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
    }
}
