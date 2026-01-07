<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FoodPost;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => FoodPost::count(),
            'active_posts' => FoodPost::where('status', 'available')->count(),
            'pending_reports' => Report::count(), // Bisa ditambahkan status 'pending' jika ada
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
