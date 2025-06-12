<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Travel_route;


class UserProfileController extends Controller
{
    public function show()
    {
        $routes = Travel_route::where('user_id', auth()->id())->get();
        return view('profile', compact('routes'));
    }
}
