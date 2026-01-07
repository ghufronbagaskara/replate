<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\User;

class PageController extends Controller
{
  // Menampilkan riwayat transaksi user
  public function history()
  {
    $user = Auth::user();

    $purchaseHistory = Transaction::with('foodPost.user', 'review')
      ->where('buyer_id', $user->id)
      ->latest()
      ->get();

    $salesHistory = Transaction::with('buyer', 'foodPost')
      ->whereHas('foodPost', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      })
      ->where('status', 'completed')
      ->latest()
      ->get();
    return view('history', compact('purchaseHistory', 'salesHistory'));
  }

  // Menampilkan halaman profil user
  public function profile()
  {
    $user = User::with('foodPosts')->find(Auth::id());
    return view('profile', compact('user'));
  }

  // Menampilkan halaman berita/edukasi
  public function news()
  {
    return response()->json(['message' => 'Ini adalah halaman edukasi/berita.']);
  }
}
