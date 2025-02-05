@extends('layouts.app')

@section('content')
<div class="container px-4 mx-auto sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Catégories</h1>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="mr-2 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nouvelle Catégorie
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="p-4 mb-6 rounded-md bg-green-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <ul class="divide-y divide-gray-200">
            @forelse($categories as $category)
            <li>
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $category->name }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                {{ $category->description ?? 'Aucune description' }}
                            </p>
                            <div class="mt-2 text-sm text-gray-500">
                                {{ $category->products_count ?? 0 }} produits dans cette catégorie
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="flex space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-md border border-transparent hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded-md border border-transparent hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-8">
                <div class="text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle catégorie.</p>
                    @if(auth()->user()->isAdmin())
                    <div class="mt-6">
                        <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="mr-2 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Créer une catégorie
                        </a>
                    </div>
                    @endif
                </div>
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection
