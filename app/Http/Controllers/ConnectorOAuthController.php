<?php

namespace App\Http\Controllers;

use App\Models\Connector;
use App\Models\ConnectorToken;
use App\Services\OAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ConnectorOAuthController extends Controller
{
    public function __construct(private OAuthService $oauth) {}

    public function authorize(string $slug)
    {
        $connector = $this->findOAuthConnector($slug);

        $state = Str::random(40);
        session(["oauth_state_{$slug}" => $state]);

        $redirectUri = route('connectors.callback', $slug);

        return redirect($this->oauth->authorizationUrl($connector, $state, $redirectUri));
    }

    public function callback(Request $request, string $slug)
    {
        $connector = $this->findOAuthConnector($slug);

        if ($request->input('state') !== session("oauth_state_{$slug}")) {
            return redirect()->route('dashboard')->with('error', 'Invalid OAuth state. Please try again.');
        }

        if ($request->has('error')) {
            $desc = $request->input('error_description', 'Authorization was denied.');
            return redirect()->route('dashboard')->with('error', ucfirst($connector->name) . ': ' . $desc);
        }

        try {
            $redirectUri = route('connectors.callback', $slug);
            $this->oauth->exchangeCode($connector, Auth::id(), $request->input('code'), $redirectUri);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', "Failed to connect {$connector->name}: " . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', "{$connector->name} connected successfully.");
    }

    public function disconnect(string $slug)
    {
        ConnectorToken::where('user_id', Auth::id())
            ->where('connector_slug', $slug)
            ->delete();

        return redirect()->back()->with('success', 'Connector disconnected.');
    }

    private function findOAuthConnector(string $slug): Connector
    {
        return Connector::where('slug', $slug)
            ->where('is_oauth', true)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
