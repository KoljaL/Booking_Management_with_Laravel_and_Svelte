<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// unprotected route to login
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Staff -> Member
    Route::get('/member', [MemberController::class, 'index']);
    Route::get('/member/{member}', [MemberController::class, 'show']);
    Route::post('/member', [MemberController::class, 'store']);
    Route::post('/member/invite', [MemberController::class, 'invite']);
    Route::put('/member/{member}', [MemberController::class, 'update']);
    Route::delete('/member/{member}', [MemberController::class, 'destroy']);

    // Staff -> Booking
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/booking/{booking}', [BookingController::class, 'show']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::put('/booking/{booking}', [BookingController::class, 'update']);
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);

    // Member -> Booking
    Route::get('/booking', [BookingController::class, 'index']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::put('/booking/{booking}', [BookingController::class, 'update']);
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);
});