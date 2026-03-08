<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardStats extends Component
{
    /**
     * Dashboard statistics.
     *
     * Keys:
     *  - users: total number of users
     *  - activities: total number of activities
     *  - orders: total number of orders
     *
     * @var array
     */
    public $stats = [];

    /**
     * Collection of the 10 most recent activities.
     *
     * @var Collection
     */
    public $recentActivities = [];

    /**
     * Orders per day for the last 7 days.
     *
     * Format: ['YYYY-MM-DD' => count]
     *
     * @var array
     */
    public $ordersPerDay = [];

    /**
     * Load and refresh all dashboard data.
     *
     * This method fetches the latest statistics, recent activities,
     * and orders per day. It also dispatches a browser event to
     * refresh the Chart.js charts.
     *
     * @return void
     */
    public function loadData(): void
    {
        $this->stats = [
            'customers'  => Customer::count(),
            'orders' => Order::where('status', '!=', 'cancelled')->count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        $this->recentActivities = Activity::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Fetch orders per day from the 'orders' table
        $rawOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)) // Last 7 days including today
            ->where('status', '!=', 'cancelled') // Optional: only count valid orders
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $this->ordersPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $this->ordersPerDay[$date] = $rawOrders[$date] ?? 0;
        }

        Log::info('Dashboard Livewire refreshed for user ID: ' . auth()->id());

        $this->dispatchBrowserEvent('refreshChart', [
            'labels' => array_keys($this->ordersPerDay),
            'values' => array_values($this->ordersPerDay)
        ]);
    }

    /**
     * Component mount lifecycle hook.
     *
     * Called once when the component is initialized. Loads
     * all dashboard data.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->loadData();
    }

    /**
     * Render the Livewire dashboard view.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.dashboard-stats');
    }

}