<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\JournalistController;
use App\Http\Controllers\ContactController;

Route::get('/', HomeController::class)->name('home');
Route::get('/artikel/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/artikel/{article}/komentar', [ArticleController::class, 'storeComment'])
    ->middleware('auth:reader')
    ->name('articles.comments.store');

Route::put('/komentar/{comment}', [ArticleController::class, 'updateComment'])
    ->middleware('auth:reader')
    ->name('comments.update');

Route::delete('/komentar/{comment}', [ArticleController::class, 'destroyComment'])
    ->middleware('auth:reader')
    ->name('comments.destroy');
Route::get('/kategori/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/pencarian', [SearchController::class, 'index'])->name('search.index');
Route::get('/arsip', [ArchiveController::class, 'index'])->name('archive.index');
Route::get('/jurnalis/{journalist}', [JournalistController::class, 'show'])->name('journalists.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:5,60')->name('contact.store');
Route::post('/langganan', [SubscriptionController::class, 'store'])->name('subscriptions.store');
Route::get('/langganan/berhenti/{token}', [SubscriptionController::class, 'unsubscribe'])->name('subscriptions.unsubscribe');

Route::get('dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
