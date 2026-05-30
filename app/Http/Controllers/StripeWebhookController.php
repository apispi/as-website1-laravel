<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Agent;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        $payload       = $request->getContent();
        $sigHeader     = $request->header('Stripe-Signature', '');

        if ($webhookSecret) {
            if (! $this->verifySignature($payload, $sigHeader, $webhookSecret)) {
                return response('Invalid signature', 400);
            }
        }

        $event = json_decode($payload, true);
        if (! $event || ! isset($event['type'])) {
            return response('Invalid payload', 400);
        }

        if ($event['type'] === 'checkout.session.completed') {
            $this->handleSessionCompleted($event['data']['object'] ?? []);
        }

        return response('OK', 200);
    }

    private function handleSessionCompleted(array $session): void
    {
        if (($session['payment_status'] ?? '') !== 'paid') {
            return;
        }

        $userId  = $session['client_reference_id'] ?? null;
        $agentId = $session['metadata']['agent_id'] ?? null;
        $type    = $session['metadata']['type'] ?? 'agent';

        if (! $userId || ! $agentId || $type === 'training') {
            return;
        }

        $user  = User::find($userId);
        $agent = Agent::find($agentId);

        if (! $user || ! $agent) {
            Log::warning('Stripe webhook: unknown user or agent', compact('userId', 'agentId'));
            return;
        }

        $existing = Subscription::where('user_id', $user->id)
            ->where('agent_id', $agent->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return;
        }

        Subscription::create([
            'user_id'    => $user->id,
            'agent_id'   => $agent->id,
            'status'     => 'active',
            'started_at' => now(),
        ]);

        ActivityLog::log(
            'subscription.created',
            "Subscribed to {$agent->name} via Stripe",
            $user->id,
            null,
            ['stripe_session_id' => $session['id'] ?? null]
        );
    }

    private function verifySignature(string $payload, string $sigHeader, string $secret): bool
    {
        $parts     = [];
        foreach (explode(',', $sigHeader) as $part) {
            [$k, $v]    = array_pad(explode('=', $part, 2), 2, '');
            $parts[$k][] = $v;
        }

        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];

        if (! $timestamp || empty($signatures)) {
            return false;
        }

        // Reject events older than 5 minutes
        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        foreach ($signatures as $sig) {
            if (hash_equals($expected, $sig)) {
                return true;
            }
        }

        return false;
    }
}
