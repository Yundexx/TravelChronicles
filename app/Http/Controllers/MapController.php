<?php
/**
 * This controller manages route-related functionality on the map page.
 * It allows users to browse routes, search and filter them by tags,
 * create new routes, and mark routes with a flag status.
 *
 * The controller contains the following main functions:
 * - index(): Displays routes with search and filtering capabilities.
 * - toggleFlag(): Toggles the flagged status of a route.
 * - store(): Validates and stores a new route with its points, tags, and photos.
 * - create(): Displays the route creation form.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route;
use App\Models\RoutePhoto;
use App\Models\RoutePoint;
use App\Models\Tag;

use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    /**
     * Display all routes with optional search and tag filtering.
     */
    public function index(Request $request)
    {
        // Load routes together with their related data
        $query = Travel_route::with(['points', 'photos', 'user', 'tags']);

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('city_name', 'like', "%{$search}%")
                ->orWhere('country_name', 'like', "%{$search}%");
            });
        }

        // Filter routes by selected tag
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        // Paginate routes and retrieve all available tags
        $routes = $query->paginate(10);
        $tags = Tag::all();

        return view('map', compact('routes', 'tags'));
    }

    /**
     * Toggle the flagged status of a route.
     */
    public function toggleFlag($routeId)
    {
        // Find the route or return a 404 error
        $route = Travel_route::findOrFail($routeId);

        // Reverse the current flag state
        $route->flagged = !$route->flagged;
        $route->save();

        return response()->json(['flagged' => $route->flagged]);
    }

    /**
     * Validate and store a new travel route.
     */
    public function store(Request $request)
    {
        // Decode route points received as JSON
        $points = json_decode($request->points, true);
        $request->merge(['points' => $points]);

        // Validate route data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'country_name' => 'required|string|max:255',
            'city_name' => 'nullable|string|max:255',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'points' => 'required|array|max:25',
            'points.*.lat' => 'required|numeric',
            'points.*.lng' => 'required|numeric',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        // Associate the route with the authenticated user
        $validated['user_id'] = auth()->id();

        // Create the route record
        $route = Travel_route::create($validated);

        // Attach selected tags to the route
        if ($request->has('tags')) {
            $route->tags()->sync($request->tags);
        }

        // Save route points in their specified order
        foreach ($request->points as $index => $point) {
            RoutePoint::create([
                'route_id' => $route->id,
                'latitude' => $point['lat'],
                'longitude' => $point['lng'],
                'order' => $index
            ]);
        }

        // Upload and save route photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('route_photos', 'public');

                RoutePhoto::create([
                    'route_id' => $route->id,
                    'photo_path' => $path
                ]);
            }
        }

        return redirect()->route('map')->with('success', 'Route created successfully!');
    }

    /**
     * Display the route creation form.
     */
    public function create()
    {
        // Retrieve all available tags
        $tags = Tag::all();

        return view('createroute', compact('tags'));
    }
}

