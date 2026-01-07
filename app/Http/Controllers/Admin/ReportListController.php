<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;

class ReportListController extends Controller
{
  public function __construct(
    protected ReportRepository $reportRepository
  ) {}

  public function index()
  {
    $reports = $this->reportRepository->getAllReports(20);
    return view('admin.reports.index', compact('reports'));
  }
}
