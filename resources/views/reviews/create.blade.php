@extends('layouts.app')

@section('title', 'Beri Review')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
  <h1 class="text-2xl font-bold text-green-700 mb-2">Beri Ulasan untuk:</h1>
  <p class="text-lg text-gray-800 mb-6">{{ $transaction->foodPost->title }}</p>

  <form action="{{ route('review.store') }}" method="POST">
    @csrf
    <input type="hidden" name="transaction_uuid" value="{{ $transaction->uuid }}">

    <div class="mb-4">
      <label for="rating" class="block text-sm font-medium text-gray-700">Rating (1-5)</label>
      <select name="rating" id="rating" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400 @error('rating') border-red-500 @enderror" required>
        <option value="" disabled selected>Pilih rating...</option>
        <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 - Sangat Baik</option>
        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 - Baik</option>
        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 - Cukup</option>
        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 - Kurang</option>
        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 - Buruk</option>
      </select>
      @error('rating')
      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="mb-6">
      <label for="review" class="block text-sm font-medium text-gray-700">Ulasan (Opsional)</label>
      <textarea name="review" id="review" rows="4" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400 @error('review') border-red-500 @enderror" placeholder="Bagaimana pengalamanmu dengan makanan ini?">{{ old('review') }}</textarea>
      @error('review')
      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">Kirim Review</button>
  </form>
</div>
@endsection