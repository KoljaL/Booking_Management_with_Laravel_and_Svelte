<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        Staff::factory(5)->create();
    }
}
