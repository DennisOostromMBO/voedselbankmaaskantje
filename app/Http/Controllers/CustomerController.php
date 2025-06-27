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
        $customer = Customer::getByIdFromSP($id);
        if (!$customer) {
            return redirect()->route('customers.index')->with('error', 'Klant niet gevonden.');
        }
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Hier zou je een SP aanroepen om te updaten, bijvoorbeeld:
        // Customer::updateFromSP($id, $request->all());
        // Voorbeeld validatie:
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'full_address' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'age' => 'required|integer',
            'customer_number' => 'required|string|max:255',
            'wish' => 'nullable|string|max:255',
        ]);
        // Roep hier je SP aan, bijvoorbeeld:
        Customer::updateFromSP($id, $validated);

        return redirect()->route('customers.index')->with('success', 'Klant bijgewerkt.');
    }
}