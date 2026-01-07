<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BusinessException;

class AuthService
{
  public function __construct(
    protected UserRepository $userRepository
  ) {}

  public function register(array $data): User
  {
    try {
      return DB::transaction(function () use ($data) {
        $user = $this->userRepository->create([
          'name' => $data['name'],
          'email' => $data['email'],
          'whatsapp_number' => $data['whatsapp_number'],
          'password' => Hash::make($data['password']),
          'is_verified' => false,
          'is_admin' => false,
        ]);

        Log::info('User registered', ['user_uuid' => $user->uuid, 'email' => $user->email]);

        return $user;
      });
    } catch (\Exception $e) {
      Log::error('User registration failed', [
        'email' => $data['email'],
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Registrasi gagal. Silakan coba lagi.');
    }
  }

  public function attemptLogin(array $credentials): bool
  {
    $user = $this->userRepository->findByEmail($credentials['email']);

    if (!$user) {
      Log::warning('Login attempt with non-existent email', ['email' => $credentials['email']]);
      return false;
    }

    if ($user->is_suspended) {
      Log::warning('Login attempt by suspended user', ['user_uuid' => $user->uuid]);
      throw new BusinessException('Akun Anda telah ditangguhkan: ' . $user->suspension_reason);
    }

    return Auth::attempt($credentials);
  }

  public function logout(): void
  {
    $userId = Auth::id();
    Auth::logout();

    Log::info('User logged out', ['user_id' => $userId]);
  }
}
