<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Selamat Datang') - RePlate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen antialiased">

    <header class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('feed') }}" class="flex items-center gap-0.5">
                <img src="{{ asset('app-logo.svg') }}" alt="Logo RePlate" class="w-8 h-8">
                <span class="text-2xl font-bold text-green-700">rePlate</span>
            </a>
            <nav class="space-x-4 flex items-center">
                @auth
                <a href="{{ route('post.create') }}" class="text-green-700 font-semibold hover:underline">Post Makanan</a>
                <a href="{{ route('order.index') }}" class="text-gray-600 hover:underline">Kelola Pesanan</a>
                <a href="{{ route('history') }}" class="text-gray-600 hover:underline">Riwayat</a>
                <a href="{{ route('profile') }}" class="text-gray-600 hover:underline">Profil</a>
                @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-red-600 hover:underline">Admin</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Login</a>
                <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif
        @if ($errors->any())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md" role="alert">
            <strong>Perhatian:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

</body>

</html>