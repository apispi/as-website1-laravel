<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GraphService;
use Illuminate\Support\Facades\Auth;

class AzurePoliciesController extends Controller
{
    public function index(GraphService $graph)
    {
        $policies = $graph->conditionalAccessPolicies();
        $roles    = $graph->directoryRoles();
        $roleMap  = config('azure.role_map', []);
        $tenant   = config('services.azure.tenant', 'common');
        $ready    = $tenant !== 'common' && config('services.azure.client_id');

        return view('admin.azure.policies', [
            'user'      => Auth::user(),
            'policies'  => $policies,
            'roles'     => $roles,
            'roleMap'   => $roleMap,
            'tenant'    => $tenant,
            'ready'     => $ready,
        ]);
    }
}
