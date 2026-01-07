<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\FoodPost;
use App\Repositories\TransactionRepository;
use App\Repositories\FoodPostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BusinessException;
use Illuminate\Support\Collection;

class TransactionService
{
  public function __construct(
    protected TransactionRepository $transactionRepository,
    protected FoodPostRepository $foodPostRepository
  ) {}

  public function createTransaction(array $data, int $buyerId): Transaction
  {
    $post = $this->foodPostRepository->findByUuid($data['post_uuid']);

    if (!$post) {
      throw new BusinessException('Postingan tidak ditemukan.', 404);
    }

    if ($post->user_id === $buyerId) {
      throw new BusinessException('Anda tidak dapat memesan makanan Anda sendiri.');
    }

    if ($post->stock < $data['quantity']) {
      throw new BusinessException('Stok tidak mencukupi.');
    }

    if (!$post->is_available) {
      throw new BusinessException('Postingan tidak tersedia lagi.');
    }

    try {
      return DB::transaction(function () use ($post, $data, $buyerId) {
        $post->decrement('stock', $data['quantity']);

        $totalPrice = $post->price * $data['quantity'];

        $transaction = $this->transactionRepository->create([
          'buyer_id' => $buyerId,
          'post_id' => $post->id,
          'quantity' => $data['quantity'],
          'total_price' => $totalPrice,
          'status' => 'pending',
        ]);

        // Clear cache
        Cache::forget("user:{$buyerId}:pending_orders");
        Cache::forget("user:{$post->user_id}:incoming_pending_orders");

        Log::info('Transaction created', [
          'transaction_uuid' => $transaction->uuid,
          'buyer_id' => $buyerId,
          'post_uuid' => $post->uuid
        ]);

        return $transaction;
      });
    } catch (\Exception $e) {
      Log::error('Transaction creation failed', [
        'buyer_id' => $buyerId,
        'post_uuid' => $data['post_uuid'] ?? 'unknown',
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal membuat pesanan. Silakan coba lagi.');
    }
  }

  public function confirmTransaction(Transaction $transaction, int $sellerId): bool
  {
    if ($transaction->foodPost->user_id !== $sellerId) {
      throw new BusinessException('Anda tidak memiliki akses.', 403);
    }

    if ($transaction->status !== 'pending') {
      throw new BusinessException('Transaksi tidak dapat dikonfirmasi.');
    }

    try {
      $result = $this->transactionRepository->update($transaction, ['status' => 'confirmed']);

      Log::info('Transaction confirmed', ['transaction_uuid' => $transaction->uuid]);

      return $result;
    } catch (\Exception $e) {
      Log::error('Transaction confirmation failed', [
        'transaction_uuid' => $transaction->uuid,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal mengkonfirmasi pesanan.');
    }
  }

  public function rejectTransaction(Transaction $transaction, int $sellerId): bool
  {
    if ($transaction->foodPost->user_id !== $sellerId) {
      throw new BusinessException('Anda tidak memiliki akses.', 403);
    }

    if ($transaction->status !== 'pending') {
      throw new BusinessException('Transaksi tidak dapat ditolak.');
    }

    try {
      return DB::transaction(function () use ($transaction) {
        $this->transactionRepository->update($transaction, ['status' => 'rejected']);
        $transaction->foodPost->increment('stock', $transaction->quantity);

        Log::info('Transaction rejected', ['transaction_uuid' => $transaction->uuid]);

        return true;
      });
    } catch (\Exception $e) {
      Log::error('Transaction rejection failed', [
        'transaction_uuid' => $transaction->uuid,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal menolak pesanan.');
    }
  }

  public function cancelTransaction(Transaction $transaction, int $buyerId): bool
  {
    if ($transaction->buyer_id !== $buyerId) {
      throw new BusinessException('Anda tidak memiliki akses.', 403);
    }

    if (!$transaction->can_be_cancelled) {
      throw new BusinessException('Pesanan tidak dapat dibatalkan.');
    }

    try {
      return DB::transaction(function () use ($transaction) {
        $this->transactionRepository->update($transaction, ['status' => 'cancelled']);
        $transaction->foodPost->increment('stock', $transaction->quantity);

        Log::info('Transaction cancelled', ['transaction_uuid' => $transaction->uuid]);

        return true;
      });
    } catch (\Exception $e) {
      Log::error('Transaction cancellation failed', [
        'transaction_uuid' => $transaction->uuid,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal membatalkan pesanan.');
    }
  }

  public function completeTransaction(Transaction $transaction, int $sellerId): bool
  {
    if ($transaction->foodPost->user_id !== $sellerId) {
      throw new BusinessException('Anda tidak memiliki akses.', 403);
    }

    if ($transaction->status !== 'confirmed') {
      throw new BusinessException('Transaksi tidak dapat diselesaikan.');
    }

    try {
      $result = $this->transactionRepository->update($transaction, ['status' => 'completed']);

      Log::info('Transaction completed', ['transaction_uuid' => $transaction->uuid]);

      return $result;
    } catch (\Exception $e) {
      Log::error('Transaction completion failed', [
        'transaction_uuid' => $transaction->uuid,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal menyelesaikan transaksi.');
    }
  }

  public function getUserTransactions(int $userId): Collection
  {
    return $this->transactionRepository->getUserTransactions($userId);
  }

  public function getIncomingOrders(int $sellerId): Collection
  {
    return $this->transactionRepository->getIncomingOrders($sellerId);
  }
}
