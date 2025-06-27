<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;

    public static function GetAllStocks()
     {
        return DB::select('CALL get_all_stocks()');
     }
     public static function createStocks()
        {
            return DB::select('CALL create_stocks()');
        }
}
