@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

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

    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                   class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Price -->
        <div>
            <label for="price" class="block font-medium mb-1">Price ($)</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}"
                   class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Stock -->
        <div>
            <label for="stock" class="block font-medium mb-1">Stock</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                   class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('stock') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Category -->
        <div>
            <label for="category_id" class="block font-medium mb-1">Category</label>
            <select id="category_id" name="category_id"
                class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block font-medium mb-1">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.products.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition">
               Cancel
            </a>
            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Update Product
            </button>
        </div>
    </form>

</div>
@endsection