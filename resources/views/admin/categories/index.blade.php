@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header + Create Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
           Create Category
        </a>
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

    <!-- Filter Form -->
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="name" placeholder="Search by name..." value="{{ request('name') }}" 
               class="border p-2 rounded flex-1">
        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
    </form>

    <!-- Categories Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Description</th>
                    <th class="p-2 text-left">Created At</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2">{{ $category->name }}</td>
                    <td class="p-2">{{ $category->description ?? '-' }}</td>
                    <td class="p-2">{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="p-2 space-x-2">
                        <a href="{{ route('admin.categories.show', $category) }}" class="text-blue-600 hover:underline">View</a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline" onclick="return confirm('Delete category?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-gray-400 p-2 text-center">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>

</div>
@endsection