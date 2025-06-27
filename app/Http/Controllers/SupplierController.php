<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        try {
            $allSuppliers = Supplier::getAllWithContacts(); // returns array

            if (!$allSuppliers || count($allSuppliers) === 0) {
                $allSuppliers = [];
            }
        } catch (\Throwable $e) {
            $allSuppliers = [];
        }

        $perPage = 2;
        $currentPage = $request->input('page', 1);
        $currentItems = array_slice($allSuppliers, ($currentPage - 1) * $perPage, $perPage);

        $suppliers = new LengthAwarePaginator(
            $currentItems,
            count($allSuppliers),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('suppliers.index', compact('suppliers'));
    }

    public function show($id)
    {
        $allSuppliers = Supplier::getAllWithContacts();
        $supplier = collect($allSuppliers)->firstWhere('id', $id);

        if (!$supplier) {
            abort(404);
        }

        return view('suppliers.show', compact('supplier'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'note' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'street' => 'nullable|string|max:100',
            'house_number' => 'nullable|string|max:4',
            'addition' => 'nullable|string|max:5',
            'postcode' => 'nullable|string|max:6',
            'city' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:10',
        ]);

        \DB::statement('CALL create_supplier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $validated['supplier_name'],
            $validated['contact_number'] ?? null,
            $validated['is_active'],
            $validated['note'] ?? null,
            $validated['email'] ?? null,
            $validated['street'] ?? null,
            $validated['house_number'] ?? null,
            $validated['addition'] ?? null,
            $validated['postcode'] ?? null,
            $validated['city'] ?? null,
            $validated['mobile'] ?? null,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol toegevoegd.');
    }
}

