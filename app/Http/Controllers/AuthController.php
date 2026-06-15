<?php
/**
 * This controller provides user authentication functionality.
 * It allows users to register, log in to the system, and log out.
 *
 * The controller contains the following main functions:
 * - showRegister(): Displays the registration form.
 * - showLogin(): Displays the login form.
 * - register(): Handles new user registration.
 * - login(): Processes user authentication.
 * - logout(): Logs the user out of the system.
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display the user registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Display the user login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Register a new user account.
     */
    public function register(Request $request)
    {
        // Validate registration form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user record
        $user = User::create($validated);

        // Automatically log in the newly registered user
        Auth::login($user);

        return redirect()->route('show.login')->with('success', 'Reģistrācija veiksmīga');
    }

    /**
     * Authenticate a user and create a new session.
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($validated)) {

            // Regenerate the session ID to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Ielogošanās veiksmīga!');
        }

        // Throw a validation exception if authentication fails
        throw ValidationException::withMessages([
            'credentials' => 'Nederīgi akreditācijas dati. Lūdzu, mēģiniet vēlreiz.'
        ]);
    }

    /**
     * Log out the currently authenticated user.
     */
    public function logout(Request $request)
    {
        // Remove the user's authentication session
        Auth::logout();

        // Invalidate the current session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('show.login')->with('success', 'Ielogojies veiksmīgi!');
    }
}
