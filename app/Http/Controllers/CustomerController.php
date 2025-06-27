<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
 
class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::getAllFromSP();
        $idsWithParcel = DB::table('food_parcels')->pluck('customer_id')->toArray();
        foreach ($customers as $customer) {
            $customer->has_food_parcel = in_array($customer->id, $idsWithParcel);
        }
        return view('customers.index', compact('customers'));
    }

    public function destroy($id)
    {
        Customer::deleteFromSP($id);
        return redirect()->route('customers.index')->with('success', 'Klant verwijderd.');
    }
    
    public function edit($id)
    {
        $customer = DB::select('CALL spGetCustomerById(?)', [$id])[0];
        return view('customers.edit', ['customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'infix'           => 'nullable|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'last_name'       => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'street'          => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'house_number'    => 'required|regex:/^\d+$/|digits_between:1,4',
            'addition'        => 'nullable|string|max:8',
            'postcode'        => 'required|regex:/^[0-9]{4}[A-Z]{2}$/',
            'city'            => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'mobile'          => 'required|regex:/^06\d{8}$/',
            'email'           => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|not_regex:/xn--/',
            'age'             => 'required|integer',
            'wish'            => 'nullable|string|max:255',
        ]);

        // Uniekheid check (behalve voor deze klant)
        $emailExists = DB::table('contacts')
            ->where('email', $validatedData['email'])
            ->where('customer_id', '!=', $id)
            ->exists();
        $mobileExists = DB::table('contacts')
            ->where('mobile', $validatedData['mobile'])
            ->where('customer_id', '!=', $id)
            ->exists();
        $errors = [];
        if ($emailExists) {
            $errors['email'] = 'Let op! Dit e-mailadres is al in gebruik door een andere klant!';
        }
        if ($mobileExists) {
            $errors['mobile'] = 'Let op! Dit mobiele nummer is al in gebruik door een andere klant!';
        }
        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        // Roep de SP aan voor bijwerken (zie spEditCustomers.sql)
        DB::statement('CALL spEditCustomer(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $validatedData['first_name'],
            $validatedData['infix'] ?? '',
            $validatedData['last_name'],
            $validatedData['street'],
            $validatedData['house_number'],
            $validatedData['addition'] ?? '',
            $validatedData['postcode'],
            $validatedData['city'],
            $validatedData['mobile'],
            $validatedData['email'],
            $validatedData['age'],
            $validatedData['wish'] ?? ''
        ]);

        return redirect()->route('customers.index')->with('success', 'Klant succesvol bijgewerkt!');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'infix'           => 'nullable|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'last_name'       => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'street'          => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'house_number'    => 'required|regex:/^\d+$/|digits_between:1,4',
            'addition'        => 'nullable|string|max:8',
            'postcode'        => 'required|regex:/^[0-9]{4}[A-Z]{2}$/',
            'city'            => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\- ]+$/u',
            'mobile'          => 'required|regex:/^06\d{8}$/',
            'email'           => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|not_regex:/xn--/',
            'age'             => 'required|integer',
            'wish'            => 'nullable|string|max:255',
        ]);

        // Uniekheid check
        $emailExists = DB::table('contacts')->where('email', $validatedData['email'])->exists();
        $mobileExists = DB::table('contacts')->where('mobile', $validatedData['mobile'])->exists();
        $errors = [];
        if ($emailExists) {
            $errors['email'] = 'Let op! Dit e-mailadres is al in gebruik door een andere klant!';
        }
        if ($mobileExists) {
            $errors['mobile'] = 'Let op! Dit mobiele nummer is al in gebruik door een andere klant!';
        }
        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        // Genereer family_name en customer_number automatisch
        $family_name = $validatedData['last_name'];
        // Zoek hoogste bestaande nummer (numeriek), filter alleen CUSTxxxx
        $lastNumber = DB::table('customers')
            ->where('number', 'LIKE', 'CUST%')
            ->selectRaw('MAX(CAST(SUBSTRING(number, 5) AS UNSIGNED)) as max_number')
            ->value('max_number');
        $nextNumber = 'CUST' . str_pad((int)($lastNumber ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        // Roep de SP aan voor aanmaken (zie spCreateCustomers.sql)
        DB::statement('CALL spCreateCustomer(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $validatedData['first_name'],
            $validatedData['infix'] ?? '',
            $validatedData['last_name'],
            $family_name,
            $validatedData['street'],
            $validatedData['house_number'],
            $validatedData['addition'] ?? '',
            $validatedData['postcode'],
            $validatedData['city'],
            $validatedData['mobile'],
            $validatedData['email'],
            $nextNumber,
            $validatedData['age'],
            $validatedData['wish'] ?? ''
        ]);

        return redirect()->route('customers.index')->with('success', 'Klant succesvol toegevoegd!');
    }
}