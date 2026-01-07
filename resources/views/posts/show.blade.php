@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- gambar -->
    <img src="{{ $post->image_path ? asset('storage/' . $post->image_path) : 'https://source.unsplash.com/random/600x400/?food,dish' }}" alt="{{ $post->title }}" class="w-full h-80 rounded-lg object-cover shadow-md">

    <!-- informasi makanan -->
    <div class="flex flex-col">
      <h1 class="text-3xl font-bold text-green-700 mb-2">{{ $post->title }}</h1>
      <p class="text-gray-600 mb-4">{{ $post->description ?? 'Tidak ada deskripsi.' }}</p>

      <div class="space-y-2 text-gray-800 border-t pt-4">
        <p><strong>Harga:</strong> <span class="text-green-600 font-semibold text-lg">{{ $post->is_free ? 'Gratis' : 'Rp ' . number_format($post->price, 0, ',', '.') }}</span></p>
        <p><strong>Stok:</strong> {{ $post->stock }} porsi</p>
        <p><strong>Kadaluarsa:</strong> <span class="text-red-600 font-medium">{{ $post->expires_at->isoFormat('dddd, D MMMM YYYY - HH:mm') }}</span></p>
        <p><strong>Lokasi:</strong> {{ $post->location }}</p>
        <p><strong>Penjual:</strong> {{ $post->user->name }}</p>
      </div>

      {{-- user action --}}
      <div class="mt-auto border-t pt-4">
        @auth
        @if(Auth::id() === $post->user_id)
        {{-- user adalah pemilik postingan --}}
        <p class="text-sm font-semibold mb-2 text-gray-700">Aksi Pemilik:</p>
        <div class="flex flex-wrap gap-3">
          <a href="{{ route('post.edit', $post->uuid) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Edit Postingan</a>
          <form action="{{ route('post.destroy', $post->uuid) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus postingan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
          </form>
        </div>
        @elseif($post->stock > 0)
        {{-- user BUKAN pemilik dan stok masih ada --}}
        <form action="{{ route('order.store') }}" method="POST">
          @csrf
          <input type="hidden" name="post_uuid" value="{{ $post->uuid }}">
          <div class="flex items-center gap-4">
            <div class="flex-grow">
              <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah:</label>
              <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $post->stock }}" class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
            </div>
            <button type="submit" class="self-end w-full bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">Pesan</button>
          </div>
        </form>
        @else
        {{-- jika stok habis --}}
        <p class="bg-gray-100 text-gray-600 font-semibold p-3 rounded-lg text-center">Stok sudah habis.</p>
        @endif
        @endauth
      </div>
    </div>
  </div>


  @auth
  @if(Auth::id() !== $post->user_id)
  <div class="mt-8 border-t pt-6">
    <h4 class="font-semibold text-lg text-gray-800 mb-3">Laporkan Postingan</h4>
    <form action="{{ route('report.store') }}" method="POST">
      @csrf
      <input type="hidden" name="post_uuid" value="{{ $post->uuid }}">
      <div class="space-y-2">
        <label class="flex items-center gap-2"><input type="radio" name="reason" value="expired" class="text-green-600"> Makanan basi</label>
        <label class="flex items-center gap-2"><input type="radio" name="reason" value="fake_photo" class="text-green-600"> Foto palsu</label>
        <label class="flex items-center gap-2"><input type="radio" name="reason" value="misleading" class="text-green-600"> Info menyesatkan</label>
        <label class="flex items-center gap-2"><input type="radio" name="reason" value="other" class="text-green-600"> Lainnya</label>
      </div>
      <textarea name="other_reason" placeholder="Jelaskan alasan lain jika memilih 'Lainnya'..." rows="2" class="w-full mt-2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-400"></textarea>
      <button type="submit" class="mt-2 bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 text-sm font-medium">Kirim Laporan</button>
    </form>
  </div>
  @endif
  @endauth
</div>
@endsection