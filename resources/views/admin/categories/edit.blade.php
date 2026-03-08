@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <!-- Header -->
    <h1 class="text-2xl font-bold mb-6">Edit Category</h1>

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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 mt-4">
            <a href="{{ route('admin.categories.index') }}"
               class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition text-center">
               Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Update Category
            </button>
        </div>
    </form>

</div>
@endsection