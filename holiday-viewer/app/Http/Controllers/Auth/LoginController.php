<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Make sure you have this blade
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validate form inputs
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if email is verified
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout(); // logout unverified user
                return back()->with('error', 'You must verify your email before logging in.');
            }

            // Login successful and verified
            return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
        }

        // Failed login
        return back()->with('error', 'Invalid credentials.');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'Logged out successfully.');
    }
}
