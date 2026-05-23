<?php

namespace App\Http\Controllers;

use App\Models\Connector;
use App\Models\ConnectorToken;
use App\Models\UserConnector;
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

        return redirect($this->oauth->authorizationUrl($connector, $state, route('connectors.callback', $slug)));
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
            $this->oauth->exchangeCode($connector, Auth::id(), $request->input('code'), route('connectors.callback', $slug));
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', "Failed to connect {$connector->name}: " . $e->getMessage());
        }

        UserConnector::updateOrCreate(
            ['user_id' => Auth::id(), 'connector_id' => $connector->id],
            ['status' => 'active', 'connected_at' => now(), 'disconnected_at' => null]
        );

        return redirect()->route('dashboard')->with('success', "{$connector->name} connected successfully.");
    }

    public function disconnect(string $slug)
    {
        $connector = Connector::where('slug', $slug)->where('is_active', true)->firstOrFail();

        ConnectorToken::where('user_id', Auth::id())
            ->where('connector_slug', $slug)
            ->delete();

        UserConnector::where('user_id', Auth::id())
            ->where('connector_id', $connector->id)
            ->update(['status' => 'disconnected', 'disconnected_at' => now()]);

        return redirect()->back()->with('success', "{$connector->name} disconnected.");
    }

    private function findOAuthConnector(string $slug): Connector
    {
        return Connector::where('slug', $slug)
            ->where('is_oauth', true)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
