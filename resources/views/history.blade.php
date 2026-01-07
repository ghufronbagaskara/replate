@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-green-700 mb-6">Riwayat Transaksi</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- as buyer -->
    <div class="bg-gray-50 p-4 rounded-lg">
      <h3 class="font-semibold text-gray-800 mb-3 border-b pb-2">Sebagai Pembeli</h3>
      @if($purchaseHistory->isEmpty())
      <p class="text-sm text-gray-500">Anda belum memiliki riwayat pembelian.</p>
      @else
      <ul class="space-y-4 text-sm">
        @foreach($purchaseHistory as $purchase)
        <li class="border-b pb-3">
          <p class="font-bold">{{ $purchase->foodPost->title }} - {{ $purchase->quantity }} porsi</p>
          <p class="text-gray-600">Penjual: {{ $purchase->foodPost->user->name }}</p>
          @php
          $statusColor = [
          'completed' => 'text-green-700', 'confirmed' => 'text-blue-600',
          'cancelled' => 'text-red-600', 'rejected' => 'text-red-600',
          'pending' => 'text-yellow-600'
          ];
          @endphp
          <p class="{{ $statusColor[$purchase->status] ?? 'text-gray-600' }}">
            {{ ucfirst($purchase->status) }} - {{ $purchase->updated_at->format('d M Y') }}
          </p>

          @if($purchase->status == 'completed' && !$purchase->review)
          <a href="{{ route('review.create', $purchase->uuid) }}" class="text-sm text-blue-600 hover:underline mt-1 inline-block">Tulis Review</a>
          @elseif($purchase->review)
          <p class="text-xs text-gray-500 mt-1">Anda memberi rating: {{ $purchase->review->rating }}/5</p>
          @endif
        </li>
        @endforeach
      </ul>
      @endif
    </div>

    <!-- as a seller -->
    <div class="bg-gray-50 p-4 rounded-lg">
      <h3 class="font-semibold text-gray-800 mb-3 border-b pb-2">Sebagai Penjual</h3>
      @if($salesHistory->isEmpty())
      <p class="text-sm text-gray-500">Anda belum memiliki riwayat penjualan.</p>
      @else
      <ul class="space-y-4 text-sm">
        @foreach($salesHistory as $sale)
        <li class="border-b pb-3">
          <p><strong>{{ $sale->buyer->name }}</strong> membeli {{ $sale->quantity }} <strong>{{ $sale->foodPost->title }}</strong></p>
          <p class="text-green-700">Selesai - {{ $sale->updated_at->format('d M Y') }}</p>
        </li>
        @endforeach
      </ul>
      @endif
    </div>
  </div>
</div>
@endsection