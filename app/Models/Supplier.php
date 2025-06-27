<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_name',
        'contact_number',
        'is_active',
        'note',
        'upcoming_delivery_at',
    ];

    /**
     * Get all suppliers with contact info using the stored procedure.
     */
    public static function getAllWithContacts()
    {
        return DB::select('CALL get_all_suppliers()');
    }

    /**
     * Create a new supplier using the stored procedure.
     */
    public static function createFromSP($data)
    {
        return DB::statement('CALL create_supplier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['supplier_name'],
            $data['contact_number'],
            $data['is_active'],
            $data['note'],
            $data['email'],
            $data['street'],
            $data['house_number'],
            $data['addition'],
            $data['postcode'],
            $data['city'],
            $data['mobile'],
            $data['upcoming_delivery_at'] ?? null
        ]);
    }

    /**
     * Update a supplier using the stored procedure.
     */
    public static function updateFromSP($id, $data)
    {
        return DB::statement('CALL update_supplier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $data['supplier_name'],
            $data['contact_number'],
            $data['is_active'],
            $data['note'],
            $data['email'],
            $data['street'],
            $data['house_number'],
            $data['addition'],
            $data['postcode'],
            $data['city'],
            $data['mobile'],
            $data['upcoming_delivery_at'] ?? null
        ]);
    }

    /**
     * Delete a supplier using the stored procedure.
     */
    public static function deleteFromSP($id, &$result)
    {
        // Use a raw PDO statement to get the OUT parameter
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("CALL delete_supplier(?, @result)");
        $stmt->execute([$id]);
        $result = $pdo->query("SELECT @result AS result")->fetch(\PDO::FETCH_OBJ)->result;
        return $result;
    }
}
