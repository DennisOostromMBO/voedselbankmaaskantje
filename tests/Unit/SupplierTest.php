<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_supplier()
    {
        $supplier = Supplier::create([
            'supplier_name' => 'Test Supplier',
            'contact_number' => '0612345678',
            'is_active' => true,
            'note' => 'Test note',
            'upcoming_delivery_at' => now(),
        ]);

        $this->assertDatabaseHas('suppliers', [
            'supplier_name' => 'Test Supplier',
            'contact_number' => '0612345678',
            'is_active' => true,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_all_suppliers_with_contacts_from_sp()
    {
        // Insert a supplier directly for test
        Supplier::create([
            'supplier_name' => 'SP Supplier',
            'contact_number' => '0698765432',
            'is_active' => true,
            'note' => 'SP note',
            'upcoming_delivery_at' => now(),
        ]);

        $suppliers = Supplier::getAllWithContacts();
        $this->assertIsArray($suppliers);
        $this->assertNotEmpty($suppliers);
        $this->assertTrue(collect($suppliers)->contains(function ($s) {
            return $s->supplier_name === 'SP Supplier';
        }));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_supplier()
    {
        $supplier = Supplier::create([
            'supplier_name' => 'Update Supplier',
            'contact_number' => '0612345678',
            'is_active' => true,
            'note' => 'Old note',
            'upcoming_delivery_at' => now(),
        ]);

        $supplier->update([
            'supplier_name' => 'Updated Supplier',
            'contact_number' => '0699999999',
            'is_active' => false,
            'note' => 'New note',
        ]);

        $this->assertDatabaseHas('suppliers', [
            'supplier_name' => 'Updated Supplier',
            'contact_number' => '0699999999',
            'is_active' => false,
            'note' => 'New note',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_a_supplier()
    {
        $supplier = Supplier::create([
            'supplier_name' => 'Delete Supplier',
            'contact_number' => '0612345678',
            'is_active' => true,
            'note' => 'To be deleted',
            'upcoming_delivery_at' => now(),
        ]);

        $supplier->delete();

        $this->assertDatabaseMissing('suppliers', [
            'supplier_name' => 'Delete Supplier',
            'contact_number' => '0612345678',
        ]);
    }
}
