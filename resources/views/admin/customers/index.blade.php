@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Customers</h1>
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text"
               name="name"
               placeholder="Search by name..."
               value="{{ request('name') }}"
               class="border p-2 rounded flex-1">

        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
    </form>

    <!-- Customers Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Registered At</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">
                        {{ $customer->name }}
                    </td>

                    <td class="p-3">
                        {{ $customer->email }}
                    </td>

                    <td class="p-3">
                        @if($customer->is_blocked)
                            <span class="px-2 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">
                                Blocked
                            </span>
                        @else
                            <span class="px-2 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">
                                Active
                            </span>
                        @endif
                    </td>

                    <td class="p-3">
                        {{ $customer->created_at->format('Y-m-d') }}
                    </td>

                    <td class="p-3 space-x-3">

                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-blue-600 hover:underline">
                            View
                        </a>

                        <a href="{{ route('admin.customers.edit', $customer) }}"
                           class="text-yellow-600 hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('admin.customers.block', $customer) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="{{ $customer->is_blocked ? 'text-green-600' : 'text-red-600' }} hover:underline">
                                {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-gray-400 p-4 text-center">
                        No customers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>

</div>
@endsection