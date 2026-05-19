<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route;
use App\Models\RoutePhoto;
use App\Models\RoutePoint;
use App\Models\Tag; 

use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = Travel_route::with(['points', 'photos', 'user', 'tags']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('city_name', 'like', "%{$search}%")
                ->orWhere('country_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        $routes = $query->paginate(10);

        // 🔥 ВОТ ЭТА СТРОКА ВАЖНА
        $tags = Tag::all();

        return view('map', compact('routes', 'tags'));
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
        $points = json_decode($request->points, true);
        $request->merge(['points' => $points]);
        
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

        $validated['user_id'] = auth()->id();
    
        $route = Travel_route::create($validated);

        if ($request->has('tags')) {
            $route->tags()->sync($request->tags);
        }
        // сохраняем точки
        foreach ($request->points as $index => $point) {
            RoutePoint::create([
                'route_id' => $route->id,
                'latitude' => $point['lat'],
                'longitude' => $point['lng'],
                'order' => $index
            ]);
        }

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

    public function create()
    {
        $tags = Tag::all();

        return view('createroute', compact('tags'));
    }


}
