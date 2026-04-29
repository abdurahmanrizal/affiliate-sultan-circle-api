<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\JamaahController;
use App\Http\Controllers\Api\DepartureScheduleController;

Route::get('/cities', [CityController::class, 'index']);

Route::get('/kols', [KolController::class, 'index']);
Route::get('/kols/referral/{referralCode}', [KolController::class, 'showByReferralCode']);
Route::post('/kols', [KolController::class, 'store']);

Route::get('/referrals', [ReferralController::class, 'index']);

Route::post('/referrals/click/{referralCode}', [ReferralController::class, 'trackClick']);
Route::post('/referrals/register', [ReferralController::class, 'registerReferral']);

Route::get('/departure-schedule', [DepartureScheduleController::class, 'index']);

Route::get('/jamaahs', [JamaahController::class, 'index']);
Route::post('/jamaahs', [JamaahController::class, 'store']);
Route::patch('/jamaah/{id}/paid', [JamaahController::class, 'updateToPaid']);
