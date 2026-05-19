<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route; // or the correct model name

class RouteController extends Controller
{
    public function destroy(Travel_route $route)
    {
        // Optionally, check if the user owns the route
        if (auth()->id() !== $route->user_id) {
            abort(403);
        }

        $route->delete();

        return redirect()->route('profile')->with('status', 'Route deleted!');
    }

    public function update(Request $request, Travel_route $route)
    {
        // Optionally, check if the user owns the route
        if (auth()->id() !== $route->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_name' => 'nullable|string|max:255',
        ]);

        $route->update($validated);

        return redirect()->route('profile')->with('status', 'Route updated!');
    }
}
