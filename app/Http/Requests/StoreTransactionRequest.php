<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTransactionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'post_uuid' => ['required', 'string', 'exists:food_posts,uuid'],
      'quantity' => ['required', 'integer', 'min:1', 'max:100'],
    ];
  }

  public function messages(): array
  {
    return [
      'post_uuid.required' => 'UUID postingan wajib diisi.',
      'post_uuid.exists' => 'Postingan tidak ditemukan.',
      'quantity.required' => 'Jumlah pesanan wajib diisi.',
      'quantity.min' => 'Jumlah pesanan minimal 1.',
      'quantity.max' => 'Jumlah pesanan maksimal 100.',
    ];
  }
}
