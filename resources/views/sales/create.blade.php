@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle Vente</h1>
                <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>

            <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Sélection des Produits -->
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Produits</h2>
                            <button type="button" id="addProductButton"
                                class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="mr-1.5 -ml-0.5 w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Ajouter un produit
                            </button>
                        </div>

                        <div id="products-container" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 items-end product-row sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Produit</label>
                                    <select name="items[0][product_id]" class="block mt-1 w-full h-10 rounded-md border-gray-300 shadow-sm product-select focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                                {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantité</label>
                                    <input type="number" name="items[0][quantity]" min="1" value="1"
                                        class="block mt-1 w-full h-10 rounded-md border-gray-300 shadow-sm quantity-input focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prix unitaire</label>
                                    <input type="number" step="0.01" class="block mt-1 w-full h-10 bg-gray-50 rounded-md border-gray-300 shadow-sm unit-price sm:text-sm" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total</label>
                                    <input type="number" step="0.01" class="block mt-1 w-full h-10 bg-gray-50 rounded-md border-gray-300 shadow-sm line-total sm:text-sm" readonly>
                                </div>
                                <div class="flex justify-center">
                                    <button type="button" class="mt-1 text-red-600 remove-row hover:text-red-900" style="display: none;">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiement -->
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Paiement</h2>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sous-total</label>
                                <input type="number" step="0.01" id="subtotal" name="subtotal" readonly
                                    class="block mt-1 w-full h-10 bg-gray-50 rounded-md border-gray-300 shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">TVA (20%)</label>
                                <input type="number" step="0.01" id="tax" name="tax" readonly
                                    class="block mt-1 w-full h-10 bg-gray-50 rounded-md border-gray-300 shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total TTC</label>
                                <input type="number" step="0.01" id="total" name="total" readonly
                                    class="block mt-1 w-full h-10 font-semibold bg-gray-50 rounded-md border-gray-300 shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                                <select name="payment_method" class="block mt-1 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="transfer">Virement</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Montant payé</label>
                                <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                                    class="block mt-1 w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    onchange="calculateChange()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Monnaie à rendre</label>
                                <input type="number" step="0.01" id="change_amount" readonly
                                    class="block mt-1 w-full h-10 bg-gray-50 rounded-md border-gray-300 shadow-sm sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('sales.index') }}"
                        class="inline-flex justify-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 shadow-sm hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Créer la vente
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


<script>
// Variables globales
let rowCount = 0;

// Fonction pour ajouter une nouvelle ligne de produit
function addProductRow() {
    rowCount++;
    const container = document.getElementById('products-container');
    const newRow = container.children[0].cloneNode(true);

    // Mise à jour des noms des champs
    newRow.querySelectorAll('select, input').forEach(input => {
        if (input.name && input.name.includes('[0]')) {
            input.name = input.name.replace('[0]', `[${rowCount}]`);
        }
        if (input.type === 'number') {
            input.value = input.classList.contains('quantity-input') ? '1' : '';
        }
    });

    // Réinitialisation des champs
    const select = newRow.querySelector('.product-select');
    select.selectedIndex = 0;
    newRow.querySelector('.unit-price').value = '';
    newRow.querySelector('.line-total').value = '';

    // Gestion du bouton de suppression
    const removeButton = newRow.querySelector('.remove-row');
    removeButton.style.display = 'block';
    removeButton.onclick = function() {
        this.closest('.product-row').remove();
        updateTotals();
    };

    container.appendChild(newRow);
    initializeRowEvents(newRow);
}

// Initialisation des événements d'une ligne
function initializeRowEvents(row) {
    const select = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity-input');

    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption) {
            row.querySelector('.unit-price').value = selectedOption.dataset.price || '';
            calculateLineTotal(row);
        }
    });

    quantityInput.addEventListener('input', function() {
        calculateLineTotal(row);
    });
}

// Calcul du total par ligne
function calculateLineTotal(row) {
    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const select = row.querySelector('.product-select');
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption && selectedOption.value) {
        const stock = parseInt(selectedOption.dataset.stock);

        if (quantity > stock) {
            alert(`Stock insuffisant. Stock disponible: ${stock}`);
            row.querySelector('.quantity-input').value = stock;
            calculateLineTotal(row);
            return;
        }

        const lineTotal = quantity * unitPrice;
        row.querySelector('.line-total').value = lineTotal.toFixed(2);
    } else {
        row.querySelector('.line-total').value = '';
    }

    updateTotals();
}

// Mise à jour des totaux
function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.line-total').forEach(input => {
        subtotal += parseFloat(input.value || 0);
    });

    const tax = subtotal * 0.20;
    const total = subtotal + tax;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('tax').value = tax.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    calculateChange();
}

// Calcul de la monnaie à rendre
function calculateChange() {
    const total = parseFloat(document.getElementById('total').value) || 0;
    const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const change = amountPaid - total;

    document.getElementById('change_amount').value = change >= 0 ? change.toFixed(2) : '0.00';
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addProductButton').addEventListener('click', addProductRow);
    const firstRow = document.querySelector('.product-row');
    if (firstRow) {
        initializeRowEvents(firstRow);
    }

    const amountPaidInput = document.getElementById('amount_paid');
    if (amountPaidInput) {
        amountPaidInput.addEventListener('input', calculateChange);
    }

    // Gestionnaire de soumission du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Vente enregistrée avec succès !');
                    window.location.href = data.redirect || '/sales';
                } else {
                    alert('Erreur lors de l\'enregistrement de la vente.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'enregistrement.');
            });
        });
    }
});
</script>
