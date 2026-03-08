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

                            <!-- Hidden delete form -->
                            <form id="delete-form-{{ $file->id }}" action="{{ route('admin.files.destroy', $file) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <button class="text-red-600 hover:underline px-2 py-1 border rounded"
                                    type="button"
                                    onclick="openDeleteModal({{ $file->id }}, '{{ $file->filename }}')">
                                Delete
                            </button>
                        </div>

                        <!-- Mobile icons (small/medium screens) -->
                        <div class="flex lg:hidden gap-3 justify-center">
                            <a href="{{ route('admin.files.download', $file->id) }}"
                               class="text-blue-600 material-icons" title="Download">
                               download
                            </a>

                            <!-- Hidden delete form -->
                            <form id="delete-form-{{ $file->id }}" action="{{ route('admin.files.destroy', $file) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <button class="text-red-600 material-icons"
                                    type="button"
                                    title="Delete"
                                    onclick="openDeleteModal({{ $file->id }}, '{{ $file->filename }}')">
                                delete
                            </button>
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

    <!-- Include Delete Modal Partial -->
    @include('admin.partials.delete-modal', ['entityName' => 'File'])

</div>

<!-- JavaScript: Delete Modal -->
<script>
let deleteFormId = null;

function openDeleteModal(fileId, fileName) {
    deleteFormId = 'delete-form-' + fileId;
    const modal = document.getElementById('deleteModal');

    // Show modal
    modal.classList.remove('hidden');

    // Set file name in modal
    document.getElementById('deleteEntityName').innerText = fileName;

    // Cancel button closes modal
    document.getElementById('cancelDelete').onclick = () => modal.classList.add('hidden');

    // Confirm button submits form
    document.getElementById('confirmDelete').onclick = () => {
        if(deleteFormId) document.getElementById(deleteFormId).submit();
    };
}

// Optional: close modal on outside click or ESC key
window.addEventListener('click', (e) => {
    const modal = document.getElementById('deleteModal');
    if(modal && !modal.classList.contains('hidden') && e.target === modal){
        modal.classList.add('hidden');
    }
});
window.addEventListener('keydown', (e) => {
    if(e.key === "Escape"){
        const modal = document.getElementById('deleteModal');
        if(modal && !modal.classList.contains('hidden')){
            modal.classList.add('hidden');
        }
    }
});
</script>
@endsection