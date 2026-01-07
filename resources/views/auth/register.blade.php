@extends('layouts.guest')

{{-- Memberitahu layout untuk menyembunyikan header --}}
@section('hide-header', true)

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-6xl m-4">
        <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Kolom Kiri -->
            <div class="w-full md:w-1/2 p-10 md:p-12 bg-green-600 text-white flex flex-col justify-center relative overflow-hidden">
                <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-green-500 rounded-full opacity-50"></div>
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-green-500 rounded-full opacity-50"></div>
                <img src="{{ asset('element.svg') }}" class="absolute bottom-0 left-0 w-full h-auto opacity-10" alt="Wave">

                <div class="mb-8 relative z-10">
                    <a href="/" class="flex items-center gap-3">
                        <img src="{{ asset('app-logo.svg') }}" alt="Logo RePlate" class="w-10 h-10">
                        <h1 class="text-3xl font-bold">rePlate</h1>
                    </a>
                </div>

                <div class="relative z-10">
                    <h2 class="text-5xl font-bold leading-tight mb-4">Bergabunglah<br>Dengan Kami!</h2>
                    <p class="text-green-100">Mulai selamatkan makanan, hemat uang, dan bantu sesama hari ini juga.</p>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="w-full md:w-1/2 p-10 md:p-12 flex items-center justify-center">
                <div class="w-full max-w-sm">
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">Buat Akun</h3>
                    <p class="text-gray-500 mb-8">Pendaftaran cepat dan mudah.</p>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" id="name" name="name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500" value="{{ old('name') }}" required placeholder="Nama Lengkap">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="email" id="email" name="email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500" value="{{ old('email') }}" required placeholder="Email">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="tel" id="whatsapp_number" name="whatsapp_number" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500" value="{{ old('whatsapp_number') }}" required placeholder="Nomor WhatsApp (62...)">
                            @error('whatsapp_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" id="password" name="password" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500" required placeholder="Password">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-6">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500" required placeholder="Konfirmasi Password">
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-lg">Daftar</button>
                        <p class="mt-8 text-center text-sm text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Masuk</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection