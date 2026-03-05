<x-app-layout>

    <!-- Dashboard header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Overview
        </h2>
    </x-slot>

    <!-- Main responsive container -->
    <div class="container mx-auto px-4 py-6">

        <!-- Responsive grid layout -->
        <!--
            grid-cols-1  -> mobile (1 column)
            md:grid-cols-2 -> tablet (2 columns)
            lg:grid-cols-3 -> desktop (3 columns)
        -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Livewire component that loads dashboard statistics -->
            <!-- wire:poll.keep-alive refreshes data every 5 seconds -->
            <div class="col-span-1 md:col-span-2 lg:col-span-3"
                 wire:poll.keep-alive.5s="loadData">

                <livewire:dashboard-stats />

            </div>

        </div>

    </div>

</x-app-layout>