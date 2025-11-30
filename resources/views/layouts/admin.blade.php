<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Cikop')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            color: #2e2e2e;
        }
        .admin-nav-link {
            transition: all 0.3s ease;
            border-radius: 6px;
        }
        .admin-nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .admin-nav-link.active {
            background-color: #dca259;
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.menu.index') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('image/LogoCikopVersion2.svg') }}" alt="Cikop Logo" class="h-10">
                        <span class="font-bold text-xl text-gray-800">Admin Panel</span>
                    </a>
                    
                    <nav class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('admin.menu.index') }}" class="admin-nav-link px-4 py-2 font-medium {{ request()->routeIs('admin.menu.*') ? 'active' : 'text-gray-600' }}">
                            Menu
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="admin-nav-link px-4 py-2 font-medium {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-gray-600' }}">
                            Pesanan
                        </a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-grow max-w-7xl w-full mx-auto px-6 py-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white py-6 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} Cikop Admin Panel.
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
