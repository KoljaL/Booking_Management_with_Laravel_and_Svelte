<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Mail\InviteMailController;
use App\Models\User;
use App\Http\Middleware\LogRequests;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Actions Handled By Resource Controller
// https://laravel.com/docs/10.x/controllers#actions-handled-by-resource-controller
// Route::resource('member', MemberController::class);
//
// | Verb       | URI                     | Action | Route Name      |
// |------------|-------------------------|--------|-----------------|
// | GET        | `/photos`               | index  | photos.index    |
// | GET        | `/photos/create`        | create | photos.create   |
// | POST       | `/photos`               | store  | photos.store    |
// | GET        | `/photos/{photo}`       | show   | photos.show     |
// | GET        | `/photos/{photo}/edit`  | edit   | photos.edit     |
// | PUT/PATCH  | `/photos/{photo}`       | update | photos.update   |
// | DELETE     | `/photos/{photo}`       | destroy| photos.destroy  |

// ✅ = done
// 👷 = in progress
// ❓ = is needed?


// Unprotected routes (no authentication required)
Route::middleware(['cors'])->group(function () {
    Route::post('/login', [UserController::class, 'login']); // ✅
    Route::post('/register', [UserController::class, 'register']); // ✅
    Route::post('/logout', [UserController::class, 'logout']); // ❓
});

// Routes for Staff and Member
Route::middleware(['auth:sanctum', LogRequests::class, 'access_control:staff,member'])->group(function () {
    Route::resource('booking', BookingController::class);
});

// Routes for Staff only
Route::middleware(['auth:sanctum', LogRequests::class, 'access_control:staff'])->group(function () {
    // CRUD members
    Route::resource('member', MemberController::class);
    // Send invite mail to member
    Route::get('invite/{user}/{id}', [InviteMailController::class, 'userInvite']); // ✅
});

// Routes for Admin only
Route::middleware(['auth:sanctum', LogRequests::class, 'access_control:admin'])->group(function () {
    // CRUD staff
    Route::resource('staff', StaffController::class);
    // CRUD locations
    Route::resource('location', LocationController::class);
    // Send invite mail to staff
    Route::get('invite/{user}/{id}', [InviteMailController::class, 'userInvite']); // ✅
});










///////////// OLD STUFF ///////////////

// protected routes
// Route::middleware('auth:sanctum')->group(function () {
// Staff -> Booking
// Route::get('/booking', [BookingController::class, 'index']);
// Route::get('/booking/{booking}', [BookingController::class, 'show']);
// Route::post('/booking', [BookingController::class, 'store']);
// Route::put('/booking/{booking}', [BookingController::class, 'update']);
// Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);
// Member -> Booking
// Route::get('/booking', [BookingController::class, 'index']);
// Route::post('/booking', [BookingController::class, 'store']);
// Route::put('/booking/{booking}', [BookingController::class, 'update']);
// Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);
// });




// // Staff -> Member
// Route::get('/member', [MemberController::class, 'index']); // ✅
// Route::get('/member/{member}', [MemberController::class, 'show']); // ✅
// Route::post('/member', [MemberController::class, 'store']); // ✅
// Route::put('/member/{member}', [MemberController::class, 'update']); // ✅
// Route::delete('/member/{member}', [MemberController::class, 'destroy']); // ✅

// Mail 
// Route::get('/member/invite/{member}', [InviteMailController::class, 'memberInvite']); // ✅