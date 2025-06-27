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
        $messages = [
            'supplier_name.required' => 'De naam van de leverancier is verplicht.',
            'supplier_name.unique' => 'Deze leverancier bestaat al.',
            'supplier_name.max' => 'De naam van de leverancier mag maximaal 255 tekens zijn.',
            'contact_number.max' => 'Het contactnummer mag maximaal 255 tekens zijn.',
            'is_active.required' => 'Geef aan of de leverancier actief is.',
            'is_active.boolean' => 'Ongeldige waarde voor actief.',
            'note.max' => 'De notitie mag niet langer zijn dan toegestaan.',
            'email.email' => 'Vul een geldig e-mailadres in.',
            'email.max' => 'Het e-mailadres mag maximaal 255 tekens zijn.',
            'street.max' => 'De straatnaam mag maximaal 100 tekens zijn.',
            'house_number.max' => 'Het huisnummer mag maximaal 4 tekens zijn.',
            'addition.max' => 'De toevoeging mag maximaal 5 tekens zijn.',
            'postcode.max' => 'De postcode mag maximaal 6 tekens zijn.',
            'city.max' => 'De plaatsnaam mag maximaal 50 tekens zijn.',
            'mobile.max' => 'Het mobiele nummer mag maximaal 10 tekens zijn.',
        ];

        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255|unique:suppliers,supplier_name',
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
        ], $messages);

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

