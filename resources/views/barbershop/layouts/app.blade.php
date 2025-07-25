<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-semibold">Barbershop</h1>
            </div>
            <nav class="mt-4">
                <div class="px-4 py-2 text-gray-400 uppercase text-xs font-semibold">Main</div>
                <a href="{{ route('barbershop.dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('barbershop.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                {{-- <div class="px-4 py-2 text-gray-400 uppercase text-xs font-semibold">Management</div>
                <a href="{{ route('barbershop.barbers.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('barbershop.barbers.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user mr-2"></i> Barbers
                </a>
                <a href="{{ route('barbershop.services.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('barbershop.services.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-cut mr-2"></i> Services
                </a>
                <a href="{{ route('barbershop.bookings.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('barbershop.bookings.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-alt mr-2"></i> Bookings
                </a> --}}
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>