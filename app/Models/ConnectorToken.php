<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectorToken extends Model
{
    protected $fillable = [
        'user_id', 'connector_slug', 'access_token', 'refresh_token', 'expires_at', 'scopes',
    ];

    protected function casts(): array
    {
        return [
            'expires_at'   => 'datetime',
            'access_token' => 'encrypted',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function connector()
    {
        return $this->belongsTo(Connector::class, 'connector_slug', 'slug');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public static function forUser(int $userId, string $slug): ?self
    {
        return static::where('user_id', $userId)->where('connector_slug', $slug)->first();
    }
}
