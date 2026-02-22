<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Overview
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 shadow rounded">
                    <h3 class="text-gray-500">Total Users</h3>
                    <p class="text-3xl font-bold">
                        {{ $stats['users'] }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow rounded">
                    <h3 class="text-gray-500">Total Orders</h3>
                    <p class="text-3xl font-bold">
                        TO DO
                    </p>
                </div>

                <div class="bg-white p-6 shadow rounded">
                    <h3 class="text-gray-500">Total Revenue</h3>
                    <p class="text-3xl font-bold">
                        TO DO
                    </p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>