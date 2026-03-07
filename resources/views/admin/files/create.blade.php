@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <!-- Header -->
    <h1 class="text-2xl font-bold mb-6">Upload File</h1>

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

    <!-- Upload Form -->
    <form action="{{ route('admin.files.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-4">
        @csrf

        <!-- File Input -->
        <div>
            <label class="block font-medium text-gray-700 mb-1" for="file">Select File</label>
            <input type="file" name="file" id="file" required
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 mt-4">
            <a href="{{ route('admin.files.index') }}"
               class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded shadow transition text-center">
               Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded shadow transition">
                Upload
            </button>
        </div>

    </form>
</div>
@endsection