<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InviteMailController;
use App\Models\User;

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
// ❓ = is it needed?


// unprotected route to login
Route::post('/login', [UserController::class, 'login']); // ✅
Route::post('/logout', [UserController::class, 'logout']); // ❓
Route::post('/register', [UserController::class, 'register']); // ✅




// 
// Routes for ROLE_STAFF
//
Route::middleware(['auth:sanctum', 'check_role:' . User::ROLE_STAFF])->group(function () {
    // Staff routes for CRUD members
    Route::resource('member', MemberController::class);
    // Staff sending mails
    Route::get('/member/invite/{member}', [InviteMailController::class, 'memberInvite']); // ✅
});



// protected routes
Route::middleware('auth:sanctum')->group(function () {



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



///////////// OLD STUFF ///////////////

// // Staff -> Member
// Route::get('/member', [MemberController::class, 'index']); // ✅
// Route::get('/member/{member}', [MemberController::class, 'show']); // ✅
// Route::post('/member', [MemberController::class, 'store']); // ✅
// Route::put('/member/{member}', [MemberController::class, 'update']); // ✅
// Route::delete('/member/{member}', [MemberController::class, 'destroy']); // ✅

// Mail 
// Route::get('/member/invite/{member}', [InviteMailController::class, 'memberInvite']); // ✅