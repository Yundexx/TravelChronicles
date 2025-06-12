<?php
// app/Http/Controllers/FeedbackController.php
namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index($routeId)
    {
        $feedbacks = Feedback::with('user')->where('route_id', $routeId)->get();
        return response()->json($feedbacks->map(function($fb) {
            return [
                'feedback' => $fb->feedback,
                'user' => $fb->user ? $fb->user->name : 'Unknown'
            ];
        }));
    }

    public function store(Request $request, $routeId)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'route_id' => $routeId,
            'user_id' => auth()->id(), // <-- set user_id from logged-in user
            'feedback' => $request->feedback,
        ]);

        return response()->json(['success' => true]);
    }
}