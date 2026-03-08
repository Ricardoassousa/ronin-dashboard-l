@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <!-- Header -->
    <h1 class="text-2xl font-bold mb-6">Create Product</h1>

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

    <!-- Form -->
    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label class="block font-medium text-gray-700 mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block font-medium text-gray-700 mb-1" for="description">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Price & Stock -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1" for="price">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1" for="stock">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Category -->
        <div>
            <label class="block font-medium text-gray-700 mb-1" for="category_id">Category</label>
            <select name="category_id" id="category_id"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 mt-4">
            <a href="{{ route('admin.products.index') }}"
               class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition text-center">
               Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Create
            </button>
        </div>

    </form>
</div>
@endsection