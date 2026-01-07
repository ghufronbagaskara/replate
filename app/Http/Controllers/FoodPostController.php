<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\FoodPostService;
use App\Services\TransactionService;
use App\Http\Requests\StoreFoodPostRequest;
use App\Http\Requests\UpdateFoodPostRequest;
use App\Repositories\TransactionRepository;

class FoodPostController extends Controller
{
  public function __construct(
    protected FoodPostService $foodPostService,
    protected TransactionRepository $transactionRepository
  ) {}

  public function index(Request $request)
  {
    $filters = $request->only(['status', 'price', 'location']);
    $foodPosts = $this->foodPostService->getAvailablePosts($filters, 9);

    $user = Auth::user();
    $myOrdersCount = 0;
    $incomingOrdersCount = 0;

    if ($user) {
      $myOrdersCount = $this->transactionRepository->getPendingOrdersCount($user->id);
      $incomingOrdersCount = $this->transactionRepository->getIncomingPendingOrdersCount($user->id);
    }

    return view('feed', compact('foodPosts', 'myOrdersCount', 'incomingOrdersCount'));
  }

  public function create()
  {
    return view('posts.create');
  }

  public function store(StoreFoodPostRequest $request)
  {
    try {
      $this->foodPostService->createPost($request->validated(), Auth::id());

      return redirect()->route('feed')->with('success', 'Makanan berhasil diposting!');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }

  public function show($id)
  {
    try {
      $post = $this->foodPostService->getPostByUuid($id);
      return view('posts.show', compact('post'));
    } catch (\Exception $e) {
      return redirect()->route('feed')->with('error', $e->getMessage());
    }
  }

  public function edit($id)
  {
    try {
      $post = $this->foodPostService->getPostByUuid($id);

      if ($post->user_id !== Auth::id()) {
        abort(403, 'AKSES DITOLAK');
      }

      return view('posts.edit', compact('post'));
    } catch (\Exception $e) {
      return redirect()->route('feed')->with('error', $e->getMessage());
    }
  }

  public function update(UpdateFoodPostRequest $request, $id)
  {
    try {
      $post = $this->foodPostService->getPostByUuid($id);
      $this->foodPostService->updatePost($post, $request->validated(), Auth::id());

      return redirect()->route('post.show', $post->uuid)->with('success', 'Postingan berhasil diperbarui.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }

  public function destroy($id)
  {
    try {
      $post = $this->foodPostService->getPostByUuid($id);
      $this->foodPostService->deletePost($post, Auth::id());

      return redirect()->route('feed')->with('success', 'Postingan berhasil dihapus.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}
