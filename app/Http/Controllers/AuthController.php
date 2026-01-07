<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
  public function __construct(
    protected AuthService $authService
  ) {}

  public function showRegistrationForm()
  {
    return view('auth.register');
  }

  public function register(RegisterRequest $request)
  {
    try {
      $this->authService->register($request->validated());

      return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(LoginRequest $request)
  {
    try {
      if ($this->authService->attemptLogin($request->validated())) {
        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
          return redirect()->intended('/admin');
        }

        return redirect()->intended('/feed');
      }

      return back()->withErrors([
        'email' => 'Email atau password yang Anda masukkan salah.',
      ])->onlyInput('email');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->onlyInput('email');
    }
  }

  public function logout(Request $request)
  {
    $this->authService->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }
}
