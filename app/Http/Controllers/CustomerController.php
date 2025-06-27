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
}