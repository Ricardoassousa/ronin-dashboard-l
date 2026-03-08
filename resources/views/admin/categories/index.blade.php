@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <!-- Header + Create button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Create Category
        </a>
    </div>

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow">{{ session('error') }}</div>
    @endif

    <!-- Filter Form -->
    <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2">
        <input type="text" name="name" placeholder="Search by name..." value="{{ request('name') }}" class="border p-2 rounded flex-1">
        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
    </form>

    <!-- Categories Table / Mobile Cards -->
    <div class="overflow-x-auto bg-white shadow rounded">

        <!-- Desktop Table -->
        <table class="min-w-full table-auto hidden md:table">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Created At</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2">{{ $category->name }}</td>
                    <td class="p-2">{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="p-2 flex flex-wrap gap-2">
                        <a href="{{ route('admin.categories.show', $category) }}" class="text-blue-600 px-2 py-1 border rounded">View</a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 px-2 py-1 border rounded">Edit</a>

                        <!-- Hidden Delete Form -->
                        <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <button type="button" class="text-red-600 px-2 py-1 border rounded" onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}')">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @foreach($categories as $category)
            <div class="bg-gray-50 p-4 rounded shadow flex flex-col gap-2">
                <div><span class="font-semibold">Name:</span> {{ $category->name }}</div>
                <div><span class="font-semibold">Created:</span> {{ $category->created_at->format('Y-m-d') }}</div>
                <div class="flex flex-wrap gap-3 mt-2">
                    <a href="{{ route('admin.categories.show', $category) }}" class="text-blue-600 material-icons" title="View">visibility</a>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 material-icons" title="Edit">edit</a>

                    <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <button type="button" class="text-red-600 material-icons" title="Delete" onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}')">delete</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $categories->links() }}</div>

    <!-- Include Delete Modal Partial -->
    @include('admin.partials.delete-modal', ['entityName' => 'Category'])
</div>

<!-- JavaScript: Responsive Delete Modal -->
<script>
let deleteFormId = null;

function openDeleteModal(categoryId, categoryName) {
    deleteFormId = 'delete-form-' + categoryId;
    const modal = document.getElementById('deleteModal');

    // Show modal
    modal.classList.remove('hidden');

    // Set category name in modal
    document.getElementById('deleteEntityName').innerText = categoryName;

    // Cancel button hides modal
    document.getElementById('cancelDelete').onclick = () => modal.classList.add('hidden');

    // Confirm button submits form
    document.getElementById('confirmDelete').onclick = () => {
        if(deleteFormId) document.getElementById(deleteFormId).submit();
    };
}

// Optional: Close modal when clicking outside or pressing ESC
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