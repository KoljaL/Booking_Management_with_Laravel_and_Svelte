<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberPasswordResetMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller {



    /*
     * INDEX
     * Get selected data of all members from the same location as the logged in staff (authUser),
     * if the authUser is an admin, then get all members
     */
    public function index() {
        // return Member::with('user')->get();
        $authUser = Auth::user();

        if ($authUser) {
            if (isAdmin($authUser)) {
                $members = Member::with('user')->get();
            } else {
                $members = $authUser->staff->members()->with('user')->get();
            }

            // remove "user" key from the member object
            $members->transform(function ($member) {
                unset($member['user']);
                return $member;
            });
            return $members;

        }
        return response()->json(['message' => 'Members not found or no associated staff.'], 404);
    }

    /*
     * SHOW
     * Get selected data of a single member from the same location as the logged in staff (authUser),
     * including all bookings for this member
     */
    public function show(Member $member) {
        try {
            // get the logged in user for the staff_id 
            $authUser = Auth::user();

            if (isAdmin($authUser) || $member->staff_id == $authUser->staff->id) {
                // Get all bookings for this member
                $member->load('booking');
                return $member;
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Member not found or no associated staff.'], 404);
        }
        return response()->json(['message' => 'Member not found or no associated staff.'], 404);
    }


    /*
     * STORE
     * Create a new User, add the user_id to the Member, and return the Member
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        // get the logged in user for the staff_id, location_id and max_booking
        $authUser = Auth::user();
        $location_id = getLocationId($authUser);
        $max_booking = getMaxBooking($location_id);
        $staff_id = getStaffId($authUser);

        // first create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        // then create a new member
        $member = new Member([
            'phone' => $request->phone,
            'location_id' => $location_id,
            'jc_number' => $request->jc_number,
            'max_booking' => $max_booking,
            'active' => 1,
            'archived' => 0,
            'staff_id' => $staff_id,
        ]);


        // associate the member with the user
        $user->member()->save($member);

        // return the member
        return $member;
    }

    /**
     * INVITE
     * Send a password reset link to the given user.
     */
    public function invite(Member $member) {
        // // Generate a unique token for the member.
        // $token = Str::random(60);
        // // Save the token to the member's record.
        // $member->update(['token' => $token]);
        // // Send an email with a one-time link.
        // Mail::to($member->email)->send(new MemberPasswordResetMail($token));
        // Return a response indicating success.
        // return response()->json(['message' => 'Password reset link sent']);
    }

    /*
     * UPDATE
     * Update a Member
     */
    public function update(Request $request, Member $member) {
        $userData = $request->only(['name', 'email']);
        $memberData = $request->toArray();
        unset($memberData['name']);
        unset($memberData['email']);

        DB::beginTransaction();
        try {
            $user = $member->user;
            // Update User attributes
            $user->update($userData);
            // Update Member attributes
            $member->update($memberData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // return error message 
            return response()->json(['message' => $e->getMessage()], 500);
        }

        // remove "user" key from the member object and return the updated member
        unset($member['user']);
        return $member;
    }

    /*
     * DESTROY
     * Delete a Member
     */
    public function destroy(Member $member) {
        try {
            // get name of member
            // $name = $member->name;
            $data = $member->toArray();
            unset($data['user']);

            // first delete all bookings for this member
            $member->booking()->delete();
            // then delete the member
            $member->delete();
            // and finally delete the user
            $member->user()->delete();

            // return info 
            return response()->json(['message' => 'Member "' . $data['name'] . '" deleted', 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Member not found'], 404);
        }
    }
}


/**
 * 
 * Just some helper functions
 * 
 */
function getMyMembers($authUser) {
    return $authUser->staff->members()->with('user')->get();
}

function isAdmin($authUser) {
    return $authUser->staff->is_admin;
}

function getLocationId($authUser) {
    return $authUser->staff->location_id;
}

function getStaffId($authUser) {
    return $authUser->staff->id;
}

function getMaxBooking($location_id) {
    $location = \App\Models\Location::find($location_id);
    return $location->max_booking;
}


/**
 * 
 * This is the old code, which is not used anymore
 * 
 */

// filter out the booking data
// $member['bookings'] = $member->load('booking')->booking->transform(function ($booking) {
//     return [
//         'id' => $booking->id,
//         'member_id' => $booking->member_id,
//         'date' => $booking->date,
//         'time' => $booking->time,
//         'location_id' => $booking->location_id,
//         'slots' => $booking->slots,
//         'state' => $booking->state,
//         'started_at' => $booking->started_at,
//         'ended_at' => $booking->ended_at,
//         'created_at' => $booking->created_at,
//         'updated_at' => $booking->updated_at,
//     ];
// });
// return [
//     'id' => $member->id,
//     'name' => $member->user->name,
//     'email' => $member->user->email,
//     'location_id' => $member->location_id,
//     'staff_id' => $member->staff_id,
//     'phone' => $member->phone,
//     'jc_number' => $member->jc_number,
//     'active' => $member->active,
//     'max_booking' => $member->max_booking,
//     'booking' => $bookings,
//     'created_at' => $member->created_at,
//     'updated_at' => $member->updated_at,
// ];

// public function show(Member $member) {
//     if (!$member) {
//         return response()->json(['message' => 'Member not found'], 404);
//     }
//     $authUser = Auth::user();
//     if ($authUser) {
//         if (isAdmin($authUser) || $member->staff_id == $authUser->staff->id) {
//             // get all bookings for this member
//             $member['bookings'] = $member->load('booking')->booking;
//             return $member;
//         }
//         return response()->json(['message' => 'Member not found or no associated staff.'], 404);
//     }
// }


// return $members->transform(function ($member) {
//     return [
//         'id' => $member->id,
//         'name' => $member->user->name,
//         'email' => $member->user->email,
//         'location_id' => $member->location_id,
//         'staff_id' => $member->staff_id,
//         'phone' => $member->phone,
//         'created_at' => $member->created_at,
//         'updated_at' => $member->updated_at,
//         'active' => $member->active,
//     ];
// });


// public function update(Request $request, Member $member) {
//     $request->validate([
//         'name' => 'required|alpha',
//         'email' => 'required|email',
//         'staff_id' => 'numeric',
//         'location_id' => 'numeric',
//         'phone' => 'numeric',
//         'jc_number' => 'string',
//         'active' => 'boolean',
//         'archived' => 'boolean',
//         'max_booking' => 'numeric',
//     ]);
//     // $member->update($request->all());
//     // return $member;

//     $memberUser = User::where('role', 'member')->find($member->user_id);

//     if ($memberUser) {
//         $memberUser->update([
//             'name' => $request->name,
//             'email' => $request->email,
//         ]);
//     }
//     $member = $memberUser->member;
//     if ($member) {
//         $member->update([
//             'staff_id' => $request->staff_id ?? $member->staff_id,
//             'location_id' => $request->location_id ?? $member->location_id,
//             'phone' => $request->phone ?? $member->phone,
//             'jc_number' => $request->jc_number ?? $member->jc_number,
//             'active' => $request->active ?? $member->active,
//             'archived' => $request->archived ?? $member->archived,
//             'max_booking' => $request->max_booking ?? $member->max_booking,
//         ]);
//     }
//     return [
//         'id' => $member->id,
//         'name' => $member->user->name,
//         'email' => $member->user->email,
//         'staff_id' => $member->staff_id,
//         'location_id' => $member->location_id,
//         'phone' => $member->phone,
//         'jc_number' => $member->jc_number,
//         'active' => $member->active,
//         'archived' => $member->archived,
//         'max_booking' => $member->max_booking,
//         'created_at' => $member->created_at,
//         'updated_at' => $member->updated_at,
//     ];
//     // return $memberUser;
// }


// this fails, because the user_id is not set...
// $member = Member::create([
//     'user_id' => $user->id,
//     // 'user_id' => function () {
//     //     return User::factory()->create()->id;
//     // },
//     'phone' => $request->phone ?? '',
//     'location_id' => getLocationId($authUser),
//     'jc_number' => $request->jc_number ?? '',
//     'max_booking' => $request->max_booking ?? '',
//     'active' => 1,
//     'archived' => 0,
//     'staff_id' => getStaffId($authUser),
// ]);