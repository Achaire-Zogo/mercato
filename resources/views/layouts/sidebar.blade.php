<div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
    <div class="flex items-center flex-shrink-0 px-4">
        <span class="text-xl font-semibold text-white">Mercato POS</span>
    </div>
    <nav class="mt-5 flex-1 px-2 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('products.index') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('products.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Produits
        </a>

        <a href="{{ route('sales.index') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('sales.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ventes
        </a>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.index') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('categories.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Catégories
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Rapports
            </a>

            <a href="{{ route('users.index') }}" class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Utilisateurs
            </a>
        @endif
    </nav>
</div>
