<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RailwaySystem;

class AuthController extends Controller
{
    public function showLogin()
    {
        $users = User::all();
        $systems = RailwaySystem::all();
        return view('auth.login', compact('users', 'systems'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $user->last_login = now();
            $user->save();

            // Auto-set active system to user's assigned system if not already set
            if ($user->assigned_system_code) {
                session(['active_system_code' => $user->assigned_system_code]);
            }

            return redirect()->intended(route('home'))->with('success', "Welcome back, {$user->name}! Successfully signed in as {$user->role}.");
        }

        return back()->withErrors([
            'username' => 'Invalid username or password provided.',
        ])->onlyInput('username');
    }

    public function quickLogin($username)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return redirect()->back()->with('error', "User '$username' not found.");
        }

        Auth::login($user);
        $user->last_login = now();
        $user->save();

        if ($user->assigned_system_code) {
            session(['active_system_code' => $user->assigned_system_code]);
        }

        return redirect()->back()->with('success', "Switched active account to {$user->name} ({$user->username} — {$user->role})!");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out successfully.');
    }

    public function switchSystem(Request $request, $code)
    {
        $system = RailwaySystem::where('code', $code)->first();
        if ($system) {
            session(['active_system_code' => $code]);
            return redirect()->back()->with('success', "Active Railway System switched to: {$system->name} (In-Charge: {$system->station_master_name})");
        }

        return redirect()->back()->with('error', "Railway System with code '$code' not found.");
    }
}
