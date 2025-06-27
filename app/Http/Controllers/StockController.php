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
        // Get all categories with their short names
        $categoriesRaw = DB::table('product_categories')
            ->select('id', 'category_name', DB::raw('
                CASE
                    WHEN category_name LIKE "Aardappels%" THEN "Groente & Fruit"
                    WHEN category_name LIKE "Kaas en vleeswaren%" THEN "Vleeswaren"
                    WHEN category_name LIKE "Zuivel%" THEN "Zuivel"
                    WHEN category_name LIKE "Bakkerij%" THEN "Bakkerij"
                    WHEN category_name LIKE "Frisdank%" THEN "Dranken"
                    WHEN category_name LIKE "Pasta%" THEN "Pasta & Wereld"
                    WHEN category_name LIKE "Soepen%" THEN "Soepen & Sauzen"
                    WHEN category_name LIKE "Snoep%" THEN "Snoep & Snacks"
                    WHEN category_name LIKE "Baby%" THEN "Baby & Hygiëne"
                    ELSE category_name
                END as short_name
            '))
            ->get();

        // Group categories by short_name and pick the first id for each
        $categories = collect();
        foreach ($categoriesRaw as $cat) {
            if (!$categories->has($cat->short_name)) {
                $categories->put($cat->short_name, $cat->id);
            }
        }

        // Fetch all products with their category short_name
        $products = DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('
                    CASE
                        WHEN product_categories.category_name LIKE "Aardappels%" THEN "Groente & Fruit"
                        WHEN product_categories.category_name LIKE "Kaas en vleeswaren%" THEN "Vleeswaren"
                        WHEN product_categories.category_name LIKE "Zuivel%" THEN "Zuivel"
                        WHEN product_categories.category_name LIKE "Bakkerij%" THEN "Bakkerij"
                        WHEN product_categories.category_name LIKE "Frisdank%" THEN "Dranken"
                        WHEN product_categories.category_name LIKE "Pasta%" THEN "Pasta & Wereld"
                        WHEN product_categories.category_name LIKE "Soepen%" THEN "Soepen & Sauzen"
                        WHEN product_categories.category_name LIKE "Snoep%" THEN "Snoep & Snacks"
                        WHEN product_categories.category_name LIKE "Baby%" THEN "Baby & Hygiëne"
                        ELSE product_categories.category_name
                    END as short_name
                ')
            )
            ->get();

        return view('Stocks.create', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
    
    public function store(Request $request)
    {
        // Map short name to category id in PHP
        $categoriesRaw = \DB::table('product_categories')
            ->select('id', 'category_name')
            ->get();

        $shortNameToId = [];
        foreach ($categoriesRaw as $cat) {
            $short = match (true) {
                str_starts_with($cat->category_name, 'Aardappels') => 'Groente & Fruit',
                str_starts_with($cat->category_name, 'Kaas en vleeswaren') => 'Vleeswaren',
                str_starts_with($cat->category_name, 'Zuivel') => 'Zuivel',
                str_starts_with($cat->category_name, 'Bakkerij') => 'Bakkerij',
                str_starts_with($cat->category_name, 'Frisdank') => 'Dranken',
                str_starts_with($cat->category_name, 'Pasta') => 'Pasta & Wereld',
                str_starts_with($cat->category_name, 'Soepen') => 'Soepen & Sauzen',
                str_starts_with($cat->category_name, 'Snoep') => 'Snoep & Snacks',
                str_starts_with($cat->category_name, 'Baby') => 'Baby & Hygiëne',
                default => $cat->category_name,
            };
            // Only keep the first id for each short name
            if (!isset($shortNameToId[$short])) {
                $shortNameToId[$short] = $cat->id;
            }
        }

        $category_id = $shortNameToId[$request->input('category_short_name')] ?? null;

        if (!$category_id) {
            return back()->withErrors(['category_short_name' => 'Categorie niet gevonden.']);
        }

        $productName = trim($request->input('product_name') ?? '');

        // Check if a stock with the same product name already exists (in note, first word)
        $existing = DB::table('stocks')
            ->whereRaw("TRIM(SUBSTRING_INDEX(note, ' ', 1)) = ?", [$productName])
            ->exists();

        if ($existing) {
            return back()
                ->withErrors(['product_name' => 'Er bestaat al een voorraad met deze productnaam.'])
                ->withInput()
                ->with('custom_error', 'Er bestaat al een voorraad met deze productnaam.');
        }

        $note = trim($productName . ' ' . ($request->input('note') ?? ''));

        $quantityInStock = max(0, (int) $request->input('quantity_in_stock'));
        $quantityDelivered = $request->input('quantity_delivered') !== null ? (int) $request->input('quantity_delivered') : 0;
        $quantitySupplied = $request->input('quantity_supplied') !== null ? (int) $request->input('quantity_supplied') : 0;

        // Validate delivered_date is not before received_date
        $receivedDate = $request->input('received_date');
        $deliveredDate = $request->input('delivered_date');
        if ($deliveredDate && $receivedDate && $deliveredDate < $receivedDate) {
            return back()
                ->withErrors(['delivered_date' => 'De leverdatum mag niet voor de ontvangstdatum liggen.'])
                ->withInput()
                ->with('custom_error', 'De leverdatum mag niet voor de ontvangstdatum liggen.');
        }

        DB::statement('CALL create_stocks(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $category_id,
            $request->input('is_active'),
            $note,
            $receivedDate,
            $deliveredDate,
            $request->input('unit'),
            $quantityInStock,
            $quantityDelivered,
            $quantitySupplied,
        ]);
        return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
    }

    public function updateQuantities(Request $request, $id)
    {
        $request->validate([
            'quantity_in_stock' => 'required|integer|min:0',
            'quantity_delivered' => 'nullable|integer|min:0',
            'quantity_supplied' => 'nullable|integer|min:0',
        ]);

        // Get the current stock
        $stock = DB::table('stocks')->where('id', $id)->first();
        if (!$stock) {
            return back()->withErrors(['stock' => 'Stock not found.']);
        }

        $newDelivered = $request->input('quantity_delivered') !== null ? (int) $request->input('quantity_delivered') : 0;
        $newSupplied = $request->input('quantity_supplied') !== null ? (int) $request->input('quantity_supplied') : 0;

        // Only update if delivered or supplied is different
        if (
            $newDelivered == 0 &&
            $newSupplied == 0
        ) {
            return back()->with('custom_error', 'No changes detected. Nothing was updated.');
        }

        // Calculate the difference (delta) for delivered and supplied
        $deltaDelivered = $newDelivered;
        $deltaSupplied = $newSupplied;

        // Prevent subtracting more than available in stock
        if ($deltaSupplied > ($stock->quantity_in_stock + $deltaDelivered)) {
            return back()
                ->withErrors(['quantity_supplied' => 'Uitdelen niet mogelijk: onvoldoende voorraad beschikbaar'])
                ->withInput()
                ->with('custom_error', 'Uitdelen niet mogelijk: onvoldoende voorraad beschikbaar');
        }

        // Update stock: add delivered, subtract supplied
        $quantityInStock = max(0, (int) $stock->quantity_in_stock + $deltaDelivered - $deltaSupplied);

        // Save the last entered values for delivered and supplied
        DB::statement('CALL update_stocks(?, ?, ?, ?)', [
            $id,
            $quantityInStock,
            $newDelivered,
            $newSupplied,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock quantities updated successfully.');
    }

    public function edit($id)
    {
        $stock = DB::table('stocks')->where('id', $id)->first();
        if (!$stock) {
            abort(404);
        }
        return view('Stocks.update', compact('stock'));
    }
}
