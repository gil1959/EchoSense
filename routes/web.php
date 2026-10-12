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
    return view('pengaturan');
});

Route::get('/peringatan', function () {
    return view('pengaturan');
});

// Rute API
Route::get('/api/briefing', [\App\Http\Controllers\DashboardController::class, 'getEnvironmentBriefing']);
Route::get('/api/voices', [\App\Http\Controllers\DashboardController::class, 'getVoices']);
Route::post('/api/tts', [\App\Http\Controllers\DashboardController::class, 'generateTTS']);
Route::post('/api/destination', [\App\Http\Controllers\DashboardController::class, 'getDestinationData']);
Route::post('/api/voice-command', [\App\Http\Controllers\DashboardController::class, 'processVoiceCommand']);
Route::post('/api/alert', [\App\Http\Controllers\DashboardController::class, 'generateAlert']);
