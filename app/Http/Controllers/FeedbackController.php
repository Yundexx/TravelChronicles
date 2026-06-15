<?php
/**
 * This controller manages route feedback.
 * It allows users to view feedback associated with a specific route
 * and submit new feedback.
 *
 * The controller contains the following main functions:
 * - index(): Retrieves all feedback for the selected route.
 * - store(): Validates and saves new feedback submitted by a user.
 */

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display all feedback for the specified route.
     */
    public function index($routeId)
    {
        // Retrieve feedback with related user information
        $feedbacks = Feedback::with('user')->where('route_id', $routeId)->get();

        // Return feedback data as JSON
        return response()->json($feedbacks->map(function($fb) {
            return [
                'feedback' => $fb->feedback,
                'user' => $fb->user ? $fb->user->name : 'Unknown'
            ];
        }));
    }

    /**
     * Store a new feedback entry for the specified route.
     */
    public function store(Request $request, $routeId)
    {
        // Validate feedback input
        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        // Create a new feedback record
        Feedback::create([
            'route_id' => $routeId,

            // Associate feedback with the currently authenticated user
            'user_id' => auth()->id(),

            'feedback' => $request->feedback,
        ]);

        return response()->json(['success' => true]);
    }
}