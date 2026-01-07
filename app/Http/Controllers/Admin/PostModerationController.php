<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FoodPostRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostModerationController extends Controller
{
  public function __construct(
    protected FoodPostRepository $repository
  ) {}

  public function index()
  {
    $posts = \App\Models\FoodPost::with('user')->latest()->paginate(20);
    return view('admin.posts.index', compact('posts'));
  }

  public function destroy($uuid)
  {
    try {
      $post = $this->repository->findByUuid($uuid);

      if (!$post) {
        return back()->with('error', 'Postingan tidak ditemukan.');
      }

      // Soft takedown (mengubah status)
      $post->update(['status' => 'taken_down']);

      $adminUser = Auth::user();
      Log::info('Admin took down post', [
        'post_uuid' => $uuid,
        'admin_uuid' => $adminUser ? $adminUser->uuid : null
      ]);

      return back()->with('success', 'Postingan berhasil dihapus oleh admin.');
    } catch (\Exception $e) {
      Log::error('Admin post takedown failed', [
        'post_uuid' => $uuid,
        'error' => $e->getMessage()
      ]);

      return back()->with('error', 'Gagal menghapus postingan.');
    }
  }
}
