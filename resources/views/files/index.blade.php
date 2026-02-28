@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header + Create Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Files</h1>
        <a href="{{ route('admin.files.create') }}" 
           class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
           Upload File
        </a>
    </div>

    <!-- Files Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Filename</th>
                    <th class="p-2 text-left">Preview</th>
                    <th class="p-2 text-left">Created At</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2 font-medium">{{ $file->filename }}</td>

                    <td class="p-2">
                        @if(\Illuminate\Support\Str::endsWith($file->path, ['jpg','jpeg','png']))
                            <img src="{{ route('admin.files.preview', $file->id) }}"
                                class="h-12 rounded"
                                alt="Document">
                        ​@else
                            <span class="text-gray-500">Document</span>
                        @endif
                    </td>

                    <td class="p-2">
                        {{ $file->created_at->format('Y-m-d') }}
                    </td>

                    <td class="p-2 space-x-2">
                        <a href="{{ route('admin.files.download', $file->id) }}"
                           class="text-blue-600 hover:underline">
                           Download
                        </a>

                        <form action="{{ route('admin.files.destroy', $file) }}"
                              method="POST" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline"
                                    onclick="return confirm('Delete file?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-gray-400 p-2 text-center">
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