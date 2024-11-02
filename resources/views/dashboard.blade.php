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
            </div>
        </div>
    </div>
</div>
@endsection