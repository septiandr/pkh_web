<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $villageName = Config::get('globals.village_name');
        $currentYear = Config::get('globals.currentYear');

        return view('auth/login', compact('villageName', 'currentYear'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $pengguna = Pengguna::where('username', $credentials['username'])->first();

        if ($pengguna && Hash::check($credentials['password'], $pengguna->password)) {
            Session::put('isLogin', true); // Store isLogin in the session
            return redirect()->route('home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['login' => 'Invalid username or password.']);
    }

    public function logout(Request $request)
    {
        Session::forget('isLogin'); // Remove isLogin from the session
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect()->route('login')->with('success', 'Logout successful!');
    }
}