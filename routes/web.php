<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::view('/about', 'about')->name('about');
Route::view('/help', 'help')->name('help');
Route::get('/marketplace', [ItemController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{item}', [ItemController::class, 'show'])->name('marketplace.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('items', ItemController::class)->except(['destroy', 'show', 'index']);
    Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('items/{item}/request', [TransactionController::class, 'store'])->name('transactions.store');

    Route::get('transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('transactions/pending', [TransactionController::class, 'pending'])->name('transactions.pending');
    Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
    Route::post('transactions/{transaction}/payment-proof', [TransactionController::class, 'uploadProof'])->name('transactions.paymentProof');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/{conversation}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/', [MessageController::class, 'store'])->name('messages.store');
        Route::post('/proposal/{message}/accept', [MessageController::class, 'acceptProposal'])->name('messages.proposals.accept');
        Route::post('/proposal/{message}/decline', [MessageController::class, 'declineProposal'])->name('messages.proposals.decline');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('profile/ratings', [ProfileController::class, 'ratings'])->name('profile.ratings');

    Route::get('ratings/{transaction}/create', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('ratings/{transaction}', [RatingController::class, 'store'])->name('ratings.store');

    Route::get('reports', [ReportController::class, 'student'])->name('reports.student');
});

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('items', [AdminController::class, 'items'])->name('admin.items');
    Route::post('items/{item}/approve', [AdminController::class, 'approveItem'])->name('admin.items.approve');
    Route::post('items/{item}/reject', [AdminController::class, 'rejectItem'])->name('admin.items.reject');
    Route::delete('items/{item}', [AdminController::class, 'destroyItem'])->name('admin.items.destroy');
    Route::get('reports', [ReportController::class, 'admin'])->name('admin.reports');
});

require __DIR__.'/auth.php';
