<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller {


    /**
     * Bookings INDEX
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get all bookings by access level. 
     * @path GET api/bookings
     */
    public function index(Request $request) {
        $date = request()->date ?? null;
        $show = request()->show ?? null;
        try {
            // dd($request->all());
            $bookings = Booking::byAccessLevel()->showBookings($show, $date);
            // dd($bookings);
            $count_bookings = $bookings->count();

            $messageDate = $date ? ' date: ' . $date : '';
            $messageShow = $show ? ' show: ' . $show : '';

            return response()->json([
                'message' => 'All Bookings' . $messageDate . $messageShow,
                'count_bookings' => $count_bookings,
                'booking' => $bookings
            ], 200);

        } catch (\Exception $th) {
            return response()->json(['message' => 'bookings not found or no bookings associated staff.', 'error' => $th->getMessage()], 404);
        }
    }
    /**
     * Bookings SHOW
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get a booking by access level.
     * @path GET api/bookings/{id}
     */
    public function show(Request $request, $id) {
        try {
            $booking = Booking::withTrashed()->byAccessLevel()->findOrFail($id);

            // It not allowed for a Member to see a soft deleted Booking
            if ($booking->trashed() && Auth::user()->role == 'member') {
                return response()->json(['message' => 'Only staff can update deleted bookings.'], 404);
            }

            return response()->json(['message' => 'Booking data', 'show' => $request->show, 'booking' => $booking], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking with ID ' . $id . ' not found', 'exception' => $e], 404);
        }
    }

    /**
     * Bookings STORE
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Create a booking by access level.
     * @path POST api/bookings
     */
    public function store(Request $request) {
        // get the logged in Staff
        $authUser = Auth::user();
        $userRole = $authUser->role;

        // check if the logged in user is a member
        if ($userRole == 'member') {
            $request->merge(['member_id' => (string) $authUser->member->id]);
        }

        // check if the logged in staff is an admin and if the request has no location_id
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
                // 'staff_id' => $authUser->staff->id ?? 999,
            ]);
            DB::commit();
            return response()->json(['message' => 'Booking created', 'booking' => $booking], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking not created', 'error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()], 404);
        }

    }

    /**
     * Bookings UPDATE
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Update a booking by access level.
     * @path PUT api/bookings/{id}
     */
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

        // It not allowed for a Member to update a soft deleted Booking
        if ($booking->trashed() && Auth::user()->role == 'member') {
            return response()->json(['message' => 'Only staff can update deleted bookings.'], 404);
        }

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



    /**
     * Bookings DESTROY
     * 
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Delete a booking by access level.
     * @path DELETE api/bookings/{id}
     */
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