@extends('layouts.guest')

{{-- Memberitahu layout untuk menyembunyikan header --}}
@section('hide-header', true)

@section('title', 'Login')

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
          <h2 class="text-5xl font-bold leading-tight mb-4">REPLATE To<br>The Rescue!</h2>
          <p class="text-green-100">Save your tummy, save your money, and save your foody. So grab your seat and get some treats!</p>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="w-full md:w-1/2 p-10 md:p-12 flex items-center justify-center">
        <div class="w-full max-w-sm">
          <h3 class="text-3xl font-bold text-gray-800 mb-2">Welcome!</h3>
          <p class="text-gray-500 mb-8">Login untuk melanjutkan.</p>

          <form action="{{ route('login') }}" method="POST">
            @csrf

            @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              {{ session('error') }}
            </div>
            @endif

            <div class="mb-4">
              <label for="email" class="sr-only">Email</label>
              <input type="email" id="email" name="email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all" placeholder="Email" value="{{ old('email') }}" required>
              @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
              <label for="password" class="sr-only">Password</label>
              <input type="password" id="password" name="password" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all" placeholder="Password" required>
              @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6 text-right">
              <a href="#" class="text-sm text-green-600 hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-lg">Masuk</button>

            <p class="mt-8 text-center text-sm text-gray-600">
              Belum punya akun?
              <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Daftar sekarang</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection