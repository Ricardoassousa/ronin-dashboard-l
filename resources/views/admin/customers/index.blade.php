@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Customers</h1>
    </div>

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

    <!-- Filter -->
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text"
               name="name"
               placeholder="Search by name..."
               value="{{ request('name') }}"
               class="border border-gray-300 p-2 rounded-lg flex-1 focus:ring-2 focus:ring-gray-800">

        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
    </form>

    <!-- Bulk Form -->
    <form method="POST" action="{{ route('admin.customers.bulk') }}">
        @csrf

        <!-- Bulk Actions Bar -->
        <div class="mb-4 flex items-center gap-3 bg-gray-100 p-3 rounded-lg shadow-sm">

            <select name="bulk_action"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-800">
                <option value="">Bulk Actions</option>
                <option value="block">Block Selected</option>
                <option value="unblock">Unblock Selected</option>
            </select>

            <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Apply
            </button>

        </div>

        <!-- Customers Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 w-10">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-500">
                        </th>
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

                        <td class="p-3">
                            <input type="checkbox"
                                   name="selected[]"
                                   value="{{ $customer->id }}"
                                   class="row-checkbox rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-500">
                        </td>

                        <td class="p-3 font-medium">
                            {{ $customer->first_name }}
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

                            <!-- Block / Unblock via hidden form -->
                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('block-{{ $customer->id }}').submit();"
                               class="{{ $customer->is_blocked ? 'text-green-600' : 'text-red-600' }} hover:underline">
                                {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                            </a>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-gray-400 p-4 text-center">
                            No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </form>

    <!-- Hidden Forms (fora do bulk form) -->
    @foreach($customers as $customer)
        <form id="block-{{ $customer->id }}"
              action="{{ route('admin.customers.block', $customer) }}"
              method="POST"
              class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $customers->withQueryString()->links() }}
    </div>

</div>

<!-- Select All Script -->
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

@endsection