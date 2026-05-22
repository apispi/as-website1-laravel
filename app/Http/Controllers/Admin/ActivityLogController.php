<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'actor'])->latest('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('filter')) {
            match($filter) {
                'auth'         => $query->whereIn('action', ['login', 'logout', 'register']),
                'profile'      => $query->whereIn('action', ['profile.update', 'password.change']),
                'admin'        => $query->where('action', 'admin.toggle'),
                'subscription' => $query->where('action', 'like', 'subscription.%'),
                default        => null,
            };
        }

        $paginator = $query->paginate(50)->withQueryString();

        $logs = $paginator->map(fn($l) => [
            'id'          => $l->id,
            'action'      => $l->action,
            'description' => $l->description,
            'ip_address'  => $l->ip_address,
            'created_at'  => $l->created_at?->toISOString(),
            'user'        => $l->user ? ['id' => $l->user->id, 'name' => $l->user->name, 'email' => $l->user->email] : null,
            'actor'       => $l->actor ? ['id' => $l->actor->id, 'name' => $l->actor->name] : null,
        ])->values()->all();

        return view('admin.activity', [
            'logs'       => $logs,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'prev_url'     => $paginator->previousPageUrl(),
                'next_url'     => $paginator->nextPageUrl(),
            ],
            'filters' => [
                'search' => $request->input('search', ''),
                'filter' => $request->input('filter', ''),
            ],
        ]);
    }
}
