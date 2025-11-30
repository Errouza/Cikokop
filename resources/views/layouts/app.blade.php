<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cikop')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom colors that might be reused */
        .text-primary { color: #dca259; }
        .bg-primary { background-color: #dca259; }
        .bg-primary-dark { background-color: #c58c3e; }
        .hover\:bg-primary-dark:hover { background-color: #c58c3e; }
        .border-primary { border-color: #dca259; }
    </style>
    @stack('styles')
</head>
<body class="font-sans bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="{{ asset('image/LogoCikopVersion2.svg') }}" alt="Cikop Logo" class="h-10 transition-transform duration-300 group-hover:scale-105">
                </a>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('magic') }}" class="px-4 py-2 text-gray-700 font-medium rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-yellow-500/10">
                        Magic
                    </a>
                    <a href="{{ route('menu') }}" class="px-4 py-2 text-white font-medium rounded-md bg-primary transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:bg-primary-dark">
                        Menu
                    </a>
                    @auth
                        <a href="{{ route('admin.menu.index') }}" class="px-4 py-2 text-gray-700 font-medium rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-yellow-500/10">
                            Admin
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-gray-700 font-medium rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-yellow-500/10">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 font-medium rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-yellow-500/10">
                            Login Admin
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-grow max-w-6xl w-full mx-auto px-6 py-8">
            @yield('content')
        </main>

        <footer class="bg-white py-6 border-t border-gray-100">
            <div class="max-w-6xl mx-auto px-6 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Cikop. All rights reserved.
            </div>
        </footer>
    </div>

    <script src="/js/cart.js"></script>
    @stack('scripts')
</body>
</html>