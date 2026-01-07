@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')


<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h1 class="text-3xl font-bold text-green-700 mb-6 border-b pb-4">Kelola Pesanan Anda</h1>

  <div class="mb-10">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Masuk (Sebagai Penjual)</h2>
    @if($incomingOrders->isEmpty())
    <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
      Tidak ada pesanan yang masuk saat ini.
    </div>
    @else
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Makanan</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Pemesan</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($incomingOrders as $order)
          <tr class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b">{{ $order->foodPost->title }} ({{ $order->quantity }}x)</td>
            <td class="py-2 px-4 border-b">{{ $order->buyer->name }}</td>
            <td class="py-2 px-4 border-b font-medium">{{ ucfirst($order->status) }}</td>
            <td class="py-2 px-4 border-b">
              <div class="flex gap-2">
                @if($order->status == 'pending')
                <form action="{{ route('order.confirm', $order->uuid) }}" method="POST"> @csrf @method('PATCH')
                  <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Konfirmasi</button>
                </form>
                <form action="{{ route('order.reject', $order->uuid) }}" method="POST"> @csrf @method('PATCH')
                  <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Tolak</button>
                </form>
                @elseif($order->status == 'confirmed')
                <form action="{{ route('order.complete', $order->uuid) }}" method="POST"> @csrf @method('PATCH')
                  <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Selesaikan</button>
                </form>
                @else
                <span class="text-xs text-gray-400">-</span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>


  <div>
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Saya (Sebagai Pembeli)</h2>
    @if($myOrders->isEmpty())
    <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
      Anda belum melakukan pemesanan apapun.
    </div>
    @else
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Makanan</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Penjual</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($myOrders as $order)
          <tr class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b"><a href="{{ route('post.show', $order->foodPost->uuid) }}" class="text-blue-600 hover:underline">{{ $order->foodPost->title }}</a></td>
            <td class="py-2 px-4 border-b">{{ $order->foodPost->user->name }}</td>
            <td class="py-2 px-4 border-b font-medium">{{ ucfirst($order->status) }}</td>
            <td class="py-2 px-4 border-b">
              <div class="flex items-center gap-2">
                <a href="{{ route('order.receipt', $order->uuid) }}" class="text-xs bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600">Lihat Receipt</a>

                @if($order->status == 'pending')
                <form action="{{ route('order.cancel', $order->uuid) }}" method="POST"> @csrf @method('PATCH')
                  <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Batalkan</button>
                </form>
                @elseif($order->status == 'completed' && !$order->review)
                <a href="{{ route('review.create', $order->uuid) }}" class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Beri Review</a>
                @elseif($order->status == 'completed' && $order->review)
                <span class="text-xs text-green-600">Direview</span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</div>
@endsection