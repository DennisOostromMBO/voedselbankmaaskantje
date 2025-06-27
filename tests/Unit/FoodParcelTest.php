<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\FoodParcel;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

/**
 * Food Parcel Model Unit Tests
 * 
 * Tests all CRUD operations, relationships, and stored procedures
 * for the FoodParcel model with comprehensive error handling.
 * 
 * @author Wassim
 * @version 1.0
 */
class FoodParcelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test food parcel creation with valid data.
     *
     * @test
     * @return void
     */
    public function test_can_create_food_parcel_with_valid_data(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => $customer->id,
            'stock_id' => $stock->id,
            'is_active' => true,
            'note' => 'Test food parcel note'
        ];

        // Act
        $foodParcel = FoodParcel::create($data);

        // Assert
        $this->assertInstanceOf(FoodParcel::class, $foodParcel);
        $this->assertEquals($customer->id, $foodParcel->customer_id);
        $this->assertEquals($stock->id, $foodParcel->stock_id);
        $this->assertTrue($foodParcel->is_active);
        $this->assertEquals('Test food parcel note', $foodParcel->note);
        $this->assertDatabaseHas('food_parcels', $data);
    }

    /**
     * Test food parcel mass assignment protection.
     *
     * @test
     * @return void
     */
    public function test_mass_assignment_protection(): void
    {
        // Arrange
        $data = [
            'id' => 999,
            'customer_id' => 1,
            'stock_id' => 1,
            'is_active' => true,
            'note' => 'Test note',
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Act
        $foodParcel = new FoodParcel();
        $foodParcel->fill($data);

        // Assert - only fillable attributes should be set
        $this->assertNull($foodParcel->id);
        $this->assertEquals(1, $foodParcel->customer_id);
        $this->assertEquals(1, $foodParcel->stock_id);
        $this->assertTrue($foodParcel->is_active);
        $this->assertEquals('Test note', $foodParcel->note);
        $this->assertNull($foodParcel->created_at);
        $this->assertNull($foodParcel->updated_at);
    }

    /**
     * Test customer relationship.
     *
     * @test
     * @return void
     */
    public function test_belongs_to_customer_relationship(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create();
        $foodParcel = FoodParcel::factory()->create([
            'customer_id' => $customer->id,
            'stock_id' => $stock->id
        ]);

        // Act
        $relatedCustomer = $foodParcel->customer;

        // Assert
        $this->assertInstanceOf(Customer::class, $relatedCustomer);
        $this->assertEquals($customer->id, $relatedCustomer->id);
    }

    /**
     * Test stock relationship.
     *
     * @test
     * @return void
     */
    public function test_belongs_to_stock_relationship(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create();
        $foodParcel = FoodParcel::factory()->create([
            'customer_id' => $customer->id,
            'stock_id' => $stock->id
        ]);

        // Act
        $relatedStock = $foodParcel->stock;

        // Assert
        $this->assertInstanceOf(Stock::class, $relatedStock);
        $this->assertEquals($stock->id, $relatedStock->id);
    }

    /**
     * Test attribute casting.
     *
     * @test
     * @return void
     */
    public function test_attribute_casting(): void
    {
        // Arrange
        $foodParcel = FoodParcel::factory()->create([
            'is_active' => 1
        ]);

        // Act & Assert
        $this->assertIsBool($foodParcel->is_active);
        $this->assertTrue($foodParcel->is_active);
        
        // Test datetime casting
        $this->assertInstanceOf(\Carbon\Carbon::class, $foodParcel->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $foodParcel->updated_at);
    }

    /**
     * Test getAllWithDetails stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_get_all_with_details_stored_procedure_success(): void
    {
        // Mock successful DB response
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcels_with_details(?, ?, ?)', [null, null, null])
            ->andReturn([
                (object)[
                    'id' => 1,
                    'customer_name' => 'John Doe',
                    'stock_name' => 'Bread',
                    'is_active' => true
                ]
            ]);

        // Act
        $result = FoodParcel::getAllWithDetails();

        // Assert
        $this->assertIsObject($result);
        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result->first()->customer_name);
    }

    /**
     * Test getAllWithDetails stored procedure with filters.
     *
     * @test
     * @return void
     */
    public function test_get_all_with_details_with_filters(): void
    {
        // Arrange
        $filters = [
            'customer_id' => 1,
            'is_active' => true,
            'search' => 'bread'
        ];

        // Mock DB response
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcels_with_details(?, ?, ?)', [1, true, 'bread'])
            ->andReturn([]);

        // Act
        $result = FoodParcel::getAllWithDetails($filters);

        // Assert
        $this->assertIsObject($result);
    }

    /**
     * Test getAllWithDetails stored procedure error handling.
     *
     * @test
     * @return void
     */
    public function test_get_all_with_details_error_handling(): void
    {
        // Mock database exception
        DB::shouldReceive('select')
            ->once()
            ->andThrow(new QueryException('connection', 'CALL sp_get_food_parcels_with_details(?, ?, ?)', [], new \Exception('DB Error')));

        // Mock log
        Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Failed to get food parcels with details/'));

        // Act & Assert
        $this->expectException(QueryException::class);
        FoodParcel::getAllWithDetails();
    }

    /**
     * Test getDetailsByIdSP stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_get_details_by_id_sp_success(): void
    {
        // Mock successful DB response
        $expectedResult = (object)[
            'id' => 1,
            'customer_name' => 'John Doe',
            'stock_name' => 'Bread',
            'is_active' => true
        ];

        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_by_id(?)', [1])
            ->andReturn([$expectedResult]);

        // Act
        $result = FoodParcel::getDetailsByIdSP(1);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test getDetailsByIdSP when no record found.
     *
     * @test
     * @return void
     */
    public function test_get_details_by_id_sp_not_found(): void
    {
        // Mock empty DB response
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_by_id(?)', [999])
            ->andReturn([]);

        // Act
        $result = FoodParcel::getDetailsByIdSP(999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test createWithSP stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_create_with_sp_success(): void
    {
        // Arrange
        $data = [
            'stock_id' => 1,
            'customer_id' => 1,
            'is_active' => true,
            'note' => 'Test note'
        ];

        // Mock successful DB call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_create_food_parcel(?, ?, ?, ?)', [1, 1, true, 'Test note'])
            ->andReturn(true);

        // Act
        $result = FoodParcel::createWithSP($data);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test createWithSP with default values.
     *
     * @test
     * @return void
     */
    public function test_create_with_sp_default_values(): void
    {
        // Arrange
        $data = [
            'stock_id' => 1,
            'customer_id' => 1
        ];

        // Mock successful DB call with defaults
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_create_food_parcel(?, ?, ?, ?)', [1, 1, true, null])
            ->andReturn(true);

        // Act
        $result = FoodParcel::createWithSP($data);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test updateWithSP stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_update_with_sp_success(): void
    {
        // Arrange
        $data = [
            'stock_id' => 2,
            'customer_id' => 2,
            'is_active' => false,
            'note' => 'Updated note'
        ];

        // Mock successful DB call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_update_food_parcel(?, ?, ?, ?, ?)', [1, 2, 2, false, 'Updated note'])
            ->andReturn(true);

        // Act
        $result = FoodParcel::updateWithSP(1, $data);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test deleteWithSP stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_delete_with_sp_success(): void
    {
        // Mock successful DB call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_delete_food_parcel(?)', [1])
            ->andReturn(true);

        // Act
        $result = FoodParcel::deleteWithSP(1);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getStatistics stored procedure success.
     *
     * @test
     * @return void
     */
    public function test_get_statistics_success(): void
    {
        // Mock successful DB response
        $expectedStats = (object)[
            'total' => 100,
            'active' => 80,
            'inactive' => 20,
            'this_month' => 25
        ];

        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_stats()')
            ->andReturn([$expectedStats]);

        // Act
        $result = FoodParcel::getStatistics();

        // Assert
        $this->assertEquals($expectedStats, $result);
    }

    /**
     * Test getStatistics with empty response.
     *
     * @test
     * @return void
     */
    public function test_get_statistics_empty_response(): void
    {
        // Mock empty DB response
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_stats()')
            ->andReturn([]);

        // Act
        $result = FoodParcel::getStatistics();

        // Assert
        $this->assertEquals(0, $result->total);
        $this->assertEquals(0, $result->active);
        $this->assertEquals(0, $result->inactive);
        $this->assertEquals(0, $result->this_month);
    }

    /**
     * Test stored procedure error handling with logging.
     *
     * @test
     * @return void
     */
    public function test_stored_procedure_error_handling(): void
    {
        // Mock database exception
        DB::shouldReceive('statement')
            ->once()
            ->andThrow(new QueryException('connection', 'CALL sp_create_food_parcel(?, ?, ?, ?)', [], new \Exception('SP Error')));

        // Mock log
        Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Failed to create food parcel/'));

        // Act & Assert
        $this->expectException(QueryException::class);
        FoodParcel::createWithSP(['stock_id' => 1, 'customer_id' => 1]);
    }

    /**
     * Test food parcel deletion cascades properly.
     *
     * @test
     * @return void
     */
    public function test_food_parcel_deletion(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create();
        $foodParcel = FoodParcel::factory()->create([
            'customer_id' => $customer->id,
            'stock_id' => $stock->id
        ]);

        // Act
        $foodParcel->delete();

        // Assert
        $this->assertDatabaseMissing('food_parcels', ['id' => $foodParcel->id]);
    }

    /**
     * Test food parcel soft delete if implemented.
     *
     * @test
     * @return void
     */
    public function test_food_parcel_validation_rules(): void
    {
        // This test would check validation if implemented in the model
        // For now, we'll test the basic structure
        
        $foodParcel = new FoodParcel();
        
        // Assert model properties
        $this->assertEquals('food_parcels', $foodParcel->getTable());
        $this->assertEquals(['stock_id', 'customer_id', 'is_active', 'note'], $foodParcel->getFillable());
        $this->assertEquals(['is_active' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'], $foodParcel->getCasts());
    }
}
