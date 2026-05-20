<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Travel_route;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        $routes = Travel_route::where('user_id', auth()->id())
            ->with(['photos', 'points', 'user'])
            ->get();
        return view('profile', compact('routes'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 1MB max
        ]);

        $user = auth()->user();

        // удалить старый файл
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // сохранить новый
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Avatar updated!');
    }

    public function updateBio(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        $user->bio = $validated['bio'];

        $user->save();

        return back()->with('success', 'Apraksts atjaunināts!');
    }
}
