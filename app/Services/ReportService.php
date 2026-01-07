<?php

namespace App\Services;

use App\Models\Report;
use App\Repositories\ReportRepository;
use App\Repositories\FoodPostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BusinessException;

class ReportService
{
  public function __construct(
    protected ReportRepository $reportRepository,
    protected FoodPostRepository $foodPostRepository
  ) {}

  public function createReport(array $data, int $userId): Report
  {
    $post = $this->foodPostRepository->findByUuid($data['post_uuid']);

    if (!$post) {
      throw new BusinessException('Postingan tidak ditemukan.', 404);
    }

    // Prevent self-reporting
    if ($post->user_id === $userId) {
      throw new BusinessException('Anda tidak dapat melaporkan postingan Anda sendiri.');
    }

    try {
      return DB::transaction(function () use ($post, $data, $userId) {
        $report = $this->reportRepository->create([
          'post_id' => $post->id,
          'reporter_id' => $userId,
          'reason' => $data['reason'],
          'other_reason' => $data['other_reason'] ?? null,
        ]);

        Log::info('Report created', [
          'report_uuid' => $report->uuid,
          'post_uuid' => $post->uuid,
          'reporter_id' => $userId,
          'reason' => $data['reason']
        ]);

        return $report;
      });
    } catch (\Exception $e) {
      Log::error('Report creation failed', [
        'post_uuid' => $post->uuid,
        'reporter_id' => $userId,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal mengirim laporan. Silakan coba lagi.');
    }
  }

  public function resolveReport(string $reportUuid, string $action): bool
  {
    $report = $this->reportRepository->findByUuid($reportUuid);

    if (!$report) {
      throw new BusinessException('Laporan tidak ditemukan.', 404);
    }

    try {
      return DB::transaction(function () use ($report, $action) {
        $status = $action === 'approve' ? 'resolved' : 'dismissed';
        $result = $this->reportRepository->update($report, ['status' => $status]);

        if ($action === 'approve') {
          // Take down the reported post
          $report->foodPost->update(['status' => 'taken_down']);
        }

        Log::info('Report resolved', [
          'report_uuid' => $report->uuid,
          'action' => $action
        ]);

        return $result;
      });
    } catch (\Exception $e) {
      Log::error('Report resolution failed', [
        'report_uuid' => $report->uuid,
        'error' => $e->getMessage()
      ]);
      throw new BusinessException('Gagal memproses laporan.');
    }
  }
}
