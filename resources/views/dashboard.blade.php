<x-app-layout>

    <!-- Dashboard header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <!-- Main responsive container -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!--
                Livewire component call.
                In Laravel 9 / Livewire 2, this is the standard way to
                inject the reactive dashboard logic.
            -->
            <livewire:dashboard-stats />

        </div>
    </div>

</x-app-layout>