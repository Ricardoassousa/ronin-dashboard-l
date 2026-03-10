<?php

namespace Tests\Feature;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the order index page is accessible for admin users.
     *
     * @return void
     */
    public function test_index_page_is_accessible(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('admin.orders.index'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.orders.index');
    }

    /**
     * Test that filtering orders by customer works.
     *
     * @return void
     */
    public function test_index_filters_by_customer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $customer1 = Customer::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $customer2 = Customer::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

        Order::factory()->count(2)->create(['customer_id' => $customer1->id]);
        Order::factory()->count(3)->create(['customer_id' => $customer2->id]);

        $response = $this->get(route('admin.orders.index', ['customer' => 'John']));

        $response->assertStatus(200)
                 ->assertViewHas('orders', function ($orders) use ($customer1) {
                     return $orders->every(fn($order) => $order->customer_id === $customer1->id);
                 });
    }

    /**
     * Test that filtering orders by status works.
     *
     * @return void
     */
    public function test_index_filters_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $customer = Customer::factory()->create();

        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'paid']);
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);

        $response = $this->get(route('admin.orders.index', ['status' => 'paid']));

        $response->assertStatus(200)
                 ->assertViewHas('orders', function ($orders) {
                     return $orders->every(fn($order) => $order->status === 'paid');
                 });
    }

    /**
     * Test that the show page for an order is accessible and loads related items.
     *
     * @return void
     */
    public function test_show_page_displays_order_with_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $order = Order::factory()->create(['customer_id' => $customer->id]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 50,
        ]);

        $response = $this->get(route('admin.orders.show', $order));

        $response->assertStatus(200)
                 ->assertViewIs('admin.orders.show')
                 ->assertViewHas('order', function ($o) use ($order) {
                     return $o->id === $order->id && $o->orderItems->isNotEmpty();
                 });
    }

    /**
     * Test updating the status of an order.
     *
     * @return void
     */
    public function test_update_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);

        $response = $this->patch(route('admin.orders.status', $order), [
            'status' => 'shipped',
        ]);

        $response->assertRedirect()
                 ->assertSessionHas('success', 'Order status updated successfully.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    // /**
    //  * Test that validation prevents invalid status updates.
    //  *
    //  * @return void
    //  */
    // public function test_update_status_validation_fails(): void
    // {
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     $customer = Customer::factory()->create();
    //     $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);

    //     $response = $this->patch(route('admin.orders.status', $order), [
    //         'status' => 'invalid_status',
    //     ]);

    //     $response->assertSessionHasErrors('status');

    //     $this->assertDatabaseHas('orders', [
    //         'id' => $order->id,
    //         'status' => 'pending',
    //     ]);
    // }
}