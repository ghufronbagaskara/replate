@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-xl mx-auto text-center">
    <h1 class="text-3xl font-bold text-green-700">Detail Pesanan Anda</h1>
    <p class="text-gray-600 mt-2">Gunakan halaman ini untuk melacak status pesanan Anda.</p>
</div>

<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-6">

    <div class="mb-6">
        @php
        $statusText = '';
        $statusClass = '';
        switch ($transaction->status) {
        case 'pending':
        $statusText = 'Menunggu Konfirmasi Penjual';
        $statusClass = 'bg-yellow-100 text-yellow-800';
        break;
        case 'confirmed':
        $statusText = 'Pesanan Dikonfirmasi, Siap Diambil';
        $statusClass = 'bg-blue-100 text-blue-800';
        break;
        case 'completed':
        $statusText = 'Pesanan Selesai';
        $statusClass = 'bg-green-100 text-green-800';
        break;
        case 'rejected':
        case 'cancelled':
        $statusText = 'Pesanan Dibatalkan';
        $statusClass = 'bg-red-100 text-red-800';
        break;
        }
        @endphp
        <div class="p-4 rounded-lg text-center {{ $statusClass }}">
            <p class="font-semibold">{{ $statusText }}</p>
        </div>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-lg font-semibold text-gray-800">Ringkasan Pesanan</h3>
        <div class="mt-2 space-y-1 text-gray-700">
            <p><strong>Makanan:</strong> {{ $transaction->foodPost->title }}</p>
            <p><strong>Jumlah:</strong> {{ $transaction->quantity }} porsi</p>
            <p><strong>Total Harga:</strong> <span class="font-bold">Rp {{ number_format($transaction->foodPost->price * $transaction->quantity, 0, ',', '.') }}</span></p>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-lg font-semibold text-gray-800">Informasi Penjual</h3>
        <div class="mt-2 space-y-1 text-gray-700">
            <p><strong>Nama:</strong> {{ $transaction->foodPost->user->name }}</p>
            <p><strong>Alamat Pengambilan:</strong> {{ $transaction->foodPost->location }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-col md:flex-row gap-3">
        @php
        $seller_whatsapp = $transaction->foodPost->user->whatsapp_number;
        @endphp

        @if($seller_whatsapp)
        <a href="https://wa.me/{{ $seller_whatsapp }}" target="_blank" rel="noopener noreferrer" class="w-full text-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            Chat Penjual di WhatsApp
        </a>
        @endif

        <a href="https://maps.google.com/?q={{ urlencode($transaction->foodPost->location) }}" target="_blank" rel="noopener noreferrer" class="w-full text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            Buka di Google Maps
        </a>
    </div>
</div>

<div class="max-w-xl mx-auto text-center mt-6">
    <a href="{{ route('order.index') }}" class="text-green-600 hover:underline">‹ Kembali ke Kelola Pesanan</a>
</div>
@endsection