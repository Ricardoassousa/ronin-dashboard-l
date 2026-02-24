<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Overview
        </h2>
    </x-slot>

    <div wire:poll.keep-alive.5s="loadData">
        <livewire:dashboard-stats />
    </div>
</x-app-layout>