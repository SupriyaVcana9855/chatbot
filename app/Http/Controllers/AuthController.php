<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
                // Get the credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $request->session()->regenerate();

            return redirect()->intended('dashboard'); // Redirect to the intended page
        }

        // Authentication failed...
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function signup(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'max:15|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 2,

        ]);

        // Log the user in
        Auth::login($user);

        // Redirect to the intended page
        return redirect()->intended('dashboard');
    }
}
