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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            color: #2e2e2e;
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 6px;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            background-color: rgba(220, 162, 89, 0.1);
        }
        .btn-primary {
            background-color: #dca259;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 162, 89, 0.2);
        }
        .logo {
            height: 40px;
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3">
                    <img src="{{ asset('image/LogoCikopVersion2.svg') }}" alt="Cikop Logo" class="logo">
                </a>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('magic') }}" class="nav-link px-4 py-2 text-gray-700 font-medium">
                        Magic
                    </a>
                    <a href="{{ route('menu') }}" class="nav-link px-4 py-2 text-white font-medium rounded-md btn-primary">
                        Menu
                    </a>
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