<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\StoreSupplierRequest;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        try {
            $allSuppliers = Supplier::getAllWithContacts();
            if (!$allSuppliers || count($allSuppliers) === 0) {
                $allSuppliers = [];
            }
        } catch (\Throwable $e) {
            $allSuppliers = [];
        }

        // Search logic
        $search = $request->input('search');
        $status = $request->input('status');
        if ($search) {
            $allSuppliers = array_filter($allSuppliers, function ($supplier) use ($search) {
                $search = mb_strtolower($search);
                return (
                    (isset($supplier->supplier_name) && mb_stripos($supplier->supplier_name, $search) !== false) ||
                    (isset($supplier->email) && mb_stripos($supplier->email, $search) !== false) ||
                    (isset($supplier->contact_number) && mb_stripos($supplier->contact_number, $search) !== false)
                );
            });
            $allSuppliers = array_values($allSuppliers);
        }
        if ($status !== null && $status !== '') {
            $allSuppliers = array_filter($allSuppliers, function ($supplier) use ($status) {
                return isset($supplier->is_active) && (string)$supplier->is_active === $status;
            });
            $allSuppliers = array_values($allSuppliers);
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

    public function store(StoreSupplierRequest $request)
    {
        $validated = $request->validated();

        // Ensure upcoming_delivery_at is formatted as Y-m-d H:i:s or null
        $validated['upcoming_delivery_at'] = $request->input('upcoming_delivery_at')
            ? date('Y-m-d H:i:s', strtotime($request->input('upcoming_delivery_at')))
            : null;

        Supplier::createFromSP($validated);

        return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol toegevoegd.');
    }

    public function edit($id)
    {
        $allSuppliers = Supplier::getAllWithContacts();
        $supplier = collect($allSuppliers)->firstWhere('id', $id);

        if (!$supplier) {
            abort(404);
        }

        // If there is a next delivery, do not allow editing
        if (!empty($supplier->upcoming_delivery_at)) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Deze leverancier kan niet worden bewerkt omdat er een volgende levering gepland staat. Verwijder of wijzig eerst de geplande levering.');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, $id)
    {
        $allSuppliers = Supplier::getAllWithContacts();
        $supplier = collect($allSuppliers)->firstWhere('id', $id);

        // If there is a next delivery, do not allow updating
        if (!empty($supplier->upcoming_delivery_at)) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Deze leverancier kan niet worden bijgewerkt omdat er een volgende levering gepland staat. Verwijder of wijzig eerst de geplande levering.');
        }

        $validated = $request->validated();

        // Ensure upcoming_delivery_at is formatted as Y-m-d H:i:s or null
        $validated['upcoming_delivery_at'] = $request->input('upcoming_delivery_at')
            ? date('Y-m-d H:i:s', strtotime($request->input('upcoming_delivery_at')))
            : null;

        Supplier::updateFromSP($id, $validated);

        return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol bijgewerkt.');
    }

    public function destroy($id)
    {
        $result = null;
        Supplier::deleteFromSP($id, $result);

        if ($result === 'success') {
            return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol verwijderd.');
        } else {
            return redirect()->route('suppliers.index')->with('error', 'Kan leverancier niet verwijderen: nog actief in lopende levering.');
        }
    }
}



