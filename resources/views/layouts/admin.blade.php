<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Panel</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Font Figtree -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>

</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">

    <!-- Global Navbar -->
    @include('layouts.navigation')

    <!-- Main Layout -->
    <div class="flex h-[calc(100vh-4rem)] pt-16">

        <!-- Mobile overlay -->
        <div
            x-show="sidebarOpen"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
            x-transition.opacity
            @click="sidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-16 bottom-0 left-0 w-64 bg-gray-900 text-white shadow-lg
                   flex flex-col z-30 transform transition-transform duration-300
                   md:translate-x-0 overflow-y-auto">

            <!-- Sidebar header -->
            <h2 class="text-2xl font-bold text-center border-b border-gray-700 py-4 mb-6">
                Admin Panel
            </h2>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 px-4 pb-4">
                @php
                    $links = [
                        ['route' => 'admin.products.index', 'icon' => 'inventory_2', 'label' => 'Products'],
                        ['route' => 'admin.categories.index', 'icon' => 'category', 'label' => 'Categories'],
                        ['route' => 'admin.customers.index', 'icon' => 'people', 'label' => 'Customers'],
                        ['route' => 'admin.orders.index', 'icon' => 'shopping_cart', 'label' => 'Orders'],
                        ['route' => 'admin.files.index', 'icon' => 'folder', 'label' => 'Files'],
                    ];
                @endphp

                @foreach($links as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="flex items-center gap-3 p-3 rounded transition-colors duration-200
                               border-l-4 border-transparent hover:bg-gray-800
                               {{ request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*') ? 'bg-gray-800 border-blue-500' : 'text-white' }}">
                        <span class="material-icons w-8 flex justify-center">{{ $link['icon'] }}</span>
                        <span class="font-medium">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- Sidebar footer -->
            <div class="text-center text-gray-400 text-sm px-4 pb-4">
                &copy; {{ date('Y') }} Ronin Dashboard
            </div>

        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col md:ml-64">

            <!-- Mobile top bar -->
            <header class="md:hidden bg-gray-900 text-white flex items-center justify-between p-4 shadow">
                <h1 class="text-lg font-bold">Admin Panel</h1>
                <button @click="sidebarOpen = !sidebarOpen" class="material-icons text-2xl">menu</button>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 sm:p-6 overflow-auto bg-gray-100">

                <!-- Header optional (Dashboard Overview etc.) -->
                @hasSection('header')
                <div class="bg-white shadow px-6 py-4 mb-6 rounded">
                    @yield('header')
                </div>
                @endif

                <!-- Page content -->
                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>