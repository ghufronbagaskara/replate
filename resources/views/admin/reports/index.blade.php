@extends('layouts.app')

@section('title', 'Laporan Masuk')

@section('content')


<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-green-700 mb-6 border-b pb-4">Daftar Laporan Masuk</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">ID</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Postingan Dilaporkan</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Pelapor</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Alasan</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b align-top">{{ $report->id }}</td>
                    <td class="py-3 px-4 border-b align-top">
                        <a href="{{ route('post.show', $report->post_id) }}" target="_blank" class="text-blue-600 hover:underline">{{ $report->foodPost->title }}</a>
                    </td>
                    <td class="py-3 px-4 border-b align-top">{{ $report->reporter->name }}</td>
                    <td class="py-3 px-4 border-b align-top">
                        @php
                        $reasonText = ucfirst(str_replace('_', ' ', $report->reason));
                        $reasonClass = [
                        'expired' => 'bg-red-100 text-red-800',
                        'fake_photo' => 'bg-yellow-100 text-yellow-800',
                        'misleading' => 'bg-yellow-100 text-yellow-800',
                        'other' => 'bg-gray-100 text-gray-800',
                        ][$report->reason] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $reasonClass }}">
                            {{ $reasonText }}
                        </span>
                        @if($report->other_reason)
                        <p class="text-xs text-gray-500 mt-2 italic">"{{ $report->other_reason }}"</p>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b align-top">
                        <form action="{{ route('admin.posts.destroy', $report->post_id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus postingan ini berdasarkan laporan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors">Hapus Postingan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        Tidak ada laporan yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</div>
@endsection