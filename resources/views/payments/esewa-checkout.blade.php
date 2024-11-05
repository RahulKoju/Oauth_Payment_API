<!-- resources/views/payments/esewa-checkout.blade.php -->
@extends('layouts.app')

@section('title', 'eSewa Checkout')

@section('content')
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
            <div class="max-w-md mx-auto">
                <div class="flex items-center space-x-5">
                    <svg class="h-14 w-14 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div class="block font-semibold text-xl text-gray-700">
                        <h2>Checkout Summary</h2>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                        <div class="flex justify-between">
                            <div>Product Amount:</div>
                            <div class="font-medium">Rs. {{ number_format($paymentData['amount'], 2) }}</div>
                        </div>
                        <div class="flex justify-between">
                            <div>Delivery Charge:</div>
                            <div class="font-medium">Rs. {{ number_format($paymentData['product_delivery_charge'], 2) }}</div>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <div>Total Amount:</div>
                            <div>Rs. {{ number_format($paymentData['total_amount'], 2) }}</div>
                        </div>
                    </div>

                    <div class="pt-6 text-base leading-6 font-bold sm:text-lg sm:leading-7">
                        <form id="esewa-payment-form" action="{{ $paymentUrl }}" method="POST">
                            @foreach($paymentData as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Proceed to eSewa Payment</span>
                            </button>
                        </form>
                    </div>

                    <div class="pt-6 text-sm text-gray-600">
                        <p class="mb-2">Test Environment Credentials:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>eSewa ID: 9806800001</li>
                            <li>Password: Nepal@123</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-xl flex items-center space-x-4">
        <svg class="animate-spin h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-700">Redirecting to eSewa...</span>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('esewa-payment-form');
        const loadingOverlay = document.getElementById('loading-overlay');

        form.addEventListener('submit', function() {
            loadingOverlay.classList.remove('hidden');
        });
    });
</script>
@endpush
@endsection