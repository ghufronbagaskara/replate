<?php

namespace App\Services;

use App\Models\FoodPost;
use App\Repositories\FoodPostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BusinessException;
use Illuminate\Pagination\LengthAwarePaginator;

class FoodPostService
{
  public function __construct(
    protected FoodPostRepository $repository
  ) {}

  public function getAvailablePosts(array $filters = [], int $perPage = 9): LengthAwarePaginator
  {
    if (isset($filters['status']) && $filters['status'] === 'free') {
      return $this->repository->getFreePosts($perPage);
    }

    if (isset($filters['price']) && $filters['price'] === 'under_10k') {
      return $this->repository->getPostsByPriceRange(0, 10000, $perPage);
    }

    if (isset($filters['location']) && !empty($filters['location'])) {
      return $this->repository->getPostsByLocation($filters['location'], $perPage);
    }

    return $this->repository->getAvailablePosts($perPage);
  }

  public function createPost(array $data, int $userId): FoodPost
  {
    try {
      return DB::transaction(function () use ($data, $userId) {
        $imagePath = null;

        if (isset($data['image'])) {
          $imagePath = $data['image']->store('food_images', 'public');
        }

        $post = $this->repository->create([
          'user_id' => $userId,
          'title' => $data['title'],
          'description' => $data['description'] ?? null,
          'price' => $data['price'],
          'image_path' => $imagePath,
          'location' => $data['location'],
          'is_free' => $data['price'] == 0,
          'expires_at' => $data['expires_at'],
          'stock' => $data['stock'],
          'status' => 'available',
        ]);

        Log::info('Food post created', ['post_uuid' => $post->uuid, 'user_id' => $userId]);

        return $post;
      });
    } catch (\Exception $e) {
      Log::error('Food post creation failed', [
        'user_id' => $userId,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal membuat postingan. Silakan coba lagi.');
    }
  }

  public function updatePost(FoodPost $post, array $data, int $userId): bool
  {
    if ($post->user_id !== $userId) {
      throw new BusinessException('Anda tidak memiliki akses untuk mengedit postingan ini.', 403);
    }

    try {
      return DB::transaction(function () use ($post, $data, $userId) {
        $updateData = [
          'title' => $data['title'],
          'description' => $data['description'] ?? $post->description,
          'price' => $data['price'],
          'location' => $data['location'] ?? $post->location,
          'expires_at' => $data['expires_at'],
          'stock' => $data['stock'],
          'is_free' => $data['price'] == 0,
        ];

        if (isset($data['image'])) {
          if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
          }
          $updateData['image_path'] = $data['image']->store('food_images', 'public');
        }

        $result = $this->repository->update($post, $updateData);

        Log::info('Food post updated', ['post_uuid' => $post->uuid, 'user_id' => $userId]);

        return $result;
      });
    } catch (\Exception $e) {
      Log::error('Food post update failed', [
        'post_uuid' => $post->uuid,
        'user_id' => $userId,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal memperbarui postingan. Silakan coba lagi.');
    }
  }

  public function deletePost(FoodPost $post, int $userId): bool
  {
    if ($post->user_id !== $userId) {
      throw new BusinessException('Anda tidak memiliki akses untuk menghapus postingan ini.', 403);
    }

    try {
      return DB::transaction(function () use ($post, $userId) {
        if ($post->image_path) {
          Storage::disk('public')->delete($post->image_path);
        }

        $result = $this->repository->delete($post);

        Log::info('Food post deleted', ['post_uuid' => $post->uuid, 'user_id' => $userId]);

        return $result;
      });
    } catch (\Exception $e) {
      Log::error('Food post deletion failed', [
        'post_uuid' => $post->uuid,
        'user_id' => $userId,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal menghapus postingan. Silakan coba lagi.');
    }
  }

  public function getPostByUuid(string $uuid): FoodPost
  {
    $post = $this->repository->findByUuid($uuid);

    if (!$post) {
      throw new BusinessException('Postingan tidak ditemukan.', 404);
    }

    return $post;
  }
}
