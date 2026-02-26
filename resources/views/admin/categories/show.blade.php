@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6">Category Details</h1>

    <div class="space-y-4">
        <div>
            <h2 class="font-medium text-gray-700">Name:</h2>
            <p class="text-gray-900">{{ $category->name }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Description:</h2>
            <p class="text-gray-900">{{ $category->description ?? '-' }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Created At:</h2>
            <p class="text-gray-900">{{ $category->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <div>
            <h2 class="font-medium text-gray-700">Updated At:</h2>
            <p class="text-gray-900">{{ $category->updated_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="{{ route('admin.categories.index') }}"
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition">
           Back
        </a>
        <a href="{{ route('admin.categories.edit', $category) }}"
           class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
           Edit
        </a>
    </div>

</div>
@endsection