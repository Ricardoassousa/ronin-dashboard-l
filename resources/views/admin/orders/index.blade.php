@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Orders</h1>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
        <input type="text" name="customer" placeholder="Search by customer..." value="{{ request('customer') }}"
               class="border p-2 rounded-lg flex-1 focus:ring-2 focus:ring-gray-800">

        <select name="status" class="border p-2 rounded-lg focus:ring-2 focus:ring-gray-800">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
        <a href="{{ route('admin.orders.index') }}"
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded-lg shadow transition">
           Reset
        </a>
    </form>

    <!-- Orders Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">

        <!-- Desktop Table -->
        <table class="min-w-full table-auto hidden md:table">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">#</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Created At</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $order->id }}</td>
                    <td class="p-3">
                        @if($order->customer)
                            {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="p-3">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="p-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="px-2 py-1 text-sm font-medium bg-yellow-100 text-yellow-700 rounded">Pending</span>
                                @break
                            @case('paid')
                                <span class="px-2 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded">Paid</span>
                                @break
                            @case('shipped')
                                <span class="px-2 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">Shipped</span>
                                @break
                            @case('cancelled')
                                <span class="px-2 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">Cancelled</span>
                                @break
                        @endswitch
                    </td>
                    <td class="p-3">{{ $order->created_at->format('Y-m-d') }}</td>
                    <td class="p-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline hidden md:inline-flex px-2 py-1 border rounded">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-gray-400 p-4 text-center">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @foreach($orders as $order)
            <div class="bg-gray-50 p-4 rounded shadow">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold">#{{ $order->id }}</span>
                    <span class="@switch($order->status)
                                    @case('pending') bg-yellow-100 text-yellow-700
                                    @break
                                    @case('paid') bg-blue-100 text-blue-700
                                    @break
                                    @case('shipped') bg-green-100 text-green-700
                                    @break
                                    @case('cancelled') bg-red-100 text-red-700
                                    @break
                                  @endswitch px-2 py-1 text-sm rounded font-medium">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div><span class="font-semibold">Customer:</span> {{ $order->customer->first_name ?? 'N/A' }}</div>
                <div><span class="font-semibold">Total:</span> ${{ number_format($order->total_amount, 2) }}</div>
                <div><span class="font-semibold">Created:</span> {{ $order->created_at->format('Y-m-d') }}</div>
                <div class="flex gap-3 mt-2">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 material-icons" title="View">visibility</a>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>

</div>
@endsection