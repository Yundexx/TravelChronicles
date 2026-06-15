<?php
/**
 * This controller manages users' favorite routes.
 * It allows authenticated users to add routes to their favorites
 * or remove them from their favorites list.
 *
 * The controller contains one main function:
 * - toggle(): Adds a route to favorites if it is not already favorited,
 *             or removes it from favorites if it already exists.
 *
 * The function returns a JSON response indicating whether the route
 * is currently marked as a favorite.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Toggle the favorite status of a route for the authenticated user.
     */
    public function toggle($routeId)
    {
        // Get the ID of the currently authenticated user
        $userId = auth()->id();

        // Check if the route is already in the user's favorites
        $exists = Favorite::where('user_id', $userId)
            ->where('route_id', $routeId)
            ->first();

        if ($exists) {
            // Remove the route from favorites
            $exists->delete();

            return response()->json(['favorited' => false]);
        } else {
            // Add the route to favorites
            Favorite::create([
                'user_id' => $userId,
                'route_id' => $routeId
            ]);

            return response()->json(['favorited' => true]);
        }
    }
}