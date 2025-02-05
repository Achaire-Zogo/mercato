@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ isset($product) ? 'Edit Product' : 'Create Product' }}
        </h1>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6 p-6">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Matricule -->
                <div class="sm:col-span-3">
                    <label for="matricule" class="block text-sm font-medium text-gray-700">SKU</label>
                    <div class="mt-1">
                        <input type="text" 
                               name="matricule" 
                               id="matricule" 
                               value="{{ old('matricule', $product->matricule ?? '') }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    @error('matricule')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1">
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $product->name ?? '') }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="sm:col-span-3">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <div class="mt-1">
                        <select name="category_id" 
                                id="category_id" 
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="sm:col-span-3">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               value="{{ old('price', $product->price ?? '') }}"
                               step="0.01"
                               min="0"
                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Quantity -->
                <div class="sm:col-span-3">
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <div class="mt-1">
                        <input type="number" 
                               name="stock_quantity" 
                               id="stock_quantity" 
                               value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}"
                               min="0"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    @error('stock_quantity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alert Threshold -->
                <div class="sm:col-span-3">
                    <label for="alert_threshold" class="block text-sm font-medium text-gray-700">Alert Threshold</label>
                    <div class="mt-1">
                        <input type="number" 
                               name="alert_threshold" 
                               id="alert_threshold" 
                               value="{{ old('alert_threshold', $product->alert_threshold ?? '') }}"
                               min="0"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    @error('alert_threshold')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiration Date -->
                <div class="sm:col-span-3">
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                    <div class="mt-1">
                        <input type="date" 
                               name="expiration_date" 
                               id="expiration_date" 
                               value="{{ old('expiration_date', isset($product) ? $product->expiration_date->format('Y-m-d') : '') }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    @error('expiration_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="sm:col-span-6">
                    <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ isset($product) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
