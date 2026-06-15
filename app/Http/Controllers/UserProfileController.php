<?php
/**
 * This controller manages user profile functionality.
 * It allows users to view their profile, update their avatar,
 * and edit their personal biography.
 *
 * The controller contains the following main functions:
 * - show(): Displays the user's profile and created routes.
 * - updateAvatar(): Uploads and updates the user's profile picture.
 * - updateBio(): Updates the user's biography.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel_route;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile page and routes.
     */
    public function show()
    {
        // Retrieve all routes created by the authenticated user
        $routes = Travel_route::where('user_id', auth()->id())
            ->with(['photos', 'points', 'user'])
            ->get();

        return view('profile', compact('routes'));
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateAvatar(Request $request)
    {
        // Validate uploaded image
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // Delete the previous avatar if it exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store the new avatar image
        $path = $request->file('avatar')->store('avatars', 'public');

        // Save the new avatar path
        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Avatar updated!');
    }

    /**
     * Update the user's biography.
     */
    public function updateBio(Request $request)
    {
        // Validate biography input
        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // Update the biography field
        $user->bio = $validated['bio'];

        $user->save();

        return back()->with('success', 'Apraksts atjaunināts!');
    }
}
