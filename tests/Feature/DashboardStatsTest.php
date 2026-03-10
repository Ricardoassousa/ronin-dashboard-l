<?php

namespace Tests\Feature;

use App\Http\Livewire\DashboardStats;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Livewire component renders without errors.
     *
     * @return void
     */
    public function test_component_renders_properly(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DashboardStats::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.dashboard-stats');
    }

    /**
     * Test that the statistics (customers, orders, revenue) are calculated correctly.
     *
     * @return void
     */
    public function stats_are_calculated_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create exactly 5 customers
        Customer::factory()->count(5)->create();

        // Create exactly 3 paid orders
        Order::factory()->count(3)->create([
            'status' => 'paid',
            'total_amount' => 100,
        ]);

        // Create exactly 2 cancelled orders
        Order::factory()->count(2)->create([
            'status' => 'cancelled',
            'total_amount' => 50,
        ]);

        Livewire::test(DashboardStats::class)
            ->assertSet('stats.customers', 5)
            ->assertSet('stats.orders', 3)       // Only non-cancelled orders
            ->assertSet('stats.revenue', 300);   // 3*100
    }

    /**
     * Test that the component loads the 10 most recent activities.
     *
     * @return void
     */
    public function test_recent_activities_are_loaded(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create 12 activities to ensure only 10 are loaded
        Activity::factory()->count(12)->create();

        Livewire::test(DashboardStats::class)
            ->assertSet('recentActivities', function ($activities) {
                return count($activities) === 10;
            });
    }

    /**
     * Test that ordersPerDay is populated correctly for the last 7 days.
     *
     * @return void
     */
    public function test_orders_per_day_is_populated_for_last_7_days(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create orders on specific days
        Order::factory()->create(['created_at' => now()->subDays(0), 'status' => 'paid']);
        Order::factory()->count(2)->create(['created_at' => now()->subDays(1), 'status' => 'paid']);
        Order::factory()->create(['created_at' => now()->subDays(3), 'status' => 'cancelled']); // Should be ignored

        $component = Livewire::test(DashboardStats::class);

        $ordersPerDay = $component->get('ordersPerDay');

        $today = now()->format('Y-m-d');
        $yesterday = now()->subDays(1)->format('Y-m-d');
        $threeDaysAgo = now()->subDays(3)->format('Y-m-d');

        $this->assertEquals(1, $ordersPerDay[$today]);
        $this->assertEquals(2, $ordersPerDay[$yesterday]);
        $this->assertEquals(0, $ordersPerDay[$threeDaysAgo]); // Cancelled order not counted
    }

    /**
     * Test that calling the loadData method refreshes the statistics correctly.
     *
     * @return void
     */
    public function test_calling_load_data_refreshes_stats(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Customer::factory()->count(2)->create();

        Livewire::test(DashboardStats::class)
            ->call('loadData')
            ->assertSet('stats.customers', 2);
    }
}