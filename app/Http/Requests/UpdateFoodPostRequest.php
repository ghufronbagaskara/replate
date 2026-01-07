<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFoodPostRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:150', 'min:5'],
      'description' => ['nullable', 'string', 'max:1000'],
      'price' => ['required', 'numeric', 'min:0', 'max:35000'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
      'location' => ['nullable', 'string', 'max:255'],
      'expires_at' => ['required', 'date'],
      'stock' => ['required', 'integer', 'min:0', 'max:1000'],
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Judul wajib diisi.',
      'title.min' => 'Judul minimal 5 karakter.',
      'price.required' => 'Harga wajib diisi.',
      'price.max' => 'Harga maksimal Rp 35.000.',
      'image.image' => 'File harus berupa gambar.',
      'image.max' => 'Ukuran gambar maksimal 2MB.',
      'expires_at.required' => 'Tanggal kadaluarsa wajib diisi.',
      'stock.required' => 'Stok wajib diisi.',
    ];
  }
}
