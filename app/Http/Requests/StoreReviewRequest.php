<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReviewRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'transaction_uuid' => ['required', 'string', 'exists:transactions,uuid'],
      'rating' => ['required', 'integer', 'min:1', 'max:5'],
      'review' => ['nullable', 'string', 'max:500'],
    ];
  }

  public function messages(): array
  {
    return [
      'transaction_uuid.required' => 'UUID transaksi wajib diisi.',
      'transaction_uuid.exists' => 'Transaksi tidak ditemukan.',
      'rating.required' => 'Rating wajib diisi.',
      'rating.min' => 'Rating minimal 1.',
      'rating.max' => 'Rating maksimal 5.',
      'review.max' => 'Review maksimal 500 karakter.',
    ];
  }
}
