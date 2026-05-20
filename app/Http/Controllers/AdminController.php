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

            $search = $request->search;
            $filter = $request->filter;

            switch ($filter) {

                // Meklēšana pēc vārda
                case 'name':

                    $query->where('name', 'like', '%' . $search . '%');

                    break;

                // Meklēšana pēc e-pasta
                case 'email':

                    $query->where('email', 'like', '%' . $search . '%');

                    break;

                // Meklēšana pēc izveides datuma
                case 'created_at':

                    $query->where('created_at', 'like', '%' . $search . '%');

                    break;

                // Meklēšana pēc lomas
                case 'role':

                    $query->whereHas('role', function ($q) use ($search) {

                        $q->where('name', 'like', '%' . $search . '%');

                    });

                    break;

                // Noklusējuma meklēšana
                default:

                    $query->where('name', 'like', '%' . $search . '%');

                    break;

            }

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
