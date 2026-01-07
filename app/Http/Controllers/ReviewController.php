<?php

namespace App\Http\Controllers;

use App\Services\ReviewService;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
  public function __construct(
    protected ReviewService $reviewService
  ) {}

  // review form
  public function create($transactionUuid)
  {
    try {
      $transaction = $this->reviewService->getTransactionForReview($transactionUuid, Auth::id());
      return view('reviews.create', compact('transaction'));
    } catch (\Exception $e) {
      abort(403, $e->getMessage());
    }
  }

  // saving review to db
  public function store(StoreReviewRequest $request)
  {
    try {
      $this->reviewService->createReview($request->validated(), Auth::id());
      return redirect()->route('history')->with('success', 'Terima kasih atas review Anda!');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }
}
