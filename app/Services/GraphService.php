<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GraphService
{
    private string $clientId;
    private string $clientSecret;
    private string $tenantId;

    public function __construct()
    {
        $this->clientId     = config('services.azure.client_id', '');
        $this->clientSecret = config('services.azure.client_secret', '');
        $this->tenantId     = config('services.azure.tenant', 'common');
    }

    // App-only token via client credentials (requires specific tenant, not 'common')
    public function appToken(): ?string
    {
        if (! $this->clientId || ! $this->clientSecret || $this->tenantId === 'common') {
            return null;
        }

        $response = Http::asForm()->post(
            "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token",
            [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope'         => 'https://graph.microsoft.com/.default',
                'grant_type'    => 'client_credentials',
            ]
        );

        return $response->json('access_token');
    }

    // Groups and directory roles the signed-in user belongs to (delegated token)
    public function userGroups(string $userToken): array
    {
        $response = Http::withToken($userToken)
            ->get('https://graph.microsoft.com/v1.0/me/memberOf?$select=id,displayName,@odata.type');

        return $response->successful() ? ($response->json('value') ?? []) : [];
    }

    // App role assignments for the signed-in user (delegated token)
    public function userAppRoles(string $userToken): array
    {
        $response = Http::withToken($userToken)
            ->get('https://graph.microsoft.com/v1.0/me/appRoleAssignments');

        return $response->successful() ? ($response->json('value') ?? []) : [];
    }

    // Conditional Access policies (app-only, requires Policy.Read.All)
    public function conditionalAccessPolicies(): array
    {
        $token = $this->appToken();
        if (! $token) {
            return [];
        }

        $response = Http::withToken($token)
            ->get('https://graph.microsoft.com/v1.0/identity/conditionalAccess/policies');

        return $response->successful() ? ($response->json('value') ?? []) : [];
    }

    // All directory roles in the tenant (app-only, requires RoleManagement.Read.Directory)
    public function directoryRoles(): array
    {
        $token = $this->appToken();
        if (! $token) {
            return [];
        }

        $response = Http::withToken($token)
            ->get('https://graph.microsoft.com/v1.0/directoryRoles?$select=id,displayName,description');

        return $response->successful() ? ($response->json('value') ?? []) : [];
    }

    // Members of a specific directory role (app-only)
    public function roleMembersById(string $roleId): array
    {
        $token = $this->appToken();
        if (! $token) {
            return [];
        }

        $response = Http::withToken($token)
            ->get("https://graph.microsoft.com/v1.0/directoryRoles/{$roleId}/members?\$select=id,displayName,userPrincipalName");

        return $response->successful() ? ($response->json('value') ?? []) : [];
    }
}
