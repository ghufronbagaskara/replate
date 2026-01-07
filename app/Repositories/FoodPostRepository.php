<?php

namespace App\Repositories;

use App\Models\FoodPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FoodPostRepository
{
  public function __construct(
    protected FoodPost $model
  ) {}

  public function findByUuid(string $uuid): ?FoodPost
  {
    return Cache::remember(
      "foodpost:uuid:{$uuid}",
      1800,
      fn() => $this->model->with('user')->byUuid($uuid)->first()
    );
  }

  public function findById(int $id): ?FoodPost
  {
    return $this->model->with('user')->find($id);
  }

  public function create(array $data): FoodPost
  {
    return $this->model->create($data);
  }

  public function update(FoodPost $post, array $data): bool
  {
    return $post->update($data);
  }

  public function delete(FoodPost $post): bool
  {
    return $post->delete();
  }

  public function getAvailablePosts(int $perPage = 9): LengthAwarePaginator
  {
    return Cache::remember(
      "foodposts:available:page:{$perPage}",
      600,
      fn() => $this->model->available()
        ->with('user')
        ->latest()
        ->paginate($perPage)
    );
  }

  public function getFreePosts(int $perPage = 9): LengthAwarePaginator
  {
    return $this->model->available()
      ->free()
      ->with('user')
      ->latest()
      ->paginate($perPage);
  }

  public function getPostsByLocation(string $location, int $perPage = 9): LengthAwarePaginator
  {
    return $this->model->available()
      ->byLocation($location)
      ->with('user')
      ->latest()
      ->paginate($perPage);
  }

  public function getPostsByPriceRange(float $min, float $max, int $perPage = 9): LengthAwarePaginator
  {
    return $this->model->available()
      ->priceRange($min, $max)
      ->with('user')
      ->latest()
      ->paginate($perPage);
  }

  public function getUserPosts(int $userId): Collection
  {
    return $this->model->where('user_id', $userId)
      ->latest()
      ->get();
  }
}
