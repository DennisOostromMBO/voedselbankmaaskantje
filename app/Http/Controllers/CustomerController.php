<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::select('CALL spGetAllCustomers()');
        // Haal alle klant-ids met voedselpakket op
        $idsWithParcel = DB::table('food_parcels')->pluck('customer_id')->toArray();
        // Voeg property toe aan elk customer object
        foreach ($customers as $customer) {
            $customer->has_food_parcel = in_array($customer->id, $idsWithParcel);
        }
        return view('customers.index', compact('customers'));
    }

    public function destroy($id)
    {
        DB::statement('CALL spDeleteCustomer(?)', [$id]);
        return redirect()->route('customers.index')->with('success', 'Klant verwijderd.');
    }
}