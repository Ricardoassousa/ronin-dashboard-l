@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header + Upload Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Files</h1>
        <a href="{{ route('admin.files.create') }}" 
           class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
           Upload File
        </a>
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

    <!-- Files Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Filename</th>
                    <th class="p-3 text-left">Preview</th>
                    <th class="p-3 text-left">Created At</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                <tr class="border-t hover:bg-gray-50">

                    <!-- Filename -->
                    <td class="p-3 font-medium">{{ $file->filename }}</td>

                    <!-- Preview -->
                    <td class="p-3">
                        @if(\Illuminate\Support\Str::endsWith($file->path, ['jpg','jpeg','png']))
                            <img src="{{ route('admin.files.preview', $file->id) }}"
                                 class="h-12 rounded"
                                 alt="Preview">
                        @else
                            <span class="text-gray-500">Document</span>
                        @endif
                    </td>

                    <!-- Created At -->
                    <td class="p-3">{{ $file->created_at->format('Y-m-d') }}</td>

                    <!-- Actions -->
                    <td class="p-3">
                        <!-- Desktop buttons (large screens) -->
                        <div class="hidden lg:flex gap-2">
                            <a href="{{ route('admin.files.download', $file->id) }}"
                               class="text-blue-600 hover:underline px-2 py-1 border rounded">
                               Download
                            </a>
                            <form action="{{ route('admin.files.destroy', $file) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline px-2 py-1 border rounded"
                                        onclick="return confirm('Delete file?')">
                                    Delete
                                </button>
                            </form>
                        </div>

                        <!-- Mobile icons (small/medium screens) -->
                        <div class="flex lg:hidden gap-3 justify-center">
                            <a href="{{ route('admin.files.download', $file->id) }}"
                               class="text-blue-600 material-icons" title="Download">
                               download
                            </a>
                            <form action="{{ route('admin.files.destroy', $file) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 material-icons" title="Delete"
                                        onclick="return confirm('Delete file?')">
                                    delete
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-gray-400 p-4 text-center">
                        No files found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $files->links() }}
    </div>

</div>
@endsection