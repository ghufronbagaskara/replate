<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Transaction;
use App\Repositories\ReviewRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BusinessException;

class ReviewService
{
  public function __construct(
    protected ReviewRepository $reviewRepository,
    protected TransactionRepository $transactionRepository
  ) {}

  public function createReview(array $data, int $userId): Review
  {
    $transaction = $this->transactionRepository->findByUuid($data['transaction_uuid']);

    if (!$transaction) {
      throw new BusinessException('Transaksi tidak ditemukan.', 404);
    }

    // Validate authorization
    if ($transaction->buyer_id !== $userId) {
      throw new BusinessException('Anda tidak memiliki akses untuk memberikan review pada transaksi ini.', 403);
    }

    if ($transaction->status !== 'completed') {
      throw new BusinessException('Review hanya dapat diberikan pada transaksi yang sudah selesai.');
    }

    if ($transaction->review) {
      throw new BusinessException('Review sudah pernah diberikan untuk transaksi ini.');
    }

    try {
      return DB::transaction(function () use ($transaction, $data, $userId) {
        $review = $this->reviewRepository->create([
          'transaction_id' => $transaction->id,
          'rating' => $data['rating'],
          'review' => $data['review'] ?? null,
        ]);

        Log::info('Review created', [
          'review_uuid' => $review->uuid,
          'transaction_uuid' => $transaction->uuid,
          'user_id' => $userId
        ]);

        return $review;
      });
    } catch (\Exception $e) {
      Log::error('Review creation failed', [
        'transaction_uuid' => $transaction->uuid,
        'user_id' => $userId,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal membuat review. Silakan coba lagi.');
    }
  }

  public function canUserReviewTransaction(string $transactionUuid, int $userId): bool
  {
    $transaction = $this->transactionRepository->findByUuid($transactionUuid);

    if (!$transaction) {
      return false;
    }

    return $transaction->buyer_id === $userId
      && $transaction->status === 'completed'
      && !$transaction->review;
  }

  public function getTransactionForReview(string $transactionUuid, int $userId): Transaction
  {
    $transaction = $this->transactionRepository->findByUuid($transactionUuid);

    if (!$transaction) {
      throw new BusinessException('Transaksi tidak ditemukan.', 404);
    }

    if (!$this->canUserReviewTransaction($transactionUuid, $userId)) {
      throw new BusinessException('Anda tidak dapat memberikan review untuk transaksi ini.', 403);
    }

    return $transaction;
  }
}
