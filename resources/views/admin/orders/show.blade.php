@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}"
           class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition text-center">
            Back
        </a>
    </div>

    <!-- Order + Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- Order Info -->
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">Order Details</h2>

            <p><strong>Status:</strong>
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
            </p>

            <p class="mt-2"><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            <p class="mt-2"><strong>Created At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <!-- Customer Info -->
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">Customer</h2>
            <p><strong>Name:</strong> {{ $order->customer->first_name ?? 'N/A' }}</p>
            <p class="mt-2"><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
        </div>

    </div>

    <!-- Update Status -->
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Update Status</h2>

        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-col sm:flex-row gap-4 sm:items-center">
            @csrf
            @method('PATCH')

            <select name="status"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit"
                    class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Update
            </button>
        </form>
    </div>

    <!-- Order Items -->
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-semibold mb-4">Order Items</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-left">Price</th>
                        <th class="p-3 text-left">Quantity</th>
                        <th class="p-3 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $item->product->name ?? 'Product removed' }}</td>
                        <td class="p-3">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3 font-medium">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-gray-400 p-4 text-center">No items found for this order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection