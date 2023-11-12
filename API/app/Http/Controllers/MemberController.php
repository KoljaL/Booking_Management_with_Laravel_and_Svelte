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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\InviteMail;
use Illuminate\Support\Benchmark;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller {



    /*
     * INDEX
     * 
     * Get selected data of all members from the same location as the logged in staff (authUser),
     * if the authUser is an admin, then get all members
     */
    public function index(Request $request) {
        // dd($request->all());
        try {
            $members = Member::byAccessLevel()->showMembers($request->show);
            $count_members = $members->count();
            return response()->json(['message' => $request->show . ' Members', 'count_members' => $count_members, 'members' => $members], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Members not found or no Members associated staff.', 'error' => $th], 404);
        }
    }


    /*
     * SHOW
     * 
     * Get selected data of a single member from the same location as the logged in staff (authUser),
     * including all bookings for this member
     */
    public function show(Request $request, $id) {
        try {
            $member = Member::withTrashed()->byAccessLevel()->withBookings($request->show)->findOrFail($id);
            $count_bookings = $member->bookings->count();

            return response()->json(['message' => 'Member data', 'show' => $request->show, 'count_bookings' => $count_bookings, 'member' => $member], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Member with ID ' . $id . ' not found', 'exception' => $e], 404);
        }
    }



    /*
     * STORE
     * Create a new User, add the user_id to the Member, and return the Member
     */
    public function store(Request $request) {
        // get the logged in Staff
        $authUser = Auth::user();

        // check if the logged in staff is an admin
        // and if the request has a location_id
        if ($authUser->isAdmin() && !$request->has('location_id')) {
            return response()->json(['message' => 'Only staff can create members.'], 404);
        }
        // if request empty 
        if (!$request->has('name') || !$request->has('email')) {
            return response()->json(['message' => 'Member not created, name and email required.'], 404);
        }

        // validate the request, email must be unique for users
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        DB::beginTransaction();
        try {
            // get the location_id, max_booking and staff_id
            $location = Location::find($authUser->staff->location_id);
            $location_id = $location->id;
            $max_booking = $location->max_booking;
            $staff_id = $authUser->staff->id;

            // first create a new user
            $user = User::create([
                'email' => $request->email,
                'role' => 'member',
            ]);
            // dd($user->id);

            // then create a new member
            $member = Member::create([
                'user_id' => $user->id,
                'staff_id' => $staff_id,
                'location_id' => $location_id,
                'max_booking' => $max_booking,
                'name' => $request->name,
                'phone' => $request->phone,
                'jc_number' => $request->jc_number,
            ]);

            // associate the member with the user
            $user->member()->save($member);
            DB::commit();

            // get the new member with the
            $newMember = Member::find($member->id);
            // return the member
            return response()->json(['message' => 'Member created', 'member' => $newMember], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error creating member', 'error' => $th], 500);
        }
    }






    /*
     * UPDATE
     * Update a Member
     */
    public function update(Request $request, $id) {
        $member = Member::withTrashed()->byAccessLevel()->findOrFail($id);
        // $member = Member::byAccessLevel()->findOrFail($id);
        $updateEmail = false;
        // dd($request->email, $member->user->email);

        // the email is a User attribute, so we need to check if it has changed
        if ($request->has('email') && $request->email != $member->user->email) {
            $updateEmail = true;
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ]);
            $userData = $request->only(['email']);
        }

        $memberData = $request->toArray();

        DB::beginTransaction();
        try {
            if ($updateEmail) {
                // update User
                $user = $member->user;
                $user->update($userData);
            }
            // update Member  
            $member->update($memberData);

            // try to update the member
            DB::commit();

            // return the member
            return response()->json(['message' => 'Member updated', 'member' => $member], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error update member', 'error' => $e], 500);
        }
    }

    /*
     * DESTROY
     * Delete a Member
     */
    public function destroy($id) {
        // get Member
        $member = Member::withTrashed()->byAccessLevel()->findOrFail($id);

        DB::beginTransaction();
        try {
            $member->active = false;
            $member->save();
            $oldMember = $member->toArray();


            // first delete all bookings for this member
            try {
                $member->bookings()->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting bookings', 'error' => $th], 500);
            }

            // then delete the member
            try {
                $member->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting member', 'error' => $th], 500);
            }

            // and finally delete the user
            try {
                $member->user()->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting user', 'error' => $th], 500);
            }

            DB::commit();

            return response()->json(['message' => 'Member deleted', 'data' => $oldMember], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Member not found', 'error' => $th], 404);
        }
    }


    /**
     * INVITE
     * Send a password reset link to the given user.
     */

    public function invite(Member $member) {
        try {
            $user = $member->user;
            $invite_token = Str::random(60);
            $user->invite_token = $invite_token;
            $user->save();
            $mailData = [
                'title' => 'Login to your account',
                'body' => 'To set your password, please click the button below.',
                'token' => $invite_token,
            ];

            Mail::to($member->email)->send(new InviteMail($mailData));
            return response()->json([
                'message' => 'Email has been sent.',
                'member' => $member,
            ], 200);
        } catch (\Throwable $th) {
            // Log the exception for debugging
            // \Log::error('Error sending email: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error sending email',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

/**
 * 
 * Just some helper functions
 * 
 */
// function getMyMembers($authUser) {
//     return $authUser->staff->members()->with('user')->get();
// }

// function isAdmin($authUser) {
//     return $authUser->staff->is_admin;
// }

// function getLocationId($authUser) {
//     return $authUser->staff->location_id;
// }

// function getStaffId($authUser) {
//     return $authUser->staff->id;
// }

// function getMaxBooking($location_id) {
//     $location = \App\Models\Location::find($location_id);
//     return $location->max_booking;
// }


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
//         if (isxAdmin($authUser) || $member->staff_id == $authUser->staff->id) {
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


// public function showxx(Member $member) {
//     // dd($member);
//     // return response()->json(['message' => 'HAAAALLLLOOOO.'], 200);
//     try {
//         // get the logged in user for the staff_id 
//         $authUser = Auth::user();

//         if (isxAdmin($authUser) || $member->staff_id == $authUser->staff->id) {
//             // Get all bookings for this member
//             $member->load('booking');
//             return $member;
//         }
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Member not found or no associated staff.'], 404);
//     }
//     return response()->json(['message' => 'Member not found or no associated staff.'], 404);
// }


// public function invite(Member $member) {
//     // dd($member);
//     try {
//         $user = $member->user;
//         $invite_token = Str::random(60);
//         $user->invite_token = $invite_token;
//         $user->save();
//         $mailData = [
//             'title' => 'Login to your account',
//             'body' => 'To set your password, please click the button below.',
//             'invite_token' => $invite_token,
//         ];


//         Mail::to('lasar@rasal.de')->send(new InviteMail($mailData));
//         return response()->json([
//             'message' => 'Email has been sent.'
//         ], 200);
//     } catch (\Throwable $th) {
//         return response()->json([
//             'message' => 'Error sending email',
//             'error' => $th
//         ], 500);
//     }
// }


// public function invite(Member $member) {

//     $user = $member->user;
//     $email = $user->email;
//     $invite_token = Str::random(60);
//     $user->invite_token = $invite_token;
//     $user->save();


//     $testMailData = [
//         'title' => 'Test Email From AllPHPTricks.com',
//         'body' => 'This is the body of test email.',
//         'invite_token' => $invite_token,
//     ];
//     // dd($email);
//     try {
//         $email = 'lasar@rasal.de';
//         Mail::to('lasar@rasal.de')->send(new InviteMail($testMailData));
//         return response()->json([
//             'message' => 'Email has been sent.'
//         ], 200);
//     } catch (\Throwable $th) {
//         return response()->json([
//             'message' => 'Error sending email',
//             'error' => $th
//         ], 500);
//     }
// }