@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Customer Details
        </h1>

        <a href="{{ route('admin.customers.index') }}"
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition">
            Back
        </a>
    </div>

    <!-- Customer Info Card -->
    <div class="bg-white shadow rounded p-6 mb-6">

        <div class="flex justify-between items-start">
            <div>
                <p class="text-lg font-semibold">{{ $customer->name }}</p>
                <p class="text-gray-600 mt-1">{{ $customer->email }}</p>
                <p class="text-gray-500 mt-2">
                    Registered at: {{ $customer->created_at->format('Y-m-d H:i') }}
                </p>
            </div>

            <div class="text-right">
                @if($customer->is_blocked)
                    <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">
                        Blocked
                    </span>
                @else
                    <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">
                        Active
                    </span>
                @endif

                <form action="{{ route('admin.customers.block', $customer) }}"
                      method="POST"
                      class="mt-4">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="{{ $customer->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}
                                   text-white px-4 py-2 rounded shadow transition">
                        {{ $customer->is_blocked ? 'Unblock Customer' : 'Block Customer' }}
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Customer Orders -->
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-semibold mb-4">Orders</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Created At</th>
                        <th class="p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $order->id }}</td>
                        <td class="p-3">€{{ number_format($order->total, 2) }}</td>

                        <td class="p-3">
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-2 py-1 text-sm bg-yellow-100 text-yellow-700 rounded">
                                        Pending
                                    </span>
                                    @break
                                @case('paid')
                                    <span class="px-2 py-1 text-sm bg-blue-100 text-blue-700 rounded">
                                        Paid
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 py-1 text-sm bg-green-100 text-green-700 rounded">
                                        Shipped
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 text-sm bg-red-100 text-red-700 rounded">
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
                        <td colspan="5" class="text-gray-400 p-4 text-center">
                            No orders found for this customer.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection