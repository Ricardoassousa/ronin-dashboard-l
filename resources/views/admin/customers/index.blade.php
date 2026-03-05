@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Customers</h1>
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

    <!-- Filter -->
    <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2">
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
        <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 bg-gray-100 p-3 rounded-lg shadow-sm">
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

            <!-- Desktop Table -->
            <table class="min-w-full table-auto hidden md:table">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 w-10">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-500">
                        </th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Registered At</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">
                            <input type="checkbox" name="selected[]" value="{{ $customer->id }}" class="row-checkbox rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-500">
                        </td>
                        <td class="p-3 font-medium">{{ $customer->first_name }}</td>
                        <td class="p-3">{{ $customer->email }}</td>
                        <td class="p-3">
                            @if($customer->is_blocked)
                                <span class="px-2 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">Blocked</span>
                            @else
                                <span class="px-2 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">Active</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $customer->created_at->format('Y-m-d') }}</td>
                        <td class="p-3 flex gap-2">

                            <!-- Desktop Buttons -->
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 px-2 py-1 border rounded hidden md:inline-flex">View</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-yellow-600 px-2 py-1 border rounded hidden md:inline-flex">Edit</a>

                            <!-- Block / Unblock -->
                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('block-{{ $customer->id }}').submit();"
                               class="{{ $customer->is_blocked ? 'text-green-600' : 'text-red-600' }} px-2 py-1 border rounded hidden md:inline-flex">
                               {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                            </a>

                            <!-- Mobile Icons -->
                            <div class="flex md:hidden gap-3">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 material-icons" title="View">visibility</a>
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="text-yellow-600 material-icons" title="Edit">edit</a>
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('block-{{ $customer->id }}').submit();"
                                   class="{{ $customer->is_blocked ? 'text-green-600' : 'text-red-600' }} material-icons" title="{{ $customer->is_blocked ? 'Unblock' : 'Block' }}">
                                   {{ $customer->is_blocked ? 'toggle_on' : 'block' }}
                                </a>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @foreach($customers as $customer)
                <div class="bg-gray-50 p-4 rounded shadow">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold">{{ $customer->first_name }}</span>
                        <span class="{{ $customer->is_blocked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} px-2 py-1 rounded text-sm">
                            {{ $customer->is_blocked ? 'Blocked' : 'Active' }}
                        </span>
                    </div>
                    <div><span class="font-semibold">Email: </span>{{ $customer->email }}</div>
                    <div><span class="font-semibold">Registered: </span>{{ $customer->created_at->format('Y-m-d') }}</div>
                    <div class="flex gap-3 mt-2">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 material-icons" title="View">visibility</a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="text-yellow-600 material-icons" title="Edit">edit</a>
                        <a href="#"
                           onclick="event.preventDefault(); document.getElementById('block-{{ $customer->id }}').submit();"
                           class="{{ $customer->is_blocked ? 'text-green-600' : 'text-red-600' }} material-icons" title="{{ $customer->is_blocked ? 'Unblock' : 'Block' }}">
                           {{ $customer->is_blocked ? 'toggle_on' : 'block' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </form>

    <!-- Hidden Forms for Block/Unblock -->
    @foreach($customers as $customer)
        <form id="block-{{ $customer->id }}" action="{{ route('admin.customers.block', $customer) }}" method="POST" class="hidden">
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
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>

@endsection