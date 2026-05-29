<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use App\Models\Agent;
use App\Models\Subscription;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            ActivityLog::log('login', 'Logged in');
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        ActivityLog::log('register', 'Created account', $user->id);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLog::log('logout', 'Logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function dashboard()
    {
        $subscriptions = Auth::user()
            ->subscriptions()
            ->with('agent')
            ->where('status', 'active')
            ->latest('started_at')
            ->get();

        return view('auth.dashboard', compact('subscriptions'));
    }

    public function catalog()
    {
        $agents         = Agent::active()->orderBy('sort_order')->get();
        $userConnectors = Auth::user()
            ->userConnectors()
            ->with('connector')
            ->latest('connected_at')
            ->get();
        $assignedIds         = $userConnectors->pluck('connector_id');
        $availableConnectors = \App\Models\Connector::where('is_active', true)
            ->whereNotIn('id', $assignedIds)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category', 'icon', 'is_oauth']);

        return view('auth.catalog', compact('agents', 'userConnectors', 'availableConnectors'));
    }

    public function userAgents()
    {
        $subscriptions = Auth::user()
            ->subscriptions()
            ->with('agent')
            ->latest('started_at')
            ->get();

        return view('auth.agents', compact('subscriptions'));
    }

    public function connectApiConnector(\App\Models\Connector $connector)
    {
        abort_if($connector->is_oauth, 400);

        $userConnector = Auth::user()->userConnectors()
            ->firstOrCreate(
                ['connector_id' => $connector->id],
                ['status' => 'active', 'connected_at' => now()]
            );

        return redirect()->route('dashboard.connectors.edit', $userConnector);
    }

    public function editConnector(\App\Models\UserConnector $userConnector)
    {
        abort_if($userConnector->user_id !== Auth::id(), 403);
        $userConnector->load('connector');
        return view('auth.connector-edit', compact('userConnector'));
    }

    public function updateConnector(Request $request, \App\Models\UserConnector $userConnector)
    {
        abort_if($userConnector->user_id !== Auth::id(), 403);
        $userConnector->load('connector');

        $schema = $userConnector->connector->config_schema ?? [];
        $config = $userConnector->config ?? [];

        foreach ($schema as $field) {
            $key  = $field['key'] ?? null;
            $type = $field['type'] ?? 'text';
            if (! $key) continue;

            $value = $request->input("config.{$key}");
            if ($type === 'password' && $value === '' && isset($config[$key])) {
                // keep existing
            } else {
                $config[$key] = $value;
            }
        }

        $userConnector->update([
            'config' => $config ?: null,
            'notes'  => $request->input('notes') ?: null,
        ]);
        ActivityLog::log('connector.config', "Updated details for {$userConnector->connector->name}");

        return redirect()->route('dashboard.connectors')->with('success', "{$userConnector->connector->name} configuration saved.");
    }

    public function userConnectors()
    {
        $userConnectors = Auth::user()
            ->userConnectors()
            ->with('connector')
            ->latest('connected_at')
            ->get();

        $assignedIds         = $userConnectors->pluck('connector_id');
        $availableConnectors = \App\Models\Connector::where('is_active', true)
            ->whereNotIn('id', $assignedIds)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category', 'icon', 'is_oauth']);

        return view('auth.connectors', compact('userConnectors', 'availableConnectors'));
    }

    public function userAgent(Subscription $subscription)
    {
        abort_if($subscription->user_id !== Auth::id(), 403);
        $subscription->load(['agent.connectors', 'skills']);

        $connectorIds = $subscription->agent?->connectors->pluck('id') ?? collect();
        $userConnectors = Auth::user()
            ->userConnectors()
            ->whereIn('connector_id', $connectorIds)
            ->get(['connector_id', 'status', 'connected_at'])
            ->keyBy('connector_id');

        return view('auth.agent', compact('subscription', 'userConnectors'));
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'     => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'avatar'   => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
            ]
        );

        Auth::login($user, true);
        ActivityLog::log('login.google', 'Logged in via Google');

        return redirect()->intended(route('dashboard'));
    }

    public function aria()
    {
        return view('auth.aria');
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        Auth::user()->update(['name' => $request->name]);
        ActivityLog::log('profile.update', 'Updated profile name');
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        ActivityLog::log('password.change', 'Changed password');
        return back()->with('success', 'Password updated successfully.');
    }
}
