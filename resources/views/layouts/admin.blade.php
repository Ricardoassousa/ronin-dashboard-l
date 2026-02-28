<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <aside class="w-72 bg-gray-900 text-white p-6 shadow-lg rounded-r-xl flex flex-col">
        <h2 class="text-2xl font-bold text-center text-white border-b border-gray-700 pb-4 mb-6">Admin Panel</h2>

        <nav class="flex-1 space-y-4">
            @php
                $links = [
                    ['route' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                    ['route' => 'admin.products.index', 'icon' => 'inventory_2', 'label' => 'Products'],
                    ['route' => 'admin.categories.index', 'icon' => 'category', 'label' => 'Categories'],
                    ['route' => 'admin.customers.index', 'icon' => 'people', 'label' => 'Customers'],
                    ['route' => 'admin.orders.index', 'icon' => 'shopping_cart', 'label' => 'Orders'],
                    ['route' => 'admin.files.index', 'icon' => 'folder', 'label' => 'Files']
                ];
            @endphp

            @foreach($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="flex items-center gap-3 p-3 rounded transition-colors duration-200 text-white
                            border-l-4 border-transparent
                            hover:bg-gray-800
                            {{ request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*') ? 'bg-gray-800 border-blue-500' : '' }}">
                    <span class="flex-shrink-0 w-8 flex justify-center material-icons text-lg">
                        {{ $link['icon'] }}
                    </span>
                    <span class="text-lg font-medium">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-10 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} Ronin Dashboard
        </div>
    </aside>

    <main class="flex-1 p-8 bg-gray-100">
        @yield('content')
    </main>

</div>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</body>
</html>