<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
  public function __construct(
    protected User $model
  ) {}

  public function findByUuid(string $uuid): ?User
  {
    return Cache::remember(
      "user:uuid:{$uuid}",
      3600,
      fn() => $this->model->byUuid($uuid)->first()
    );
  }

  public function findById(int $id): ?User
  {
    return $this->model->find($id);
  }

  public function findByEmail(string $email): ?User
  {
    return $this->model->where('email', $email)->first();
  }

  public function create(array $data): User
  {
    return $this->model->create($data);
  }

  public function update(User $user, array $data): bool
  {
    return $user->update($data);
  }

  public function delete(User $user): bool
  {
    return $user->delete();
  }

  public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model->latest()->paginate($perPage);
  }

  public function getVerifiedUsers(): Collection
  {
    return $this->model->where('is_verified', true)->get();
  }

  public function getSuspendedUsers(): Collection
  {
    return $this->model->whereNotNull('suspended_at')->get();
  }
}
