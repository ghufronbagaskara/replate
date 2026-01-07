@extends('layouts.app')

@section('title', 'Moderasi Postingan')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h1 class="text-3xl font-bold text-green-700 mb-6 border-b pb-4">Moderasi Postingan</h1>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">ID</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Judul</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Penjual</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($posts as $post)
        <tr class="hover:bg-gray-50">
          <td class="py-3 px-4 border-b">{{ $post->uuid }}</td>
          <td class="py-3 px-4 border-b">
            <a href="{{ route('post.show', $post->uuid) }}" target="_blank" class="text-blue-600 hover:underline">{{ $post->title }}</a>
          </td>
          <td class="py-3 px-4 border-b">{{ $post->user->name }}</td>
          <td class="py-3 px-4 border-b">
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $post->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
              {{ ucfirst($post->status) }}
            </span>
          </td>
          <td class="py-3 px-4 border-b">
            <form action="{{ route('admin.posts.destroy', $post->uuid) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus postingan ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors">Hapus Paksa</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-500">
            Tidak ada postingan untuk dimoderasi.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $posts->links() }}
  </div>
</div>
@endsection