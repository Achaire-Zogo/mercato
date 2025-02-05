@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <p class="mt-2 text-sm text-gray-700">Access and generate various reports for your business</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <!-- Sales Report Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900">Sales Report</h2>
                        <p class="text-sm text-gray-600">View sales performance and trends</p>
                    </div>
                </div>
                <a href="{{ route('reports.sales') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    View Report
                </a>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-sm">
                    <a href="{{ route('reports.export', 'sales') }}" class="text-indigo-600 hover:text-indigo-900">Export to CSV</a>
                </div>
            </div>
        </div>

        <!-- Products Report Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900">Products Report</h2>
                        <p class="text-sm text-gray-600">Analyze product performance</p>
                    </div>
                </div>
                <a href="{{ route('reports.products') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    View Report
                </a>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-sm">
                    <a href="{{ route('reports.export', 'products') }}" class="text-green-600 hover:text-green-900">Export to CSV</a>
                </div>
            </div>
        </div>

        <!-- Inventory Report Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900">Inventory Report</h2>
                        <p class="text-sm text-gray-600">Track stock levels and alerts</p>
                    </div>
                </div>
                <a href="{{ route('reports.inventory') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                    View Report
                </a>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-sm">
                    <a href="{{ route('reports.export', 'inventory') }}" class="text-yellow-600 hover:text-yellow-900">Export to CSV</a>
                </div>
            </div>
        </div>

        <!-- Users Report Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-600 bg-opacity-75">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900">Users Report</h2>
                        <p class="text-sm text-gray-600">Review user performance</p>
                    </div>
                </div>
                <a href="{{ route('reports.users') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                    View Report
                </a>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-sm">
                    <a href="{{ route('reports.export', 'users') }}" class="text-purple-600 hover:text-purple-900">Export to CSV</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
