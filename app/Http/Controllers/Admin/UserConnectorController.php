<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Connector;
use App\Models\User;
use App\Models\UserConnector;
use Illuminate\Http\Request;

class UserConnectorController extends Controller
{
    public function index()
    {
        $userConnectors = UserConnector::with(['user', 'connector'])
            ->latest('connected_at')
            ->get();

        return view('admin.user-connectors.index', compact('userConnectors'));
    }

    public function userConnectors(User $user)
    {
        $userConnectors    = $user->userConnectors()->with('connector')->latest('connected_at')->get();
        $assignedIds       = $userConnectors->pluck('connector_id');
        $availableConnectors = Connector::where('is_active', true)
            ->whereNotIn('id', $assignedIds)
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'icon']);

        return view('admin.users.connectors', compact('user', 'userConnectors', 'availableConnectors'));
    }

    public function assign(Request $request, User $user)
    {
        $data = $request->validate([
            'connector_id' => 'required|exists:connectors,id',
            'status'       => 'required|in:active,disconnected',
        ]);

        UserConnector::updateOrCreate(
            ['user_id' => $user->id, 'connector_id' => $data['connector_id']],
            ['status' => $data['status'], 'connected_at' => now(), 'disconnected_at' => null]
        );

        return back()->with('success', 'Connector assigned successfully.');
    }

    public function revoke(User $user, UserConnector $userConnector)
    {
        $userConnector->delete();
        return back()->with('success', 'Connector removed from user.');
    }
}
