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
        $startLat = fake()->latitude(56.94, 56.97);
        $startLng = fake()->longitude(24.09, 24.14);
        $endLat = fake()->latitude(56.94, 56.97);
        $endLng = fake()->longitude(24.09, 24.14);


        return [
            'name' => fake()->name(),
            'description' => fake()->realtext(100),
            'country_name' => 'Latvia',
            'city_name' => 'Riga',
            'start_location' => "{$startLat}, {$startLng}",
            'end_location' => "{$endLat}, {$endLng}",
            'user_id'=> 1
        ];
    }
}
