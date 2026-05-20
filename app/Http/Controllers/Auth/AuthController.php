<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginView(): View
    {
        $page_title = 'Login';

        return view('auth.login', compact('page_title'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login successful.');
        }

        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => 'Invalid username or password.',
            ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully.');
    }

    public function forgotPasswordView(): View
    {
        $page_title = 'Forgot Password';

        return view('auth.forgot_password', compact('page_title'));
    }
}
