<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CIKOP')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="text-gray-800" style="background-color: #FFF8E7;">
    <div class="min-h-screen">
        <header class="bg-transparent shadow-none">
            <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">CIKOP</h1>
                <a href="{{ route('menu') }}" class="text-sm px-3 py-2 bg-green-600 text-white rounded">Menu</a>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 py-6">
            @yield('content')
        </main>
    </div>

    <script src="/js/cart.js"></script>
    @stack('scripts')
</body>
</html>
