<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//test command
Artisan::command('beep', function () {
    $this->comment('beep!');
})->purpose('Beep command for beep!');