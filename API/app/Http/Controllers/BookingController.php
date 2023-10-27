<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller {

    /*
     * Get selected data of all bookings from the same location as the logged in staff (authUser),
     * if the authUser is an admin, then get all bookings
     * 
     * GET /api/booking
     */
    public function index() {
        // return Booking::all();
        $authUser = Auth::user();

        if ($authUser) {
            if (isAdmin($authUser)) {
                // return Booking::all();
                $bookings = Booking::all();
            } else {
                $bookings = getMyBookings($authUser);
            }

            // getthe member to this booking and return the member name
            $member = Booking::with('member')->get();
            return $bookings->transform(function ($booking) {
                return [
                    'id' => $booking->id,
                    'member_id' => $booking->member_id,
                    'member' => $booking->member->user,
                    'location_id' => $booking->location_id,
                    'staff_id' => $booking->staff_id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                    'active' => $booking->active,
                ];
            });
        }
        return response()->json(['message' => 'Bookings not found or no associated staff.'], 404);
    }
    public function show(Booking $booking) {
        return $booking;
    }
    public function store(Request $request) {
        return Booking::create($request->all());
    }
    public function update(Request $request, Booking $booking) {
        $booking->update($request->all());
        return $booking;
    }
    public function destroy(Booking $booking) {
        $booking->delete();
        return response()->json(null, 204);
    }
}

function isAdmin($authUser) {
    return $authUser->staff->is_admin;
}

function getMyBookings($authUser) {
    return Booking::with('member')->where('location_id', $authUser->staff->location_id)->get();
}