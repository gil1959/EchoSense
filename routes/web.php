<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/pengaturan', function () {
    return view('pengaturan');
});

Route::get('/riwayat', function () {
    return view('pengaturan'); // placeholder
});

Route::get('/peringatan', function () {
    return view('pengaturan'); // placeholder
});

Route::get('/api/sonify', [\App\Http\Controllers\DashboardController::class, 'getSonificationData']);
Route::get('/api/voices', [\App\Http\Controllers\DashboardController::class, 'getVoices']);
Route::post('/api/tts', [\App\Http\Controllers\DashboardController::class, 'generateTTS']);
