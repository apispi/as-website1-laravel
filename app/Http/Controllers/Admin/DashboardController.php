<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'  => User::count(),
            'admin_users'  => User::where('is_admin', true)->count(),
            'new_today'    => User::whereDate('created_at', today())->count(),
            'new_this_week'=> User::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        $recentUsers = User::latest()->take(8)->get(['id', 'name', 'email', 'is_admin', 'created_at']);

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
