<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository
{
  public function create(array $data): Review
  {
    return Review::create($data);
  }

  public function findByUuid(string $uuid): ?Review
  {
    return Review::where('uuid', $uuid)->first();
  }

  public function findByTransactionUuid(string $transactionUuid): ?Review
  {
    return Review::whereHas('transaction', function ($query) use ($transactionUuid) {
      $query->where('uuid', $transactionUuid);
    })->first();
  }

  public function update(Review $review, array $data): bool
  {
    return $review->update($data);
  }

  public function delete(Review $review): bool
  {
    return $review->delete();
  }

  public function getUserReviews(int $userId): Collection
  {
    return Review::with('transaction.foodPost')
      ->whereHas('transaction', function ($query) use ($userId) {
        $query->where('buyer_id', $userId);
      })
      ->latest()
      ->get();
  }

  public function getPostReviews(int $postId): Collection
  {
    return Review::with('transaction.buyer')
      ->whereHas('transaction.foodPost', function ($query) use ($postId) {
        $query->where('id', $postId);
      })
      ->latest()
      ->get();
  }
}
