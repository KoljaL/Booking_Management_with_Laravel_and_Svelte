<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Staff;
use App\Models\Member;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */

    // public function ruddn() {
    //     // create two locations
    //     Location::create([
    //         'city' => 'Köln',
    //         'address' => 'Am Dom 1, 50667 Köln',
    //         'opening_hour_from' => 8,
    //         'opening_hour_to' => 18,
    //         'opening_days' => 'Mo-Fr',
    //         'phone' => '0123456789',
    //         'email' => 'köeln@example.com',
    //         'slot_duration' => 90,
    //         'max_booking' => 5,
    //         'workspaces' => 100,
    //         'imap_host' => 'imap.example.com',
    //         'imap_pw' => 'password',
    //     ]);

    //     Location::create([
    //         'city' => 'Berlin',
    //         'address' => 'Am Dom 1, 50667 Köln',
    //         'opening_hour_from' => 8,
    //         'opening_hour_to' => 18,
    //         'opening_days' => 'Mo-Fr',
    //         'phone' => '0123456789',
    //         'email' => 'berlin@example.com',
    //         'slot_duration' => 90,
    //         'max_booking' => 5,
    //         'workspaces' => 100,
    //         'imap_host' => 'imap.example.com',
    //         'imap_pw' => 'password',
    //     ]);

    //     // create an admin user
    //     Staff::create([
    //         'user_id' => \App\Models\User::create([
    //             'email' => 'admin@example.com',
    //             'password' => bcrypt('password'),
    //             'role' => 'staff',
    //         ])->id,
    //         'name' => 'Admin of the system',
    //         'is_admin' => true,
    //         'phone' => '0123456789',
    //         'location_id' => 1,
    //     ]);

    //     // now create one normal staff user
    //     Staff::create([
    //         'user_id' => \App\Models\User::create([
    //             'email' => 'staff@example.com',
    //             'password' => bcrypt('password'),
    //             'role' => 'staff',
    //         ])->id,
    //         'name' => 'Staff on duty',
    //         'is_admin' => false,
    //         'phone' => '0123456789',
    //         'location_id' => 2,
    //     ]);

    //     // now create one member user
    //     Member::create([
    //         'user_id' => \App\Models\User::create([
    //             'email' => 'member@example.com',
    //             'password' => bcrypt('password'),
    //             'role' => 'member',
    //         ])->id,
    //         'name' => 'Active Member',
    //         'location_id' => 1,
    //         'staff_id' => 2,
    //         'phone' => '0123456789',
    //         'jc_number' => '1234567890',
    //         'max_booking' => 5,
    //         'active' => true,
    //     ]);

    //     Member::create([
    //         'user_id' => \App\Models\User::create([
    //             'email' => 'member1@example.comn',
    //             'password' => bcrypt('password'),
    //             'role' => 'member',
    //         ])->id,
    //         'name' => 'Inactive Member',
    //         'location_id' => 2,
    //         'staff_id' => 2,
    //         'phone' => '0123456789',
    //         'jc_number' => '1234567890',
    //         'max_booking' => 5,
    //         'active' => false,
    //     ]);

    //     Booking::create([
    //         'member_id' => 1,
    //         'location_id' => 2,
    //         'staff_id' => 2,
    //         'date' => '2021-10-25',
    //         'time' => '09:00',
    //         'slots' => 1,
    //         'state' => 1,
    //         'started_at' => '2021-10-25 09:15:00',
    //         'ended_at' => '2021-10-25 09:30:00',
    //     ]);
    // }
    public function run() {

        // the order of seeding is important,because of the foreign key constraints
        // first create locations,
        // then create create the staff users,
        // then create the member users,
        // then create the bookings

        // first create locations, 
        Location::factory(3)->create();

        // then create create an admin user, 
        Staff::create([
            'user_id' => \App\Models\User::create([
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ])->id,
            'name' => 'Admin of the system',
            'is_admin' => true,
            'phone' => '0123456789',
            'location_id' => 1,
        ]);

        // now create one normal staff user
        Staff::create([
            'user_id' => \App\Models\User::create([
                'email' => 'staff@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ])->id,
            'name' => 'Staff on duty',
            'is_admin' => false,
            'phone' => '0123456789',
            'location_id' => 1,
        ]);

        // now create 5 more staff users
        // Staff::factory(2)->create();

        // now create one member user
        Member::create([
            'user_id' => \App\Models\User::create([
                'email' => 'member@example.com',
                'password' => bcrypt('password'),
                'role' => 'member',
            ])->id,
            'name' => 'Member of the system',
            'location_id' => 1,
            'staff_id' => 2,
            'phone' => '0123456789',
            'jc_number' => '1234567890',
            'max_booking' => 5,
            'active' => true,
        ]);

        // now create 5 more member users
        Member::factory(23)->create();

        // at last, create 5 bookings
        Booking::factory(1500)->create();
    }
}