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

    public static function getByIdFromSP($id)
    {
        $result = DB::select('CALL spGetCustomerById(?)', [$id]);
        return $result[0] ?? null;
    }

    public static function updateFromSP($id, $data)
    {
        // Pas de volgorde en parameters aan op je SP!
        DB::statement('CALL spEditCustomer(?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $data['full_name'],
            $data['family_name'],
            $data['full_address'],
            $data['mobile'],
            $data['email'],
            $data['age'],
            $data['wish'] ?? null,
        ]);
    }
}
