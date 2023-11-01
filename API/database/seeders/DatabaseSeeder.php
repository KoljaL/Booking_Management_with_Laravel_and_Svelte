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
    public function run() {

        // the order of seeding is important,because of the foreign key constraints
        // first create locations,
        // then create create the staff users,
        // then create the member users,
        // then create the bookings

        // first create locations, 
        Location::factory(5)->create();

        // then create create an admin user, 
        Staff::create([
            'user_id' => \App\Models\User::create([
                'name' => 'Admin of the system',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ])->id,
            'is_admin' => true,
            'phone' => '0123456789',
            'location_id' => 1,
        ]);

        // now create one normal staff user
        Staff::create([
            'user_id' => \App\Models\User::create([
                'name' => 'Staff on duty',
                'email' => 'staff@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ])->id,
            'is_admin' => false,
            'phone' => '0123456789',
            'location_id' => 1,
        ]);

        // now create 5 more staff users
        Staff::factory(5)->create();

        // now create one member user
        Member::create([
            'user_id' => \App\Models\User::create([
                'name' => 'Member of the system',
                'email' => 'member@example.com',
                'password' => bcrypt('password'),
                'role' => 'member',
            ])->id,
            'location_id' => 1,
            'staff_id' => 2,
            'phone' => '0123456789',
            'jc_number' => '1234567890',
            'max_booking' => 5,
            'active' => true,
            'archived' => false,
        ]);

        // now create 5 more member users
        Member::factory(15)->create();

        // at last, create 5 bookings
        Booking::factory(50)->create();
    }
}