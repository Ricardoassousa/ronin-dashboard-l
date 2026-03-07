<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ronin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-[#fafafa] text-slate-900 tracking-tight">

    <!-- Minimal Nav -->
    <nav class="flex justify-between items-center px-12 h-24 max-w-6xl mx-auto">
        <div class="text-xl font-black uppercase tracking-tighter italic">
            Ronin<span class="text-blue-600">.</span>
        </div>

        <div class="flex items-center gap-10 text-[13px] font-bold uppercase tracking-widest text-slate-500">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-slate-900 border-b-2 border-blue-600 pb-1 hover:text-blue-600 transition">Join</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Ultra Minimal Hero -->
    <main class="max-w-4xl mx-auto px-6 pt-32 pb-40 text-center">
        <h1 class="text-7xl md:text-8xl font-black mb-8 leading-none tracking-tighter">
            Smart. Simple. <br>
            <span class="text-slate-300">Ronin.</span>
        </h1>
        
        <p class="text-xl text-slate-400 mb-12 font-light max-w-lg mx-auto leading-relaxed">
            The high-performance dashboard for modern business management. No clutter, just results.
        </p>

        <a href="{{ route('register') }}" class="inline-block bg-slate-900 text-white px-12 py-5 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-blue-600 hover:scale-105 transition-all duration-300 shadow-xl shadow-slate-200">
            Start Experience
        </a>
    </main>

    <!-- Content Preview (Abstract) -->
    <section class="max-w-5xl mx-auto px-6">
        <div class="bg-white border border-slate-100 rounded-3xl h-96 shadow-sm flex items-center justify-center">
            <div class="w-20 h-1 bg-slate-100 rounded-full relative">
                <div class="absolute inset-0 bg-blue-600 w-1/2 rounded-full"></div>
            </div>
        </div>
    </section>

    <footer class="py-20 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-slate-300">
            &copy; {{ date('Y') }} Ronin Dashboard. All rights reserved.
        </span>
    </footer>

</body>
</html>