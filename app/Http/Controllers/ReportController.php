<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
  public function __construct(
    protected ReportService $reportService
  ) {}

  // Menyimpan laporan dari user
  public function store(StoreReportRequest $request)
  {
    try {
      $this->reportService->createReport($request->validated(), Auth::id());
      return back()->with('success', 'Laporan Anda telah dikirim dan akan segera kami tinjau.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }
}
