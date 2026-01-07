@extends('layouts.app')

@section('title', 'Moderasi User')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h1 class="text-3xl font-bold text-green-700 mb-6 border-b pb-4">Moderasi Pengguna</h1>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">ID</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Nama</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Email & WA</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
          <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr class="hover:bg-gray-50">
          <td class="py-3 px-4 border-b align-top">{{ $user->uuid }}</td>
          <td class="py-3 px-4 border-b align-top">{{ $user->name }}</td>
          <td class="py-3 px-4 border-b align-top">
            <p>{{ $user->email }}</p>
            <p class="text-xs text-gray-500">{{ $user->whatsapp_number }}</p>
          </td>
          <td class="py-3 px-4 border-b align-top">
            <div class="flex flex-col gap-1">
              <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_verified ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $user->is_verified ? 'Verified' : 'Not Verified' }}
              </span>
              @if($user->is_admin)
              <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                Admin
              </span>
              @endif
            </div>
          </td>
          <td class="py-3 px-4 border-b align-top">
            <div class="flex gap-2">
              @if(!$user->is_verified)
              <form action="{{ route('admin.users.verify', $user->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Verifikasi</button>
              </form>
              @endif
              <form action="{{ route('admin.users.suspend', $user->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Suspend</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-500">
            Tidak ada pengguna terdaftar.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $users->links() }}
  </div>
</div>
@endsection