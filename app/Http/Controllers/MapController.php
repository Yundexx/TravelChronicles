<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route;

class MapController extends Controller
{
    public function index()
    {
        $routes = Travel_route::paginate(10); // Show 10 per page
        return view('map', compact('routes'));
    }
    public function toggleFlag($routeId)
    {
        $route = Travel_route::findOrFail($routeId);
        $route->flagged = !$route->flagged;
        $route->save();

        return response()->json(['flagged' => $route->flagged]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'country_name' => 'required|string|max:255',
            'city_name' => 'nullable|string|max:255' ?? '-',
            'start_location' => 'required|string',
            'end_location' => 'required|string'
        ]);

        $validated['user_id'] = auth()->id();

        Travel_route::create($validated);

        return redirect()->route('map')->with('success', 'Route created successfully!');
    }
}
