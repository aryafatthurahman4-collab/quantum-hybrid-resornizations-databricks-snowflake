<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuantumApiController;

Route::get('/', function () {
    return view('welcome');
});

// Quantum Hybrid Resornizations, Databricks & Snowflake API Endpoints
Route::get('/api/quantum/models', [QuantumApiController::class, 'models']);
Route::get('/api/quantum/hardware-status', [QuantumApiController::class, 'hardwareStatus']);
Route::post('/api/quantum/sample', [QuantumApiController::class, 'sample']);
