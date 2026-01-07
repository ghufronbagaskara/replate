<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository
{
  public function __construct(
    protected Transaction $model
  ) {}

  public function findByUuid(string $uuid): ?Transaction
  {
    return $this->model->with(['foodPost.user', 'buyer'])
      ->byUuid($uuid)
      ->first();
  }

  public function findById(int $id): ?Transaction
  {
    return $this->model->with(['foodPost.user', 'buyer'])->find($id);
  }

  public function create(array $data): Transaction
  {
    return $this->model->create($data);
  }

  public function update(Transaction $transaction, array $data): bool
  {
    return $transaction->update($data);
  }

  public function getUserTransactions(int $userId): Collection
  {
    return $this->model->with('foodPost.user')
      ->where('buyer_id', $userId)
      ->latest()
      ->get();
  }

  public function getIncomingOrders(int $sellerId): Collection
  {
    return $this->model->with('buyer', 'foodPost')
      ->whereHas('foodPost', function ($query) use ($sellerId) {
        $query->where('user_id', $sellerId);
      })
      ->latest()
      ->get();
  }

  public function getPendingOrdersCount(int $userId): int
  {
    return Cache::remember(
      "user:{$userId}:pending_orders",
      300,
      fn() => $this->model->where('buyer_id', $userId)
        ->pending()
        ->count()
    );
  }

  public function getIncomingPendingOrdersCount(int $sellerId): int
  {
    return Cache::remember(
      "user:{$sellerId}:incoming_pending_orders",
      300,
      fn() => $this->model->whereHas('foodPost', function ($q) use ($sellerId) {
        $q->where('user_id', $sellerId);
      })->pending()->count()
    );
  }
}
