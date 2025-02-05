@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Ventes</h1>
            <a href="{{ route('sales.create') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="mr-1.5 -ml-0.5 w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nouvelle Vente
            </a>
        </div>

        <!-- Filtres -->
        <div class="mt-4 bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('sales.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div>
                        <label for="date_start" class="block text-sm font-medium text-gray-700">Date début</label>
                        <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="date_end" class="block text-sm font-medium text-gray-700">Date fin</label>
                        <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Tous</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complétée</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex justify-center px-3 py-2 w-full text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques -->
        <dl class="grid grid-cols-1 gap-5 mt-4 sm:grid-cols-4">
            <div class="overflow-hidden px-4 py-5 bg-white rounded-lg shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Ventes du jour</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $todaySales ?? 0 }}</dd>
            </div>
            <div class="overflow-hidden px-4 py-5 bg-white rounded-lg shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Chiffre d'affaires du jour</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($todayRevenue ?? 0, 2) }} €</dd>
            </div>
            <div class="overflow-hidden px-4 py-5 bg-white rounded-lg shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Ventes du mois</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $monthSales ?? 0 }}</dd>
            </div>
            <div class="overflow-hidden px-4 py-5 bg-white rounded-lg shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Chiffre d'affaires du mois</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($monthRevenue ?? 0, 2) }} €</dd>
            </div>
        </dl>

        <!-- Liste des ventes -->
        <div class="mt-4 bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col" class="py-3.5 pr-3 pl-4 text-sm font-semibold text-left text-gray-900 sm:pl-6">Référence</th>
                                <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-left text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-left text-gray-900">Total</th>
                                <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-left text-gray-900">Statut</th>
                                <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($sales as $sale)
                            <tr>
                                <td class="py-4 pr-3 pl-4 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-6">
                                    {{ $sale->invoice_number }}
                                </td>

                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ number_format($sale->total, 2) }} €
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap">
                                    @switch($sale->status)
                                        @case('completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full">Complétée</span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">En attente</span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-red-800 bg-red-100 rounded-full">Annulée</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">{{ $sale->status }}</span>
                                    @endswitch
                                </td>
                                <td class="relative py-4 pr-4 pl-3 text-sm font-medium text-right whitespace-nowrap sm:pr-6">
                                    <a href="{{ route('sales.show', $sale) }}" class="mr-3 text-indigo-600 hover:text-indigo-900">Voir</a>
                                    <a href="{{ route('sales.edit', $sale) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-sm text-center text-gray-500">
                                    Aucune vente trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($sales, 'links'))
                <div class="mt-4">
                    {{ $sales->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
