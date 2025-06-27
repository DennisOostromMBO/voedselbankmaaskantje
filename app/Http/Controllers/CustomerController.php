<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::select('CALL spGetAllCustomers()');
        return view('customers.index', compact('customers'));
    }
}