<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReportRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'post_uuid' => ['required', 'string', 'exists:food_posts,uuid'],
      'reason' => ['required', 'in:expired,fake_photo,misleading,other'],
      'other_reason' => ['required_if:reason,other', 'nullable', 'string', 'max:500'],
    ];
  }

  public function messages(): array
  {
    return [
      'post_uuid.required' => 'UUID postingan wajib diisi.',
      'post_uuid.exists' => 'Postingan tidak ditemukan.',
      'reason.required' => 'Alasan laporan wajib diisi.',
      'reason.in' => 'Alasan laporan tidak valid.',
      'other_reason.required_if' => 'Alasan lainnya wajib diisi.',
      'other_reason.max' => 'Alasan lainnya maksimal 500 karakter.',
    ];
  }
}
