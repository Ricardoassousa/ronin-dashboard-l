@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Header + Create Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Products</h1>
        <a href="{{ route('admin.products.create') }}" 
           class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
           Create Product
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

    <!-- Filter Form -->
    <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2">
        <input type="text" name="name" placeholder="Search by name..." value="{{ request('name') }}" 
               class="border p-2 rounded flex-1">
        <button class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Filter
        </button>
    </form>

    <!-- Products Table -->
    <div class="overflow-x-auto bg-white shadow rounded">

        <!-- Desktop Table -->
        <table class="min-w-full table-auto hidden md:table">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Price</th>
                    <th class="p-2 text-left">Stock</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2">{{ $product->name }}</td>
                    <td class="p-2">${{ $product->price }}</td>
                    <td class="p-2">{{ $product->stock }}</td>
                    <td class="p-2 flex gap-2">
                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 px-2 py-1 border rounded">View</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 px-2 py-1 border rounded">Edit</a>

                        <!-- Hidden Delete Form -->
                        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <button type="button" class="text-red-600 px-2 py-1 border rounded"
                                onclick="openDeleteModal({{ $product->id }}, '{{ $product->name }}')">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @foreach($products as $product)
            <div class="bg-gray-50 p-4 rounded shadow">
                <div><span class="font-semibold">Name: </span>{{ $product->name }}</div>
                <div><span class="font-semibold">Price: </span>${{ $product->price }}</div>
                <div><span class="font-semibold">Stock: </span>{{ $product->stock }}</div>
                <div class="flex gap-3 mt-2">
                    <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 material-icons" title="View">visibility</a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 material-icons" title="Edit">edit</a>

                    <!-- Hidden Delete Form -->
                    <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <button type="button" class="text-red-600 material-icons" title="Delete"
                            onclick="openDeleteModal({{ $product->id }}, '{{ $product->name }}')">
                        delete
                    </button>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <!-- Include Delete Modal Partial -->
    @include('admin.partials.delete-modal', ['entityName' => 'Product'])

</div>

<!-- JavaScript: Delete Modal -->
<script>
let deleteFormId = null;

function openDeleteModal(productId, productName) {
    deleteFormId = 'delete-form-' + productId;
    const modal = document.getElementById('deleteModal');

    // Show modal
    modal.classList.remove('hidden');

    // Set product name in modal
    document.getElementById('deleteEntityName').innerText = productName;

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