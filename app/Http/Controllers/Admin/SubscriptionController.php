<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function userAgents(User $user)
    {
        $subscriptions   = $user->subscriptions()->with('agent')->latest('started_at')->get();
        $assignedIds     = $subscriptions->pluck('agent_id');
        $availableAgents = Agent::where('is_active', true)
                                ->whereNotIn('id', $assignedIds)
                                ->orderBy('name')
                                ->get(['id', 'name', 'price', 'badge']);

        return view('admin.users.agents', compact('user', 'subscriptions', 'availableAgents'));
    }

    public function assign(Request $request, User $user)
    {
        $data = $request->validate([
            'agent_id'   => 'required|exists:agents,id',
            'status'     => 'required|in:active,cancelled,expired',
            'expires_at' => 'nullable|date',
        ]);

        Subscription::create([
            'user_id'    => $user->id,
            'agent_id'   => $data['agent_id'],
            'status'     => $data['status'],
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return back()->with('success', 'Agent assigned successfully.');
    }

    public function revoke(User $user, Subscription $subscription)
    {
        $subscription->delete();
        return back()->with('success', 'Agent removed from user.');
    }
}
