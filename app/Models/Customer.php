<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    public static function getAllFromSP()
    {
        return DB::select('CALL spGetAllCustomers()');
    }

    public static function deleteFromSP($id)
    {
        return DB::statement('CALL spDeleteCustomer(?)', [$id]);
    }
}
