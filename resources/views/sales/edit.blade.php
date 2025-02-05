@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-6 text-xl font-semibold">Modifier la vente #{{ $sale->invoice_number }}</h2>

                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sales.update', $sale) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Mode de paiement</label>
                                <select id="payment_method" name="payment_method" class="block px-4 py-3 mt-1 w-full text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="cash" {{ $sale->payment_method === 'cash' ? 'selected' : '' }}>Espèces</option>
                                    <option value="card" {{ $sale->payment_method === 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                    <option value="transfer" {{ $sale->payment_method === 'transfer' ? 'selected' : '' }}>Virement</option>
                                </select>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Produits</h3>
                                <div class="mt-4 space-y-4">
                                    @foreach($sale->items as $item)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <select name="items[{{ $loop->index }}][product_id]" class="block px-4 py-3 w-full text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $item->product_id === $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-32">
                                            <label class="sr-only">Quantité</label>
                                            <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" min="1" class="block px-4 py-3 w-full text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        </div>
                                        <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-md border border-transparent hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="addProductRow()">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter un produit
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end items-center space-x-4">
                            <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

    <script>
        function addProductRow() {
            const container = document.querySelector('.space-y-4');
            const index = container.querySelectorAll('.flex.items-center.space-x-4').length;

            const template = `
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <select name="items[\${index}][product_id]" class="block px-4 py-3 w-full text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Sélectionner un produit</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock_quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <input type="number" name="items[\${index}][quantity]" value="1" min="1" class="block px-4 py-3 w-full text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
        }
    </script>
