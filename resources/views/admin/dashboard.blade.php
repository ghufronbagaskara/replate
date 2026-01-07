@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Admin Dashboard</h1>

    <!-- Statistik Sistem -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h4 class="text-gray-500 text-sm font-medium">Total Pengguna</h4>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h4 class="text-gray-500 text-sm font-medium">Total Postingan</h4>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_posts'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-teal-500">
            <h4 class="text-gray-500 text-sm font-medium">Postingan Aktif</h4>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['active_posts'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <h4 class="text-gray-500 text-sm font-medium">Laporan Masuk</h4>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_reports'] }}</p>
        </div>
    </div>

    <!-- Menu Navigasi Admin -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Menu Navigasi</h3>
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('admin.posts.index') }}" class="flex-1 text-center bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                <span class="font-medium text-gray-700">Moderasi Postingan</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex-1 text-center bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                <span class="font-medium text-gray-700">Moderasi User</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex-1 text-center bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                <span class="font-medium text-gray-700">Lihat Laporan</span>
            </a>
        </div>
    </div>
</div>
@endsection