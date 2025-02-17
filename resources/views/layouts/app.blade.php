<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mercato') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 16rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex overflow-hidden h-screen">
        <!-- Sidebar -->
        @auth
        <div class="flex-shrink-0 lg:flex">
            <!-- Mobile sidebar -->
            <div class="lg:hidden">
                <div class="flex fixed inset-0 z-40" x-show="sidebarOpen" @click.away="sidebarOpen = false" x-cloak>
                    <div class="fixed inset-0" aria-hidden="true" x-show="sidebarOpen">
                        <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                    </div>
                    <div class="flex relative flex-col flex-1 w-full max-w-xs bg-gray-900">
                        <div class="absolute top-0 right-0 pt-2 -mr-12">
                            <button @click="sidebarOpen = false" class="flex justify-center items-center ml-1 w-10 h-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>
            <!-- Desktop sidebar -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col flex-1 h-0 bg-gray-900">
                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>
        </div>
        @endauth

        <!-- Main content -->
        <div class="flex overflow-hidden flex-col flex-1 w-0">
            <!-- Top Navigation -->
            <div class="flex relative z-10 flex-shrink-0 h-16 bg-white shadow">
                <button @click="sidebarOpen = true" class="px-4 text-gray-500 border-r border-gray-200 lg:hidden focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex flex-1 justify-between px-4">
                    <div class="flex flex-1"></div>
                    @auth
                    <div class="flex items-center ml-4 md:ml-6">
                        <div x-data="{ open: false }" class="relative ml-3">
                            <div>
                                <button @click="open = !open" class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="ml-2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 py-1 mt-2 w-48 bg-white rounded-md ring-1 ring-black ring-opacity-5 shadow-lg origin-top-right"
                                 role="menu"
                                 x-cloak>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="overflow-y-auto relative flex-1 focus:outline-none">
                <div class="py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
