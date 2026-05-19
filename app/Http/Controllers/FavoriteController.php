<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle($routeId)
    {
        $userId = auth()->id();

        $exists = Favorite::where('user_id', $userId)
            ->where('route_id', $routeId)
            ->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['favorited' => false]);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'route_id' => $routeId
            ]);
            return response()->json(['favorited' => true]);
        }
    }
}
