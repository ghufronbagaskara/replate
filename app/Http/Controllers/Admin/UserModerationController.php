<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserModerationController extends Controller
{
  public function index()
  {
    $users = User::latest()->paginate(20);
    return view('admin.users.index', compact('users'));
  }

  // user verification
  public function verify($uuid)
  {
    $user = User::where('uuid', $uuid)->firstOrFail();
    $user->update(['is_verified' => true]);
    return back()->with('success', "Akun {$user->name} berhasil diverifikasi.");
  }

  // suspend user with flag
  public function suspend($uuid)
  {
    $user = User::where('uuid', $uuid)->firstOrFail();
    $user->update(['is_suspended' => true]);
    return back()->with('success', "Akun {$user->name} berhasil ditangguhkan.");
  }
}
