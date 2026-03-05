@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6">Product Details</h1>

    <div class="space-y-4">
        <div>
            <h2 class="font-medium text-gray-700">Name:</h2>
            <p class="text-gray-900">{{ $product->name }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Description:</h2>
            <p class="text-gray-900">{{ $product->description ?? '-' }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Price:</h2>
            <p class="text-gray-900">${{ number_format($product->price, 2) }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Stock:</h2>
            <p class="text-gray-900">{{ $product->stock }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Category:</h2>
            <p class="text-gray-900">{{ $product->category?->name ?? '-' }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Created At:</h2>
            <p class="text-gray-900">{{ $product->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Updated At:</h2>
            <p class="text-gray-900">{{ $product->updated_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-2">
        <a href="{{ route('admin.products.index') }}"
           class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition text-center">
           Back
        </a>
        <a href="{{ route('admin.products.edit', $product) }}"
           class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition text-center">
           Edit
        </a>
    </div>

</div>
@endsection