<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\UserConnector;

class DashboardChatController extends Controller
{
    public function send(Request $request)
    {
        $key = 'dashboard-chat:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['error' => 'Too many messages. Please wait a moment.'], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'message'           => 'required|string|max:1000',
            'history'           => 'nullable|array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $userConnector = UserConnector::where('user_id', Auth::id())
            ->where('status', 'active')
            ->whereHas('connector', fn($q) => $q->where('slug', 'custom-chat-api'))
            ->first();

        if (! $userConnector) {
            return response()->json(['error' => 'No active chat connector configured.'], 422);
        }

        $config      = $userConnector->config ?? [];
        $endpointUrl = $config['endpoint_url'] ?? null;

        if (! $endpointUrl) {
            return response()->json(['error' => 'Chat connector endpoint URL is not set.'], 422);
        }

        $payload = [
            'message' => trim($request->input('message')),
            'history' => collect($request->input('history', []))->takeLast(10)->values()->toArray(),
        ];

        $httpRequest = Http::timeout(30)->acceptJson();

        $apiKey = $config['api_key'] ?? null;
        if ($apiKey) {
            $httpRequest = $httpRequest->withToken($apiKey);
        }

        $response = $httpRequest->post($endpointUrl, $payload);

        if ($response->failed()) {
            return response()->json(['error' => 'The connected chat API returned an error. Please try again.'], 502);
        }

        $reply = $response->json('reply') ?? $response->json('message') ?? $response->json('content');

        if (! $reply) {
            return response()->json(['error' => 'The connected API did not return a valid reply.'], 502);
        }

        return response()->json(['reply' => $reply]);
    }
}
