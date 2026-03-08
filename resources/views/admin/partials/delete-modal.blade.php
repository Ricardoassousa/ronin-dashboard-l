<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Delete {{ $entityName ?? 'Item' }}</h2>
        <p class="text-gray-600 mb-6 text-sm sm:text-base">
            Are you sure you want to delete 
            <span id="deleteEntityName" class="font-semibold"></span>?
        </p>
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">Cancel</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 w-full sm:w-auto">Delete</button>
        </div>
    </div>
</div>