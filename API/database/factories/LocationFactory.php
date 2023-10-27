<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'city' => fake()->city,
            'address' => fake()->address,
            'opening_hour_from' => fake()->randomElement([8, 9, 10, 11, 12]),
            'opening_hour_to' => fake()->randomElement([16, 17, 18, 19, 20]),
            'opening_days' => fake()->randomElement(['1,2,3,4,5', '1,2,3,4,5,6', '1,2,3,4,5,6,7']),
            'phone' => fake()->phoneNumber,
            'email' => fake()->unique()->safeEmail,
            'slot_duration' => fake()->randomElement([90, 120, 150, 180, 210, 240, 270, 300, 330, 360]),
            'max_booking' => fake()->numberBetween(3, 5),
            'workspaces' => fake()->numberBetween(10, 20),
            'imap_host' => fake()->domainName,
            'imap_pw' => fake()->password,
        ];
    }
}
