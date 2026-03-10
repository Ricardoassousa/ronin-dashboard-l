<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate as an admin user (role = admin).
     *
     * @return User
     */
    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'role' => 'admin', // Admin role
        ]);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Test that the index page displays a list of customers.
     *
     * @return void
     */
    public function test_index_displays_customers(): void
    {
        $this->actingAsAdmin();

        Customer::factory()->count(3)->create();

        $response = $this->get(route('admin.customers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.index');
        $response->assertViewHas('customers');
    }

    /**
     * Test that the show page displays a specific customer.
     *
     * @return void
     */
    public function test_show_displays_customer(): void
    {
        $this->actingAsAdmin();

        $customer = Customer::factory()->create();

        $response = $this->get(route('admin.customers.show', $customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.show');
        $response->assertViewHas('customer', $customer);
    }

    /**
     * Test that the edit page displays the form for a customer.
     *
     * @return void
     */
    public function test_edit_displays_customer_form(): void
    {
        $this->actingAsAdmin();

        $customer = Customer::factory()->create();

        $response = $this->get(route('admin.customers.edit', $customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.edit');
        $response->assertViewHas('customer', $customer);
    }

    /**
     * Test that updating a customer works correctly.
     *
     * @return void
     */
    public function test_update_modifies_customer(): void
    {
        $this->actingAsAdmin();

        $customer = Customer::factory()->create([
            'first_name' => 'OldFirst',
            'last_name' => 'OldLast',
            'email' => 'old@example.com',
            'phone' => '12345',
        ]);

        $response = $this->put(route('admin.customers.update', $customer), [
            'first_name' => 'NewFirst',
            'last_name' => 'NewLast',
            'email' => 'new@example.com',
            'phone' => '67890',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('success', 'Customer updated successfully.');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'NewFirst',
            'last_name' => 'NewLast',
            'email' => 'new@example.com',
            'phone' => '67890',
        ]);
    }

    /**
     * Test that toggling block status works correctly.
     *
     * @return void
     */
    public function test_toggle_block_updates_status(): void
    {
        // Authenticate as admin
        $this->actingAsAdmin();

        // Create a customer initially unblocked
        $customer = Customer::factory()->create(['is_blocked' => false]);

        // Use PATCH instead of PUT, and correct route name
        $response = $this->patch(route('admin.customers.block', $customer));

        // Assert redirect and success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Customer status updated successfully.');

        // Assert database has updated value
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'is_blocked' => true,
        ]);
    }

    /**
     * Test that bulk actions (block/unblock) work correctly.
     *
     * @return void
     */
    public function test_bulk_actions_block_and_unblock(): void
    {
        $this->actingAsAdmin();

        $customers = Customer::factory()->count(2)->create(['is_blocked' => false]);

        // Bulk block
        $response = $this->post(route('admin.customers.bulk'), [
            'selected' => $customers->pluck('id')->toArray(),
            'bulk_action' => 'block',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Bulk action applied successfully.');

        foreach ($customers as $customer) {
            $this->assertDatabaseHas('customers', [
                'id' => $customer->id,
                'is_blocked' => true,
            ]);
        }

        // Bulk unblock
        $response = $this->post(route('admin.customers.bulk'), [
            'selected' => $customers->pluck('id')->toArray(),
            'bulk_action' => 'unblock',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Bulk action applied successfully.');

        foreach ($customers as $customer) {
            $this->assertDatabaseHas('customers', [
                'id' => $customer->id,
                'is_blocked' => false,
            ]);
        }
    }

    /**
     * Test that bulk action fails if no customers are selected.
     *
     * @return void
     */
    public function test_bulk_action_fails_without_selection(): void
    {
        // Authenticate as admin
        $this->actingAsAdmin();

        // Make POST request to the correct route with no selected customers
        $response = $this->post(route('admin.customers.bulk'), [
            // 'selected' => [], // optional, can be omitted
            'bulk_action' => 'block',
        ]);

        // Assert redirect and error message
        $response->assertRedirect();
        $response->assertSessionHas('error', 'No customers selected.');
    }

    /**
     * Test that bulk action fails with invalid action.
     *
     * @return void
     */
    public function test_bulk_action_fails_with_invalid_action(): void
    {
        $this->actingAsAdmin();

        $customers = Customer::factory()->count(2)->create();

        $response = $this->post(route('admin.customers.bulk'), [
            'selected' => $customers->pluck('id')->toArray(),
            'bulk_action' => 'invalid_action',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Invalid action.');
    }
}