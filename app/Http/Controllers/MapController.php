<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route;

class MapController extends Controller
{
    public function index()
    {
        $routes = Travel_route::all();
        return view('map', compact('routes'));
    }
}
