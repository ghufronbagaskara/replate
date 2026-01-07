<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:100', 'min:3'],
      'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
      'whatsapp_number' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
      'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'Nama wajib diisi.',
      'name.min' => 'Nama minimal 3 karakter.',
      'email.required' => 'Email wajib diisi.',
      'email.email' => 'Format email tidak valid.',
      'email.unique' => 'Email sudah terdaftar.',
      'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
      'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid.',
      'password.required' => 'Password wajib diisi.',
      'password.min' => 'Password minimal 8 karakter.',
      'password.confirmed' => 'Konfirmasi password tidak cocok.',
      'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
    ];
  }
}
