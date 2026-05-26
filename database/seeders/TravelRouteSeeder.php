<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Travel_route;
use App\Models\RoutePoint;
use App\Models\RoutePhoto;
use App\Models\Tag;
use App\Models\Feedback;
use App\Models\User;

class TravelRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем первого пользователя
        $user = User::first();

        // Если пользователей нет — создаём
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Создание маршрута
        $route = Travel_route::create([
            'name' => 'Riga Old Town Walk',
            'description' => 'A beautiful walking route through the historical center of Riga.',
            'country_name' => 'Latvia',
            'city_name' => 'Riga',
            'user_id' => $user->id,
            'flagged' => false,
        ]);

        // Точки маршрута
        $points = [
            [
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'order' => 1,
            ],
            [
                'latitude' => 56.9475,
                'longitude' => 24.1090,
                'order' => 2,
            ],
            [
                'latitude' => 56.9459,
                'longitude' => 24.1125,
                'order' => 3,
            ],
        ];

        foreach ($points as $point) {
            RoutePoint::create([
                'route_id' => $route->id,
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude'],
                'order' => $point['order'],
            ]);
        }

        // Фото маршрута
        RoutePhoto::create([
            'route_id' => $route->id,
            'photo_path' => 'route_photos/demo-route.jpg',
        ]);

        // Теги
        $tags = ['Historical', 'City', 'Walking'];

        foreach ($tags as $tagName) {

            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);

            $route->tags()->attach($tag->id);
        }

        // Отзыв
        Feedback::create([
            'route_id' => $route->id,
            'user_id' => $user->id,
            'feedback' => 'Amazing route with beautiful architecture and atmosphere!',
        ]);
    }
}