<?php

namespace App\Services;

use App\Models\Connector;
use App\Models\ConnectorToken;
use Illuminate\Support\Facades\Http;

class OAuthService
{
    public function authorizationUrl(Connector $connector, string $state, string $redirectUri): string
    {
        $params = array_merge($connector->oauth_extra_params ?? [], [
            'client_id'     => $connector->oauth_client_id,
            'response_type' => 'code',
            'redirect_uri'  => $redirectUri,
            'scope'         => $connector->oauth_scopes,
            'state'         => $state,
        ]);

        return $connector->oauth_auth_url . '?' . http_build_query($params);
    }

    public function exchangeCode(Connector $connector, int $userId, string $code, string $redirectUri): ConnectorToken
    {
        $response = Http::asForm()->post($connector->oauth_token_url, [
            'client_id'     => $connector->oauth_client_id,
            'client_secret' => $connector->oauth_client_secret,
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $redirectUri,
            'scope'         => $connector->oauth_scopes,
        ]);

        $response->throw();

        return $this->upsertToken($connector->slug, $userId, $response->json(), $connector->oauth_scopes);
    }

    public function refreshToken(Connector $connector, ConnectorToken $token): ConnectorToken
    {
        $response = Http::asForm()->post($connector->oauth_token_url, [
            'client_id'     => $connector->oauth_client_id,
            'client_secret' => $connector->oauth_client_secret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $token->refresh_token,
            'scope'         => $connector->oauth_scopes,
        ]);

        $response->throw();

        return $this->upsertToken($connector->slug, $token->user_id, $response->json(), $connector->oauth_scopes);
    }

    public function getValidToken(Connector $connector, int $userId): ?ConnectorToken
    {
        $token = ConnectorToken::forUser($userId, $connector->slug);

        if (! $token) {
            return null;
        }

        if ($token->isExpired() && $token->refresh_token) {
            $redirectUri = route('connectors.callback', $connector->slug);
            $token = $this->refreshToken($connector, $token);
        }

        return $token;
    }

    private function upsertToken(string $slug, int $userId, array $data, string $defaultScopes): ConnectorToken
    {
        $expiresAt = isset($data['expires_in'])
            ? now()->addSeconds((int) $data['expires_in'] - 60)
            : null;

        return ConnectorToken::updateOrCreate(
            ['user_id' => $userId, 'connector_slug' => $slug],
            [
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at'    => $expiresAt,
                'scopes'        => $data['scope'] ?? $defaultScopes,
            ]
        );
    }
}
