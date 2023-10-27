<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;



class StaffFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'phone' => $this->faker->phoneNumber,
            'is_admin' => false,
            'location_id' => function () {
                return \App\Models\Location::inRandomOrder()->first()->id;
            },
        ];
    }

    public function configure(): StaffFactory {
        return $this->afterCreating(function (Staff $staff) {
            $staff->user->update(['role' => 'staff']);
        });
    }
}