<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Models\Travel_route;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//test command
Artisan::command('beep', function () {
    $this->comment('beep!');
})->purpose('Beep command for beep!');

Artisan::command('fill', function(){
    User::factory()->count(10)->create();
    Travel_route::factory()->count(15)->create();
    $this->info('Filled users and travel_routes tables with test data.');
})->purpose('Test fill ');