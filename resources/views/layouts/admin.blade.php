<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Figtree Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans antialiased bg-gray-100 overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- 1. Global Navbar: Outside any flexbox to avoid being "pushed" -->
    <div class="h-16 w-full fixed top-0 left-0 z-50 bg-white shadow">
        @include('layouts.navigation')
    </div>

    <!-- 2. Main Container: Starts after the navbar (pt-16) -->
    <div class="flex h-screen pt-16">

        <!-- Sidebar (Desktop) -->
        <!-- Fixed w-64 to ensure 'main' knows exactly where to start -->
        <aside class="hidden md:flex flex-col w-64 bg-gray-900 text-white shadow-lg shrink-0">
            <h2 class="text-2xl font-bold text-center border-b border-gray-700 py-4 mb-6">
                Admin Panel
            </h2>

            <!-- Navigation Links -->
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
                    @php
                        /* Extract resource name to keep link active for all CRUD routes (create, edit, etc.) */
                        $resourceName = str_replace('.index', '', $link['route']);
                        $active = request()->routeIs($link['route']) || request()->routeIs($resourceName . '.*');
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 p-3 rounded transition-colors duration-200 border-l-4
                              {{ $active ? 'bg-gray-800 border-blue-500 text-white' : 'text-white border-transparent hover:bg-gray-800' }}">
                        <span class="material-icons w-8 flex justify-center text-[24px]">{{ $link['icon'] }}</span>
                        <span class="font-medium">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Content Area + Mobile Top Bar -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Mobile Toggle Bar (Appears below Global Navbar on mobile) -->
            <header class="md:hidden bg-gray-900 text-white shadow-md z-40 border-t border-gray-800 shrink-0">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-lg font-bold">Admin Panel</h1>
                    <button @click="sidebarOpen = !sidebarOpen" class="material-icons text-2xl p-1 bg-gray-800 rounded">
                        <span x-text="sidebarOpen ? 'close' : 'menu'">menu</span>
                    </button>
                </div>

                <!-- Expandable Mobile Menu -->
                <div x-show="sidebarOpen" x-collapse x-cloak class="bg-gray-900 px-4 pb-4 space-y-1 overflow-y-auto max-h-[calc(100vh-12rem)]">
                    @foreach($links as $link)
                        @php 
                            $resourceName = str_replace('.index', '', $link['route']);
                            $active = request()->routeIs($link['route']) || request()->routeIs($resourceName . '.*'); 
                        @endphp
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center gap-3 p-3 rounded border-l-4 
                                  {{ $active ? 'bg-gray-800 border-blue-500 text-white' : 'text-white border-transparent hover:bg-gray-800' }}">
                            <span class="material-icons w-8 flex justify-center text-[20px]">{{ $link['icon'] }}</span>
                            <span class="font-medium text-sm">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-gray-100">
                
                <!-- Optional Header (Dashboard Overview etc.) -->
                @hasSection('header')
                <div class="bg-white shadow px-6 py-4 mb-6 rounded border border-gray-200">
                    @yield('header')
                </div>
                @endif

                <!-- Page Content -->
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>