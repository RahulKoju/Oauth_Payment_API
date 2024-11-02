<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Auth')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <a href="/" class="flex items-center py-4">
                        <span class="font-semibold text-gray-500 text-lg">Your App</span>
                    </a>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                    <div class="flex items-center space-x-4">
                        <img src="{{ Auth::user()->avatar }}" alt="avatar" class="w-8 h-8 rounded-full">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="py-2 px-4 bg-red-500 text-white rounded hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Login
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</body>

</html>