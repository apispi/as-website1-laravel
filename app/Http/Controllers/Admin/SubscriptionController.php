<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function userAgents(User $user)
    {
        $subscriptions = $user->subscriptions()->with('agent')->latest('started_at')->get();
        return view('admin.users.agents', compact('user', 'subscriptions'));
    }
}
