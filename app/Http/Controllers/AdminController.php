<?php
/**
 * This controller provides user management functionality in the administration panel.
 * It allows administrators to view all system users, search for users by various criteria,
 * and delete user accounts.
 *
 * The controller contains the following main functions:
 * - index(): Displays a paginated list of users with search and filtering capabilities.
 * - destroy(): Deletes the selected user from the system.
 *
 * Access to these functions is restricted to administrators only.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display a paginated list of users with search and filtering options.
     */
    public function index(Request $request)
    {
        // Ensure that only administrators can access this page
        if (auth()->user()->role->name !== 'admin') {
            abort(403);
        }

        // Load users together with their roles
        $query = User::with('role');

        // Apply search filters if a search term is provided
        if ($request->search) {

            $search = $request->search;
            $filter = $request->filter;

            switch ($filter) {

                // Search users by name
                case 'name':

                    $query->where('name', 'like', '%' . $search . '%');

                    break;

                // Search users by email address
                case 'email':

                    $query->where('email', 'like', '%' . $search . '%');

                    break;

                // Search users by account creation date
                case 'created_at':

                    $query->where('created_at', 'like', '%' . $search . '%');

                    break;

                // Search users by role name
                case 'role':

                    $query->whereHas('role', function ($q) use ($search) {

                        $q->where('name', 'like', '%' . $search . '%');

                    });

                    break;

                // Default search by name
                default:

                    $query->where('name', 'like', '%' . $search . '%');

                    break;

            }
        }

        // Paginate results and preserve query parameters
        $users = $query->paginate(5)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Delete the specified user account.
     */
    public function destroy(User $user)
    {
        // Ensure that only administrators can delete users
        if (auth()->user()->role->name !== 'admin') {
            abort(403);
        }

        // Prevent administrators from deleting their own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Jūs nevarat dzēst pats sevi');
        }

        // Remove the user from the database
        $user->delete();

        return back()->with('success', 'Lietotājs izdzēsts');
    }
}
