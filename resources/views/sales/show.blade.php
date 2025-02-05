@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Détails de la vente #{{ $sale->invoice_number }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('sales.download-pdf', $sale) }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-md shadow-sm hover:bg-red-500">
                    <svg class="mr-1.5 -ml-0.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Télécharger PDF
                </a>
                <a href="{{ route('sales.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 shadow-sm hover:bg-gray-50">
                    <svg class="mr-1.5 -ml-0.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Informations de la vente -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Informations générales</h3>
                        <dl class="mt-4 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro de facture</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $sale->invoice_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $sale->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @switch($sale->payment_method)
                                        @case('cash')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full">CASH</span>
                                            @break
                                        @case('card')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">CREDIT CARD</span>
                                            @break
                                        @case('transfer')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-red-800 bg-red-100 rounded-full">TRANSFERT</span>
                                            @break
                                    @endswitch
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Totaux</h3>
                        <dl class="mt-4 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sous-total</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($sale->subtotal, 2) }} €</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">TVA (20%)</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($sale->tax, 2) }} €</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($sale->total_amount, 2) }} €</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails des produits -->
        <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Produits</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                    {{ $item->product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                    {{ number_format($item->unit_price, 2) }} €
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                    {{ number_format($item->line_total, 2) }} €
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    <div class="flex justify-end">
                        <div class="text-right">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Sous-total:</span>
                                <span class="ml-2">{{ number_format($sale->subtotal, 2) }} €</span>
                            </div>
                            <div class="text-sm text-gray-600 mt-2">
                                <span class="font-medium">TVA (20%):</span>
                                <span class="ml-2">{{ number_format($sale->tax, 2) }} €</span>
                            </div>
                            <div class="text-lg font-bold text-gray-900 mt-4">
                                <span>Total:</span>
                                <span class="ml-2">{{ number_format($sale->total_amount, 2) }} €</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
