@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-semibold mb-6">Welcome to Dashboard</h1>

            <!-- User Profile Section -->
            <div class="bg-gray-100 rounded-lg p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <img src="{{ url(Auth::user()->avatar) }}" alt="Profile Picture" class="w-16 h-16 rounded-full">
                    <div>
                        <h2 class="text-xl font-semibold">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            <!-- Add your dashboard content here -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">Profile Status</h3>
                    <p class="text-green-600">Verified</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">Last Login</h3>
                    <p>{{ Auth::user()->updated_at->diffForHumans() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">Account Type</h3>
                    <p>Google Account</p>
                </div>
            </div>

            <!-- eSewa Payment Section -->
            <div class="space-y-6">
                <!-- Test Environment Notice -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Test Environment Credentials:</strong><br>
                                eSewa ID: 9806800001<br>
                                Password: Nepal@123
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                    ['name' => 'Basic Package', 'price' => 10, 'delivery' => 1, 'tax_amount' => 1, 'service_charge' => 2],
                    ['name' => 'Standard Package', 'price' => 20, 'delivery' => 2, 'tax_amount' => 2, 'service_charge' => 4],
                    ['name' => 'Premium Package', 'price' => 50, 'delivery' => 4, 'tax_amount' => 3, 'service_charge' => 6],
                    ] as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative pb-48 overflow-hidden">
                            <img class="absolute inset-0 h-full w-full object-cover" src="https://placehold.co/600x400/eef2ff/475569/png?text={{ urlencode($product['name']) }}" alt="{{ $product['name'] }}">
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $product['name'] }}</h3>
                                <span class="text-2xl font-bold text-blue-600">Rs. {{ $product['price'] }}</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-4">Delivery Charge: Rs. {{ $product['delivery'] }}</p>

                            <form action="{{ route('esewa.checkout') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $product['price'] }}">
                                <input type="hidden" name="product_delivery_charge" value="{{ $product['delivery'] }}">
                                <input type="hidden" name="tax_amount" value="{{ $product['tax_amount'] }}">
                                <input type="hidden" name="product_service_charge" value="{{ $product['service_charge'] }}">

                                <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Pay with eSewa</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Transaction Status Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Recent Transactions</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Add your transaction records here -->
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No transactions yet
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl flex items-center space-x-4">
        <svg class="animate-spin h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-700">Processing payment...</span>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading modal when form is submitted
        const forms = document.querySelectorAll('form');
        const loadingModal = document.getElementById('loading-modal');

        forms.forEach(form => {
            form.addEventListener('submit', function() {
                loadingModal.classList.remove('hidden');
            });
        });
    });
</script>
@endpush
@endsection