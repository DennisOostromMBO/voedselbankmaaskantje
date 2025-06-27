<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    /**
     * Get all suppliers with contact info using the stored procedure.
     */
    public static function getAllWithContacts()
    {
        return DB::select('CALL get_all_suppliers()');
    }
}
