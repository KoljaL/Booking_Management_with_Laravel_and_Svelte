<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array {
        return [
            'member_id' => function () {
                return Member::inRandomOrder()->first()->id;
            },
            'location_id' => function (array $attributes) {
                return Member::find($attributes['member_id'])->location_id;
            },
            // 'staff_id' => function (array $attributes) {
            //     return Member::find($attributes['member_id'])->staff_id;
            // },
            'comment_staff' => fake()->colorName(),
            'comment_member' => fake()->emoji() . fake()->emoji() . fake()->emoji(),
            // 'date' => fake()->dateTimeBetween('-5 days', '+5 days'),
            // dateTime between -10 and +10 days and between 09:00 and 18:00
            // 'date' => fake()->dateTimeBetween('-10 days', '+10 days'),
            'date' => fake()->dateTimeBetween('-10 days', '+10 days')->setTime(rand(9, 17), rand(0, 59), rand(0, 59)),
            'slots' => fake()->numberBetween(1, 4),
            'state' => fake()->numberBetween(1, 3),
            'started_at' => '',
            'ended_at' => '',
            // 'time' => fake()->dateTimeBetween('09:00', '18:00')->format('H:00'),
            // 'started_at' => function (array $attributes) {
            //     return \Carbon\Carbon::parse($attributes['time'])->addMinutes(15);
            // },
            // 'ended_at' => function (array $attributes) {
            //     return \Carbon\Carbon::parse($attributes['started_at'])->addMinutes(15);
            // },
        ];
    }
}