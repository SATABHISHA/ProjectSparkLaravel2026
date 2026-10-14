<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminAuthController
{
    public function loginForm()
    {
        if (auth()->check()) return redirect()->route('admin.dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (! Auth::attempt(array_merge($credentials, ['is_admin' => true, 'is_active' => true]), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those credentials did not match an active administrator.'])->withInput($request->only('email'));
        }
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function forgotForm()
    {
        return view('auth.forgot-password');
    }

    public function forgot(Request $request, SmtpMailService $mailer)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $data['email'])->where('is_admin', true)->where('is_active', true)->first();
        if (! $user) return back()->with('status', 'If that email belongs to an active administrator, a reset link is on its way.');

        $token = Password::broker()->createToken($user);
        $url = route('admin.password.reset', ['token' => $token, 'email' => $user->email]);
        if ($mailer->sendResetLink($user->email, $url)) return back()->with('status', 'Reset link sent. Check your inbox.');
        return back()->with('status', 'SMTP is not configured yet. Use the local reset link below, then configure SMTP in Settings.')->with('reset_link', $url);
    }

    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->string('email')]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate(['token' => ['required'], 'email' => ['required', 'email'], 'password' => ['required', 'string', 'min:8', 'confirmed']]);
        $status = Password::broker()->reset($data, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => str()->random(60)])->save();
        });
        if ($status === Password::PASSWORD_RESET) return redirect()->route('admin.login')->with('status', 'Password reset successfully. You can sign in now.');
        return back()->withErrors(['email' => __($status)])->withInput($request->only('email'));
    }
}
