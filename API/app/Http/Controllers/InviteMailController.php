<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\InviteMail;
use App\Models\Member;
use Illuminate\Support\Str;

class InviteMailController extends Controller {

    public function memberInvite(Member $member) {
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
                'member' =>
                    [
                        'name' => $member->name,
                        'email' => $member->email,
                        'invite_token' => $invite_token,
                    ]
            ], 200);
        } catch (\Throwable $th) {
            // Log the exception for debugging
            \Log::error('Error sending email: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error sending email',
                'error' => $th->getMessage()
            ], 500);
        }
    }



}



// public function index() {
//     try {
//         $testMailData = [
//             'title' => 'Test Email From AllPHPTricks.com',
//             'body' => 'This is the body of test email.'
//         ];
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