@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Customer</h1>

        <a href="{{ route('admin.customers.index') }}"
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition">
            Back
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium text-gray-700 mb-1">
                Name
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $customer->name) }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-medium text-gray-700 mb-1">
                Email
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email', $customer->email) }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status (read-only info) -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">
                Status
            </label>

            @if($customer->is_blocked)
                <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-700 rounded">
                    Blocked
                </span>
            @else
                <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded">
                    Active
                </span>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
            <a href="{{ route('admin.customers.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition">
                Cancel
            </a>

            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Update Customer
            </button>
        </div>

    </form>

</div>
@endsection