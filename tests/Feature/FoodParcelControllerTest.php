<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FoodParcel;
use App\Models\Customer;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Food Parcel Controller Feature Tests
 * 
 * Tests all HTTP endpoints and user interactions for the food parcel
 * management system with proper authentication and authorization.
 * 
 * @author Wassim
 * @version 1.0
 */
class FoodParcelControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticated user for testing.
     */
    private User $user;

    /**
     * Setup test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test food parcels index page loads successfully.
     *
     * @test
     * @return void
     */
    public function test_index_page_loads_successfully(): void
    {
        // Mock stored procedure calls
        DB::shouldReceive('select')
            ->with('CALL sp_get_food_parcels_with_details()')
            ->andReturn([]);
            
        DB::shouldReceive('select')
            ->with('CALL sp_get_food_parcel_stats()')
            ->andReturn([(object)['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0]]);

        // Act
        $response = $this->get(route('food-parcels.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('food-parcels.index');
        $response->assertViewHas(['foodParcels', 'statistics', 'customers']);
    }

    /**
     * Test index page with search and filters.
     *
     * @test
     * @return void
     */
    public function test_index_page_with_filters(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        
        // Mock stored procedure calls with filters
        DB::shouldReceive('select')
            ->with('CALL sp_get_food_parcels_with_details(?, ?, ?)', [$customer->id, true, 'bread'])
            ->andReturn([]);
            
        DB::shouldReceive('select')
            ->with('CALL sp_get_food_parcel_stats()')
            ->andReturn([(object)['total' => 5, 'active' => 3, 'inactive' => 2, 'this_month' => 1]]);

        // Act
        $response = $this->get(route('food-parcels.index', [
            'customer_id' => $customer->id,
            'is_active' => true,
            'search' => 'bread'
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('food-parcels.index');
    }

    /**
     * Test index page handles database errors gracefully.
     *
     * @test
     * @return void
     */
    public function test_index_handles_database_errors(): void
    {
        // Mock database exception
        DB::shouldReceive('select')
            ->andThrow(new \Exception('Database connection failed'));

        // Mock logging
        Log::shouldReceive('error');

        // Act
        $response = $this->get(route('food-parcels.index'));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Test create page loads successfully.
     *
     * @test
     * @return void
     */
    public function test_create_page_loads_successfully(): void
    {
        // Arrange
        $customers = Customer::factory()->count(3)->create();
        $stocks = Stock::factory()->count(5)->create(['quantity' => 10]);

        // Act
        $response = $this->get(route('food-parcels.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('food-parcels.create');
        $response->assertViewHas(['customers', 'stocks']);
    }

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
            'note' => 'Test food parcel for integration'
        ];

        // Mock stored procedure call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_create_food_parcel(?, ?, ?, ?)', [$stock->id, $customer->id, true, 'Test food parcel for integration'])
            ->andReturn(true);

        // Act
        $response = $this->post(route('food-parcels.store'), $data);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
        $response->assertSessionHas('success', 'Food parcel created successfully!');
    }

    /**
     * Test food parcel creation with invalid data.
     *
     * @test
     * @return void
     */
    public function test_create_food_parcel_validation_errors(): void
    {
        // Act
        $response = $this->post(route('food-parcels.store'), []);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['customer_id', 'stock_id']);
    }

    /**
     * Test food parcel creation with non-existent customer.
     *
     * @test
     * @return void
     */
    public function test_create_food_parcel_with_invalid_customer(): void
    {
        // Arrange
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => 999, // Non-existent
            'stock_id' => $stock->id,
            'is_active' => true
        ];

        // Act
        $response = $this->post(route('food-parcels.store'), $data);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['customer_id']);
    }

    /**
     * Test food parcel show page.
     *
     * @test
     * @return void
     */
    public function test_show_food_parcel_success(): void
    {
        // Arrange
        $mockFoodParcel = (object)[
            'id' => 1,
            'customer_id' => 1,
            'stock_id' => 1,
            'customer_name' => 'John Doe',
            'stock_name' => 'Bread',
            'is_active' => true,
            'note' => 'Test note',
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Mock stored procedure call
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_by_id(?)', [1])
            ->andReturn([$mockFoodParcel]);

        // Act
        $response = $this->get(route('food-parcels.show', 1));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('food-parcels.show');
        $response->assertViewHas('foodParcel');
    }

    /**
     * Test show page with non-existent food parcel.
     *
     * @test
     * @return void
     */
    public function test_show_non_existent_food_parcel(): void
    {
        // Mock stored procedure returning empty result
        DB::shouldReceive('select')
            ->once()
            ->with('CALL sp_get_food_parcel_by_id(?)', [999])
            ->andReturn([]);

        // Act
        $response = $this->get(route('food-parcels.show', 999));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
        $response->assertSessionHas('error', 'Food parcel not found.');
    }

    /**
     * Test edit page loads successfully.
     *
     * @test
     * @return void
     */
    public function test_edit_page_loads_successfully(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        $foodParcel = FoodParcel::factory()->create([
            'customer_id' => $customer->id,
            'stock_id' => $stock->id
        ]);

        // Create additional test data
        Customer::factory()->count(2)->create();
        Stock::factory()->count(3)->create(['quantity' => 5]);

        // Act
        $response = $this->get(route('food-parcels.edit', $foodParcel->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('food-parcels.edit');
        $response->assertViewHas(['foodParcel', 'customers', 'stocks']);
    }

    /**
     * Test food parcel update with valid data.
     *
     * @test
     * @return void
     */
    public function test_can_update_food_parcel_with_valid_data(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        $foodParcel = FoodParcel::factory()->create([
            'customer_id' => $customer->id,
            'stock_id' => $stock->id
        ]);

        $newCustomer = Customer::factory()->create();
        $newStock = Stock::factory()->create(['quantity' => 15]);
        
        $updateData = [
            'customer_id' => $newCustomer->id,
            'stock_id' => $newStock->id,
            'is_active' => false,
            'note' => 'Updated test note'
        ];

        // Mock stored procedure call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_update_food_parcel(?, ?, ?, ?, ?)', [
                $foodParcel->id, 
                $newStock->id, 
                $newCustomer->id, 
                false, 
                'Updated test note'
            ])
            ->andReturn(true);

        // Act
        $response = $this->put(route('food-parcels.update', $foodParcel->id), $updateData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
        $response->assertSessionHas('success', 'Food parcel updated successfully!');
    }

    /**
     * Test food parcel update with invalid data.
     *
     * @test
     * @return void
     */
    public function test_update_food_parcel_validation_errors(): void
    {
        // Arrange
        $foodParcel = FoodParcel::factory()->create();

        // Act
        $response = $this->put(route('food-parcels.update', $foodParcel->id), [
            'customer_id' => '', // Invalid
            'stock_id' => 'invalid' // Invalid
        ]);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['customer_id', 'stock_id']);
    }

    /**
     * Test food parcel deletion.
     *
     * @test
     * @return void
     */
    public function test_can_delete_food_parcel(): void
    {
        // Arrange
        $foodParcel = FoodParcel::factory()->create();

        // Mock stored procedure call
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_delete_food_parcel(?)', [$foodParcel->id])
            ->andReturn(true);

        // Act
        $response = $this->delete(route('food-parcels.destroy', $foodParcel->id));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
        $response->assertSessionHas('success', 'Food parcel deleted successfully!');
    }

    /**
     * Test food parcel deletion with database error.
     *
     * @test
     * @return void
     */
    public function test_delete_food_parcel_database_error(): void
    {
        // Arrange
        $foodParcel = FoodParcel::factory()->create();

        // Mock stored procedure throwing exception
        DB::shouldReceive('statement')
            ->once()
            ->andThrow(new \Exception('Foreign key constraint violation'));

        // Mock logging
        Log::shouldReceive('error');

        // Act
        $response = $this->delete(route('food-parcels.destroy', $foodParcel->id));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
        $response->assertSessionHas('error', 'Failed to delete food parcel. Please try again.');
    }

    /**
     * Test food parcel creation with database error.
     *
     * @test
     * @return void
     */
    public function test_create_food_parcel_database_error(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => $customer->id,
            'stock_id' => $stock->id,
            'is_active' => true,
            'note' => 'Test note'
        ];

        // Mock stored procedure throwing exception
        DB::shouldReceive('statement')
            ->once()
            ->andThrow(new \Exception('Database error'));

        // Mock logging
        Log::shouldReceive('error');

        // Act
        $response = $this->post(route('food-parcels.store'), $data);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Failed to create food parcel. Please try again.');
    }

    /**
     * Test food parcel note length validation.
     *
     * @test
     * @return void
     */
    public function test_note_length_validation(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => $customer->id,
            'stock_id' => $stock->id,
            'is_active' => true,
            'note' => str_repeat('a', 1001) // Exceeds 1000 character limit
        ];

        // Act
        $response = $this->post(route('food-parcels.store'), $data);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['note']);
    }

    /**
     * Test food parcel boolean casting for is_active.
     *
     * @test
     * @return void
     */
    public function test_boolean_casting_for_is_active(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => $customer->id,
            'stock_id' => $stock->id,
            'is_active' => '1', // String representation
            'note' => 'Test boolean casting'
        ];

        // Mock stored procedure call - should receive boolean true
        DB::shouldReceive('statement')
            ->once()
            ->with('CALL sp_create_food_parcel(?, ?, ?, ?)', [$stock->id, $customer->id, true, 'Test boolean casting'])
            ->andReturn(true);

        // Act
        $response = $this->post(route('food-parcels.store'), $data);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('food-parcels.index'));
    }

    /**
     * Test unauthorized access to food parcels (if auth middleware is applied).
     *
     * @test
     * @return void
     */
    public function test_unauthorized_access(): void
    {
        // Logout the user
        Auth::logout();

        // Act
        $response = $this->get(route('food-parcels.index'));

        // Assert - This will depend on your auth middleware setup
        // Assuming redirect to login page
        $response->assertStatus(302);
    }

    /**
     * Test CSRF protection on store request.
     *
     * @test
     * @return void
     */
    public function test_csrf_protection_on_store(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $data = [
            'customer_id' => $customer->id,
            'stock_id' => $stock->id,
            'is_active' => true
        ];

        // Act - Make request without CSRF token
        $response = $this->withSession(['_token' => 'wrong-token'])
                         ->post(route('food-parcels.store'), $data);

        // This test verifies CSRF protection exists
        // In Laravel, CSRF failures typically return 419
        $response->assertStatus(419);
    }

    /**
     * Test that proper HTTP methods are required.
     *
     * @test
     * @return void
     */
    public function test_http_method_requirements(): void
    {
        // Test that GET request to store endpoint returns method not allowed
        $response = $this->get(route('food-parcels.store'));
        $response->assertStatus(405); // Method Not Allowed

        // Test that GET request to destroy endpoint returns method not allowed
        $response = $this->get(route('food-parcels.destroy', 1));
        $response->assertStatus(405); // Method Not Allowed
    }
}
