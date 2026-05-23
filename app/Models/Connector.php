<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connector extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'category', 'icon', 'website_url',
        'is_active', 'sort_order',
        'is_oauth', 'oauth_client_id', 'oauth_client_secret',
        'oauth_auth_url', 'oauth_token_url', 'oauth_scopes', 'oauth_extra_params',
    ];

    protected function casts(): array
    {
        return [
            'is_active'         => 'boolean',
            'is_oauth'          => 'boolean',
            'oauth_client_secret' => 'encrypted',
            'oauth_extra_params'  => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function tokens()
    {
        return $this->hasMany(ConnectorToken::class, 'connector_slug', 'slug');
    }

    public function tokenForUser(int $userId): ?ConnectorToken
    {
        return $this->tokens()->where('user_id', $userId)->first();
    }
}
