<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequiresAzureRole
{
    /**
     * Gate access based on roles stored in users.azure_roles (synced from Entra on login).
     *
     * Usage: Route::middleware('azure.role:admin')
     *        Route::middleware('azure.role:admin,agent_user')  // any of these roles passes
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $userRoles = $user->azure_roles ?? [];

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Access requires the ' . implode(' or ', $roles) . ' role in your Microsoft tenant.');
    }
}
