@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="flex space-x-3">
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Image -->
            <div class="lg:col-span-1 p-6">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg shadow">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="lg:col-span-2 p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->matricule }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->price, 2) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock_quantity > $product->alert_threshold ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock_quantity }} units
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alert Threshold</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->alert_threshold }} units</dd>
                    </div>

                    @if($product->expiration_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expiration Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->expiration_date->format('M d, Y') }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>

                @if(auth()->user()->isAdmin())
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900">Stock Management</h3>
                    <form action="{{ route('products.updateStock', $product) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label for="adjustment" class="block text-sm font-medium text-gray-700">Adjust Stock</label>
                                <div class="mt-1">
                                    <input type="number" 
                                           name="adjustment" 
                                           id="adjustment" 
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           required>
                                </div>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Stock
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
