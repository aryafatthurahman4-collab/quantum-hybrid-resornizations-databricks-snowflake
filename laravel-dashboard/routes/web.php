<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuantumApiController;
use App\Http\Controllers\HrisEmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hris', function () {
    return view('hris');
});

// Quantum Engine Endpoints
Route::get('/api/quantum/models', [QuantumApiController::class, 'models']);
Route::get('/api/quantum/hardware-status', [QuantumApiController::class, 'hardwareStatus']);
Route::post('/api/quantum/sample', [QuantumApiController::class, 'sample']);

// HRIS ITK Endpoints
Route::get('/api/hris/employees', [HrisEmployeeController::class, 'employees']);
Route::get('/api/hris/attendance', [HrisEmployeeController::class, 'attendance']);
