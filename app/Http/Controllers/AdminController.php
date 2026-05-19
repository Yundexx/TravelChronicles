<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403);
        }

        $query = User::with('role');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(5)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403);
        }

        // чтобы админ не удалил сам себя
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself');
        }

        $user->delete();

        return back()->with('success', 'User deleted');
    }
}
