<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Repositories\TransactionRepository;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
  public function __construct(
    protected TransactionService $transactionService,
    protected TransactionRepository $transactionRepository
  ) {}

  public function index()
  {
    $user = Auth::user();

    $myOrders = $this->transactionService->getUserTransactions($user->id);
    $incomingOrders = $this->transactionService->getIncomingOrders($user->id);

    return view('orders.index', compact('myOrders', 'incomingOrders'));
  }

  public function store(StoreTransactionRequest $request)
  {
    try {
      $transaction = $this->transactionService->createTransaction(
        $request->validated(),
        Auth::id()
      );

      return redirect()->route('order.receipt', $transaction->uuid)
        ->with('success', 'Pesanan berhasil dibuat! Silakan hubungi penjual.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function showReceipt($uuid)
  {
    try {
      $transaction = $this->transactionRepository->findByUuid($uuid);

      if (!$transaction || $transaction->buyer_id !== Auth::id()) {
        abort(403, 'AKSES DITOLAK');
      }

      return view('orders.receipt', compact('transaction'));
    } catch (\Exception $e) {
      return redirect()->route('order.index')->with('error', $e->getMessage());
    }
  }

  public function confirm($uuid)
  {
    try {
      $transaction = $this->transactionRepository->findByUuid($uuid);
      $this->transactionService->confirmTransaction($transaction, Auth::id());

      return back()->with('success', 'Pesanan telah dikonfirmasi.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function reject($uuid)
  {
    try {
      $transaction = $this->transactionRepository->findByUuid($uuid);
      $this->transactionService->rejectTransaction($transaction, Auth::id());

      return back()->with('success', 'Pesanan telah ditolak.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function cancel($uuid)
  {
    try {
      $transaction = $this->transactionRepository->findByUuid($uuid);
      $this->transactionService->cancelTransaction($transaction, Auth::id());

      return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibatalkan.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function complete($uuid)
  {
    try {
      $transaction = $this->transactionRepository->findByUuid($uuid);
      $this->transactionService->completeTransaction($transaction, Auth::id());

      return back()->with('success', 'Transaksi telah diselesaikan.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}
