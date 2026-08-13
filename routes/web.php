<?php

use App\Http\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DemoController::class, 'index'])->name('demo');
Route::post('/runs', [DemoController::class, 'store'])->name('runs.store');
Route::get('/runs/{runId}', [DemoController::class, 'show'])->name('runs.show');
Route::post('/runs/{runId}/candidates/{candidateId}', [DemoController::class, 'select'])
    ->name('runs.select');
