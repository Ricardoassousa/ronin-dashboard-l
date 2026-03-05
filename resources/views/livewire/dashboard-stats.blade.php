<div>

    <!-- Dashboard vertical spacing -->
    <div class="py-6">

        <!-- Responsive container -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Responsive grid layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Widgets -->
                <div class="space-y-4">

                    <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-6 shadow rounded flex flex-col items-center">
                        <p class="text-gray-200 text-sm font-semibold">Total Users</p>
                        <p class="text-3xl font-bold">{{ $stats['users'] }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-700 text-white p-6 shadow rounded flex flex-col items-center">
                        <p class="text-gray-200 text-sm font-semibold">Total Orders</p>
                        <p class="text-3xl font-bold">{{ $stats['orders'] ?? 'N/A' }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white p-6 shadow rounded flex flex-col items-center">
                        <p class="text-gray-200 text-sm font-semibold">Total Revenue</p>
                        <p class="text-3xl font-bold">{{ $stats['revenue'] ?? 'N/A' }}</p>
                    </div>

                </div>

                <!-- Charts + Activity -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white p-6 shadow rounded">
                        <h3 class="text-gray-700 text-lg font-semibold mb-4">Orders Last 7 Days</h3>
                        <canvas id="ordersChart" height="200"></canvas>
                    </div>

                    <div class="bg-white p-6 shadow rounded h-64 overflow-y-auto">
                        <h3 class="text-gray-700 text-lg font-semibold mb-3">Recent Activity</h3>

                        @forelse($recentActivities as $activity)
                            <div class="flex justify-between items-center py-1 border-b">

                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $activity->description }}</span>
                                    <small class="text-gray-400">
                                        {{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <span class="px-2 py-1 text-xs font-semibold rounded text-white
                                    @if($activity->type === 'order') bg-blue-500
                                    @elseif($activity->type === 'customer') bg-green-500
                                    @elseif($activity->type === 'product') bg-purple-500
                                    @else bg-gray-400 @endif">
                                    {{ ucfirst($activity->type) }}
                                </span>

                            </div>
                        @empty
                            <p class="text-gray-400">No recent activity.</p>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Chart.js library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chart;

    // Function that renders the chart
    function renderChart(labels, data) {

        const ctx = document.getElementById('ordersChart').getContext('2d');

        // Destroy existing chart before rendering a new one
        if (chart) chart.destroy();

        chart = new Chart(ctx, {

            // Line chart type
            type: 'line',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Orders',

                    data: data,

                    borderColor: 'rgba(54,162,235,1)',
                    backgroundColor: 'rgba(54,162,235,0.2)',

                    borderWidth: 2,
                    tension: 0.3,

                    // Fill area below line
                    fill: true
                }]
            },

            options: {

                // Enables responsive behaviour
                responsive: true,

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Render chart with current data
    renderChart(@json(array_keys($ordersPerDay ?? [])), @json(array_values($ordersPerDay ?? [])));

    // Livewire event to refresh chart data dynamically
    window.addEventListener('refreshChart', event => {
        renderChart(event.detail.labels, event.detail.values);
    });

</script>