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
}

