<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use Mail;
use App\Models\Member;
use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class InviteMailController extends \App\Http\Controllers\Controller {

    // own route for invide Member or Staff, parameter in controller or get route in controller?
    public function userInvite($userType, $id) {
        // get the route 
        try {
            if ($userType === 'member') {
                $user = Member::findOrFail($id);

            } elseif ($userType === 'staff') {
                $user = Staff::findOrFail($id);
            }
            $email = $user->user->email;
            $name = $user->name;
            // dd($user->user);
            // $user = $member->user;
            $invite_token = Str::random(60);
            $user->user->invite_token = $invite_token;
            $user->user->save();
            $user->save();

            // dd($user->toArray());

            $mailContent = /*HTML*/<<<EOT
            <h2>Hallo {$name}</h2>
            <p>To set your password, please click the button below.</p>
            <a href="http://localhost:3000/reset-password/{$invite_token}">Click here</a>
            EOT;

            Mail::html($mailContent, function ($message) use ($email) {
                $message->to($email)
                    ->subject('Login to your account');
                // ->from('test@wed.de')
                // ->replyTo('tes@web.de')
                // ->priority(3)
                // ->attach('pathToFile')
                // ->attachFromStorage('pathToFile', 'nameForFile', [
                //     'mime' => 'application/pdf',
                // ])
                // ->embed('pathToFile')
                // ->embedFromStorage('pathToFile', 'nameForFile')
                // ->bcc('test@werb.de')

            });


            return response()->json([
                'message' => 'Email has been sent.',
                'member' =>
                    [
                        'name' => $name,
                        'email' => $email,
                        'invite_token' => $invite_token,
                    ]
            ], 201);


        } catch (\Throwable $th) {
            // Log the exception for debugging
            return response()->json([
                'message' => 'InviteMail: Error sending email',
                'error' => $th->getMessage()
            ], 500);
        }
    }



}