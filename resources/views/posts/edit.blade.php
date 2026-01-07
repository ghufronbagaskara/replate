@extends('layouts.app')

@section('title', 'Edit Makanan')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h2 class="text-2xl font-semibold text-green-700 mb-6">Edit: {{ $post->title }}</h2>

  <form action="{{ route('post.update', $post->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
      @if($post->image_path)
      <img src="{{ asset('storage/' . $post->image_path) }}" alt="Gambar Saat Ini" class="mt-2 rounded-lg max-w-xs">
      @else
      <p class="text-sm text-gray-500 mt-2 italic">(Tidak ada gambar)</p>
      @endif
    </div>

    <div class="mb-4">
      <label for="image" class="block text-sm font-medium text-gray-700">Ganti Gambar (Opsional)</label>
      <input type="file" id="image" name="image" accept="image/*" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
      @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
      <label for="title" class="block text-sm font-medium text-gray-700">Nama Makanan</label>
      <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400" required>
      @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
      <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
      <textarea id="description" name="description" rows="3" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">{{ old('description', $post->description) }}</textarea>
      @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="number" id="price" name="price" value="{{ old('price', $post->price) }}" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400" max="35000" required>
        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>
      <div class="mb-4">
        <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $post->stock) }}" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400" min="0" required>
        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="mb-4">
      <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
      <input type="text" id="location" name="location" value="{{ old('location', $post->location) }}" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400" required>
      @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
      <label for="expires_at" class="block text-sm font-medium text-gray-700">Tanggal & Jam Kadaluarsa</label>
      <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', $post->expires_at->format('Y-m-d\TH:i')) }}" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400" required>
      @error('expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mt-6">
      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>
@endsection