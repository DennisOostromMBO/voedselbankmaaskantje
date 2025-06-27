<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Use DB::select for SP, but for pagination use query builder
        $suppliers = DB::table('suppliers')
            ->leftJoin('contacts', 'contacts.supplier_id', '=', 'suppliers.id')
            ->select(
                'suppliers.id',
                'suppliers.supplier_name',
                'suppliers.contact_number',
                'suppliers.is_active',
                'suppliers.note',
                'suppliers.created_at',
                'suppliers.updated_at',
                'contacts.street',
                'contacts.postcode',
                'contacts.house_number',
                'contacts.addition',
                'contacts.city',
                'contacts.mobile',
                'contacts.email',
                'contacts.full_address',
                'contacts.is_active AS contact_is_active',
                'contacts.note AS contact_note'
            )
            ->paginate(2);

        return view('suppliers.index', compact('suppliers'));
    }

    public function show($id)
    {
        $supplier = \DB::table('suppliers')
            ->leftJoin('contacts', 'contacts.supplier_id', '=', 'suppliers.id')
            ->select(
                'suppliers.*',
                'contacts.street',
                'contacts.postcode',
                'contacts.house_number',
                'contacts.addition',
                'contacts.city',
                'contacts.mobile',
                'contacts.email',
                'contacts.full_address',
                'contacts.is_active AS contact_is_active',
                'contacts.note AS contact_note'
            )
            ->where('suppliers.id', $id)
            ->first();

        if (!$supplier) {
            abort(404);
        }

        return view('suppliers.show', compact('supplier'));
    }
}
