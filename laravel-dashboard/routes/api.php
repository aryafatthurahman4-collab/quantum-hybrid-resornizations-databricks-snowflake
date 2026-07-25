<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuantumApiController;

Route::prefix('quantum')->group(function () {
    Route::get('/models', [QuantumApiController::class, 'models']);
    Route::get('/hardware-status', [QuantumApiController::class, 'hardwareStatus']);
    Route::post('/sample', [QuantumApiController::class, 'sample']);
});
