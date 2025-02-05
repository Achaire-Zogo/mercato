<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mercato POS') }}</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @auth
        <div class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition-transform duration-300 transform"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="flex justify-center items-center h-16 bg-gray-800">
                <span class="text-xl font-semibold text-white">Mercato POS</span>
            </div>

            <nav class="px-2 mt-5">
                <a href="{{ route('products.index') }}" class="flex items-center px-2 py-2 text-base font-medium text-gray-300 rounded-md group hover:bg-gray-700 hover:text-white">
                    <svg class="mr-4 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Products
                </a>

                <a href="{{ route('sales.index') }}" class="flex items-center px-2 py-2 mt-1 text-base font-medium text-gray-300 rounded-md group hover:bg-gray-700 hover:text-white">
                    <svg class="mr-4 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Sales
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.index') }}" class="flex items-center px-2 py-2 mt-1 text-base font-medium text-gray-300 rounded-md group hover:bg-gray-700 hover:text-white">
                    <svg class="mr-4 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Categories
                </a>

                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Reports') }}
                    </x-nav-link>
                @endif
            </nav>
        </div>
        @endauth

        <!-- Content -->
        <div class="flex-1" :class="{'ml-64': sidebarOpen}">
            <!-- Top Navigation -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            @auth
                            <button @click="sidebarOpen = !sidebarOpen" class="px-4 text-gray-500 focus:outline-none focus:bg-gray-100 focus:text-gray-600 lg:hidden">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            @endauth
                        </div>

                        @auth
                        <div class="flex items-center">
                            <div x-data="{ open: false }" class="relative ml-3">
                                <div>
                                    <button @click="open = !open" class="flex text-sm rounded-full border-2 border-transparent focus:outline-none focus:border-gray-300">
                                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                        <svg class="ml-2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg origin-top-right">
                                    <div class="py-1 bg-white rounded-md shadow-xs">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block px-4 py-2 w-full text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="py-6">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
