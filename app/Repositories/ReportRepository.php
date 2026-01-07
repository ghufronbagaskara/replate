<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportRepository
{
  public function create(array $data): Report
  {
    return Report::create($data);
  }

  public function findByUuid(string $uuid): ?Report
  {
    return Report::where('uuid', $uuid)->first();
  }

  public function update(Report $report, array $data): bool
  {
    return $report->update($data);
  }

  public function delete(Report $report): bool
  {
    return $report->delete();
  }

  public function getAllReports(int $perPage = 20): LengthAwarePaginator
  {
    return Report::with('foodPost', 'reporter')
      ->latest()
      ->paginate($perPage);
  }

  public function getPendingReports(int $perPage = 20): LengthAwarePaginator
  {
    return Report::with('foodPost', 'reporter')
      ->where('status', 'pending')
      ->latest()
      ->paginate($perPage);
  }

  public function getReportsByPost(int $postId): Collection
  {
    return Report::with('reporter')
      ->where('post_id', $postId)
      ->latest()
      ->get();
  }
}
