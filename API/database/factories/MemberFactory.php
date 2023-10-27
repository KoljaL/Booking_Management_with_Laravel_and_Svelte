<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Member;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory {
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
            'location_id' => function () {
                return \App\Models\Location::inRandomOrder()->first()->id;
            },

            'jc_number' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'max_booking' => $this->faker->numberBetween(1, 10),
            'active' => $this->faker->boolean,
            'archived' => $this->faker->boolean,
            'staff_id' => function (array $attributes) {
                return \App\Models\Staff::where('location_id', $attributes['location_id'])->inRandomOrder()->first()->id ?? \App\Models\Staff::inRandomOrder()->first()->id;
            },
        ];
    }
    public function configure(): MemberFactory {
        return $this->afterCreating(function (Member $member) {
            $member->user->update(['role' => 'member']);
        });
    }
}