<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Agent;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function allAgents()
    {
        $subscriptions = Subscription::with(['user', 'agent'])
            ->latest('started_at')
            ->get();

        return view('admin.subscriptions', compact('subscriptions'));
    }

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

        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'agent_id'   => $data['agent_id'],
            'status'     => $data['status'],
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        $agentName = Agent::find($data['agent_id'])?->name ?? 'Agent';
        ActivityLog::log('subscription.assign', "Assigned {$agentName} to {$user->name}", $user->id, Auth::id());

        return redirect()->route('admin.users.show', $user)->with('success', 'Agent assigned successfully.')->with('active_tab', 'agents');
    }

    public function revoke(User $user, Subscription $subscription)
    {
        $agentName = $subscription->agent?->name ?? 'Agent';
        $subscription->delete();
        ActivityLog::log('subscription.revoke', "Removed {$agentName} from {$user->name}", $user->id, Auth::id());
        return redirect()->route('admin.users.show', $user)->with('success', 'Agent removed from user.')->with('active_tab', 'agents');
    }
}
