<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.loans.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = str_contains($credentials['login'], '@') ? 'email' : 'phone';
        $user = User::where($loginField, $credentials['login'])->first();

        if ($user && $user->password && Hash::check($credentials['password'], $user->password)) {
            Auth::guard('web')->login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }

            if ($user->role === 'customer') {
                return redirect()->route('client.loans.index');
            }

            return redirect()->route('login')->withErrors(['login' => 'Role tidak dikenali.']);
        }

        return back()->withErrors([
            'login' => 'Kredensial tidak cocok atau akun belum terdaftar.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
            $request->session()->forget('client_customer_id');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda sudah logout.');
    }
}
