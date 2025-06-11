<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel_route>
 */
class Travel_routeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->realtext(100),
            'country_name' => fake()->country(),
            'city_name' => fake()->city(),
            'start_location' => '56.951332440328 656, 24.112974959604376',
            'end_location' => '56.951332440328600, 24.112974959604300',
            'user_id'=> 1
        ];
    }
}
