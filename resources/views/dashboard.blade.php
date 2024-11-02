@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-semibold mb-6">Welcome to Dashboard</h1>

            <div class="bg-gray-100 rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ Auth::user()->avatar }}" alt="Profile Picture" class="w-16 h-16 rounded-full">
                    <div>
                        <h2 class="text-xl font-semibold">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Add your dashboard content here -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
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

                <!-- Test Product Section -->
                <div class="mt-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    This is a test environment. Use these credentials for eSewa sandbox:
                                    <br>eSewa ID: 9806800001
                                    <br>Password: Nepal@123
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Test Product Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="https://placehold.co/600x400/eef2ff/475569/png?text=Test+Product" alt="Test Product" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold mb-2">Test Product</h3>
                                <p class="text-gray-600 mb-4">This is a test product for learning eSewa integration.</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-blue-600">Rs. 100</span>
                                    <form action="https://uat.esewa.com.np/epay/main" method="POST">
                                        <input value="100" name="tAmt" type="hidden">
                                        <input value="90" name="amt" type="hidden">
                                        <input value="5" name="txAmt" type="hidden">
                                        <input value="2" name="psc" type="hidden">
                                        <input value="3" name="pdc" type="hidden">
                                        <input value="EPAYTEST" name="scd" type="hidden">
                                        <input value="TEST-{{ uniqid() }}" name="pid" type="hidden">
                                        <input value="{{ url('/esewa/success') }}" type="hidden" name="su">
                                        <input value="{{ url('/esewa/failure') }}" type="hidden" name="fu">
                                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                                            Test eSewa Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Information Card -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-4">Testing Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-700">Test Amount Breakdown:</h4>
                                    <ul class="ml-4 text-gray-600">
                                        <li>Total Amount: Rs. 100</li>
                                        <li>Product Price: Rs. 90</li>
                                        <li>Tax Amount: Rs. 5</li>
                                        <li>Service Charge: Rs. 2</li>
                                        <li>Delivery Charge: Rs. 3</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-700">What happens next:</h4>
                                    <ol class="ml-4 text-gray-600 list-decimal">
                                        <li>Click the "Test eSewa Payment" button</li>
                                        <li>You'll be redirected to eSewa's sandbox login page</li>
                                        <li>Use the test credentials provided above</li>
                                        <li>Complete the test payment</li>
                                        <li>You'll be redirected back to success/failure page</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection