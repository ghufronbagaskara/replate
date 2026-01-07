<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodPostController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostModerationController;
use App\Http\Controllers\Admin\UserModerationController;
use App\Http\Controllers\Admin\ReportListController;

// as guest
Route::get('/', function () {
  return Auth::check() ? redirect('/feed') : redirect('/login');
});

// --- autentikasi as a guest ---
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
  Route::post('/register', [AuthController::class, 'register']);
});

// --- route that needed to logged in ---
Route::middleware(['auth', 'log.activity'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  // feed
  Route::get('/feed', [FoodPostController::class, 'index'])->name('feed');

  // crud food post (using UUID)
  Route::get('/post/new', [FoodPostController::class, 'create'])->name('post.create');
  Route::post('/post', [FoodPostController::class, 'store'])->name('post.store');
  Route::get('/post/{uuid}', [FoodPostController::class, 'show'])->name('post.show');
  Route::get('/post/{uuid}/edit', [FoodPostController::class, 'edit'])->name('post.edit');
  Route::put('/post/{uuid}', [FoodPostController::class, 'update'])->name('post.update');
  Route::delete('/post/{uuid}', [FoodPostController::class, 'destroy'])->name('post.destroy');

  // transaction (using UUID)
  Route::post('/order', [TransactionController::class, 'store'])->name('order.store');
  Route::get('/orders', [TransactionController::class, 'index'])->name('order.index');
  Route::patch('/order/{uuid}/confirm', [TransactionController::class, 'confirm'])->name('order.confirm');
  Route::patch('/order/{uuid}/reject', [TransactionController::class, 'reject'])->name('order.reject');
  Route::patch('/order/{uuid}/cancel', [TransactionController::class, 'cancel'])->name('order.cancel');
  Route::patch('/order/{uuid}/complete', [TransactionController::class, 'complete'])->name('order.complete');

  // receipt (using UUID)
  Route::get('/receipt/{uuid}', [TransactionController::class, 'showReceipt'])->name('order.receipt');

  // transaction history
  Route::get('/history', [PageController::class, 'history'])->name('history');

  // rating and review
  Route::get('/review/{transactionUuid}', [ReviewController::class, 'create'])->name('review.create');
  Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

  // report
  Route::post('/report', [ReportController::class, 'store'])->name('report.store');

  // others
  Route::get('/profile', [PageController::class, 'profile'])->name('profile');
  Route::get('/news', [PageController::class, 'news'])->name('news');
});

// admin route (using UUID)
Route::prefix('admin')->middleware(['auth', 'admin', 'log.activity'])->name('admin.')->group(function () {
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/posts', [PostModerationController::class, 'index'])->name('posts.index');
  Route::delete('/posts/{uuid}', [PostModerationController::class, 'destroy'])->name('posts.destroy');
  Route::get('/users', [UserModerationController::class, 'index'])->name('users.index');
  Route::patch('/users/{uuid}/verify', [UserModerationController::class, 'verify'])->name('users.verify');
  Route::patch('/users/{uuid}/suspend', [UserModerationController::class, 'suspend'])->name('users.suspend');
  Route::get('/reports', [ReportListController::class, 'index'])->name('reports.index');
});
