@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h2 class="text-2xl font-bold text-green-700 mb-6 border-b pb-4">Profil Saya</h2>

  <!-- informasi akun -->
  <div class="mb-8">
    <p class="text-gray-700 mb-1"><strong>Nama:</strong> {{ $user->name }}</p>
    <p class="text-gray-700 mb-1"><strong>Email:</strong> {{ $user->email }}</p>
    <p class="text-gray-700 mb-1"><strong>No. WhatsApp:</strong> {{ $user->whatsapp_number ?? '-' }}</p>
    <p class="text-gray-700"><strong>Bergabung Sejak:</strong> {{ $user->created_at->isoFormat('D MMMM YYYY') }}</p>
  </div>

  <!-- postingan -->
  <h3 class="text-xl font-semibold text-gray-800 mb-4">Makanan yang Pernah Diposting</h3>
  @if($user->foodPosts->isEmpty())
  <p class="text-gray-500 bg-gray-50 p-4 rounded-lg">Anda belum pernah memposting makanan.</p>
  @else
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($user->foodPosts as $post)
    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
      <h4 class="font-bold text-lg text-green-700">{{ $post->title }}</h4>
      <p class="text-gray-600 text-sm mb-3">Status: <span class="font-medium">{{ ucfirst($post->status) }}</span> | Stok: <span class="font-medium">{{ $post->stock }}</span></p>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('post.edit', $post->uuid) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
        <form action="{{ route('post.destroy', $post->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini secara permanen?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
@endsection