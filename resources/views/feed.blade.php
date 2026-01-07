@extends('layouts.app')

@section('title', 'Feed Makanan')

@section('content')
<div class="max-w-7xl mx-auto">

  {{-- ringkasan pesanan --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    @if($myOrdersCount > 0)
    <div class="bg-green-50 border border-green-200 p-4 rounded-lg shadow-sm flex justify-between items-center hover:bg-green-100 transition">
      <div>
        <p class="text-gray-800 font-medium">Cek Pesananmu</p>
        <p class="text-sm text-gray-600">{{ $myOrdersCount }} pesanan sedang berlangsung</p>
      </div>
      <a href="{{ route('order.index') }}" class="inline-block bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700 transition">Lihat</a>
    </div>
    @endif

    @if($incomingOrdersCount > 0)
    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg shadow-sm flex justify-between items-center hover:bg-yellow-100 transition">
      <div>
        <p class="text-gray-800 font-medium">{{ $incomingOrdersCount }} Pesanan Masuk!</p>
        <p class="text-sm text-gray-600">Segera periksa dan konfirmasi</p>
      </div>
      <a href="{{ route('order.index') }}" class="inline-block bg-yellow-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Lihat</a>
    </div>
    @endif
  </div>

  {{-- title dan filter --}}
  <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold text-green-700 mb-4">Jelajahi Makanan</h2>
    <form action="{{ route('feed') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
      <input type="text" name="location" placeholder="Cari berdasarkan kota..." value="{{ request('location') }}" class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
      <select name="status" class="w-full md:w-auto px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
        <option value="">Semua Status</option>
        <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Gratis</option>
      </select>
      <select name="price" class="w-full md:w-auto px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
        <option value="">Semua Harga</option>
        <option value="under_10k" {{ request('price') == 'under_10k' ? 'selected' : '' }}>≤ Rp 10.000</option>
      </select>
      <button type="submit" class="w-full md:w-auto bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Filter</button>
      <a href="{{ route('feed') }}" class="w-full md:w-auto text-center text-gray-600 hover:underline py-2">Reset</a>
    </form>
  </div>

  {{-- feed makanan --}}
  @if($foodPosts->isEmpty())
  <div class="text-center py-10 bg-white rounded-lg shadow-sm">
    <p class="text-gray-500">Tidak ada makanan yang tersedia sesuai filter Anda.</p>
  </div>
  @else
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($foodPosts as $post)
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col">
      <img src="{{ $post->image_path ? asset('storage/' . $post->image_path) : 'https://source.unsplash.com/random/400x200/?food,meal' }}"
        alt="{{ $post->title }}"
        class="w-full h-48 object-cover">
      <div class="p-4 flex flex-col flex-grow">
        <div class="flex justify-between items-start mb-2">
          <h3 class="font-bold text-lg text-gray-800">{{ $post->title }}</h3>
          <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full whitespace-nowrap">Stok: {{ $post->stock }}</span>
        </div>
        <p class="text-gray-600 text-sm mb-3 flex-grow">{{ Str::limit($post->description, 100) }}</p>

        {{-- location adding --}}
        <div class="text-xs text-gray-500 mb-3 space-y-1">
          <p class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $post->location }}
          </p>
          <p class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Penjual: {{ $post->user->name }}
          </p>
        </div>

        <div class="flex justify-between items-center mt-auto">
          <p class="text-green-600 font-semibold text-lg">
            {{ $post->is_free ? 'Gratis' : 'Rp ' . number_format($post->price, 0, ',', '.') }}
          </p>
          <a href="{{ route('post.show', $post->uuid) }}" class="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            Lihat Detail
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- pagination --}}
  <div class="mt-8">
    {{ $foodPosts->appends(request()->query())->links() }}
  </div>
  @endif
</div>
@endsection