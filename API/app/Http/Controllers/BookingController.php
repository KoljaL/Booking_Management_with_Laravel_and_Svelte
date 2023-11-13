<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller {

    /*
     * Get selected data of all bookings from the same location as the logged in staff (authUser),
     * if the authUser is an admin, then get all bookings
     * 
     * GET /api/booking
     */
    public function index(Request $request) {


        // Benchmark::dd([
        //     'Booking::get()' => fn() => Booking::get(), // 0.5 ms
        //     'Booking::byAccessLevel()' => fn() => Booking::byAccessLevel()->showBookings($request->show), // 0.5 ms
        //     "Booking::with('location')->with('member')->get()" => fn() => Booking::with('location')->with('member')->get(), // 20.0 ms
        // ]);

        // dd($request->all());
        try {
            $bookings = Booking::byAccessLevel()->showBookings($request->show);
            $count_bookings = $bookings->count();
            return response()->json(['message' => $request->show . ' bookings', 'count_bookings' => $count_bookings, 'bookings' => $bookings], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'bookings not found or no bookings associated staff.', 'error' => $th], 404);
        }


    }
    public function show(Request $request, $id) {
        try {
            $booking = Booking::withTrashed()->byAccessLevel()->findOrFail($id);

            return response()->json(['message' => 'Booking data', 'show' => $request->show, 'booking' => $booking], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking with ID ' . $id . ' not found', 'exception' => $e], 404);
        }
    }
    public function store(Request $request) {
        // get the logged in Staff
        $authUser = Auth::user();
        $userRole = $authUser->role;

        // check if the logged in user is a member
        if ($userRole == 'member') {
            $request->merge(['member_id' => (string) $authUser->member->id]);
        }

        // check if the logged in staff is an admin
        // and if the request has a location_id
        // if ($authUser->staff && $authUser->isAdmin() && !$request->has('location_id')) {
        //     return response()->json(['message' => 'Only staff can create members.'], 404);
        // }

        // validate the request
        $request->validate([
            'member_id' => 'required|integer',
            'location_id' => 'required|integer',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'slots' => 'required|integer',
        ]);
        DB::beginTransaction();
        // dd($request->all());
        try {
            // dd($authUser->staff->id);
            $booking = Booking::create([
                'member_id' => $request->member_id,
                'location_id' => $request->location_id,
                'date' => $request->date,
                'time' => $request->time,
                'slots' => $request->slots,
                'state' => 'created by ' . $userRole,
                'staff_id' => $authUser->staff->id,
            ]);
            DB::commit();
            return response()->json(['message' => 'Booking created', 'booking' => $booking], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking not created', 'error' => $e], 404);
        }

    }
    public function update(Request $request, $id) {

        // validate the request
        $request->validate([
            'member_id' => 'integer',
            'location_id' => 'integer',
            'date' => 'date',
            'time' => 'date_format:H:i',
            'slots' => 'integer',
            'comment_member' => 'string',
            'comment_staff' => 'string',
        ]);

        $booking = Booking::withTrashed()->byAccessLevel()->findOrFail($id);
        DB::beginTransaction();
        try {
            $booking->update([
                'member_id' => $request->member_id,
                'location_id' => $request->location_id,
                'date' => $request->date,
                'time' => $request->time,
                'slots' => $request->slots,
                'comment_member' => $request->comment_member,
                'comment_staff' => $request->comment_staff,
            ]);
            DB::commit();
            return response()->json(['message' => 'Booking updated', 'booking' => $booking], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking not updated', 'error' => $e], 404);
        }
    }
    public function destroy($id) {
        // get the logged in Staff
        $authUser = Auth::user();
        $userRole = $authUser->role;
        $booking = Booking::withTrashed()->byAccessLevel()->findOrFail($id);
        DB::beginTransaction();
        try {
            // change state to deleted
            $booking->update([
                'state' => 'deleted by ' . $userRole,
            ]);
            $booking->delete();
            DB::commit();
            return response()->json(['message' => 'Booking deleted', 'booking' => $booking], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking not deleted', 'error' => $e], 404);
        }
    }
}

// function getLocationId($authUser) {
//     return $authUser->staff->location_id;
// }

// function isAdmin($authUser) {
//     return $authUser->staff->is_admin;
// }

// function getMyBookings($authUser) {
//     return Booking::with('member')->where('location_id', $authUser->staff->location_id)->get();
// }