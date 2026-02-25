@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Orders</h1>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full">
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
                    <td class="p-3 font-medium">
                        {{ $order->id }}
                    </td>

                    <td class="p-3">
                        {{ $order->customer->name ?? 'N/A' }}
                    </td>

                    <td class="p-3">
                        €{{ number_format($order->total, 2) }}
                    </td>

                    <td class="p-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="px-2 py-1 text-sm font-medium bg-yellow-100 text-yellow-700 rounded">
                                    Pending
                                </span>
                                @break

                            @case('paid')
                                <span class="px-2 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded">
                                    Paid
                                </span>
                                @break

                            @case('shipped')
                                <span class="px-2 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">
                                    Shipped
                                </span>
                                @break

                            @case('cancelled')
                                <span class="px-2 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">
                                    Cancelled
                                </span>
                                @break
                        @endswitch
                    </td>

                    <td class="p-3">
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>

                    <td class="p-3">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-600 hover:underline">
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
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>
@endsection