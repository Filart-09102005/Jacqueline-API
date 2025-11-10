<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * Only allows logged-in users who are registered in the system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        // Optional: ensure user exists in database (registered)
        $user = Auth::user();
        if (!$user || !User::where('id', $user->id)->exists()) {
            Auth::logout();
            return redirect()->route('login.form')->with('error', 'You must register first.');
        }

        return $next($request);
    }
}
