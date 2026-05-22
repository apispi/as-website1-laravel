<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get(['id', 'name', 'email', 'is_admin', 'created_at']);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own admin status.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $verb = $user->is_admin ? 'Granted admin role to' : 'Removed admin role from';
        ActivityLog::log('admin.toggle', "{$verb} {$user->name}", $user->id, Auth::id());

        return back()->with('success', $user->name . ' is now ' . ($user->is_admin ? 'an admin' : 'a regular user') . '.');
    }
}
