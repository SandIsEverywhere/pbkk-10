<?php

use App\Http\Controllers\AgentOverviewController;
use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('sessions', ChatSessionController::class)
        ->only(['index', 'show'])
        ->parameters(['sessions' => 'chatSession']);
    Route::get('/agents', [AgentOverviewController::class, 'index'])->name('agents.index');
});

require __DIR__.'/auth.php';
