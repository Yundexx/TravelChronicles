<?php
// app/Http/Controllers/FeedbackController.php
namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index($routeId)
    {
        return response()->json(
            Feedback::where('route_id', $routeId)->latest()->get(['feedback'])
        );
    }

    public function store(Request $request, $routeId)
    {
        $request->validate(['feedback' => 'required|string|max:1000']);
        Feedback::create([
            'route_id' => $routeId,
            'feedback' => $request->feedback,
        ]);
        return response()->json(['success' => true]);
    }
}