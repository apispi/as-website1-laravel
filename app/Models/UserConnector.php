<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConnector extends Model
{
    protected $fillable = [
        'user_id', 'connector_id', 'status', 'connected_at', 'disconnected_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'connected_at'    => 'datetime',
            'disconnected_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function connector(): BelongsTo { return $this->belongsTo(Connector::class); }

    public function token(): ?ConnectorToken
    {
        return ConnectorToken::forUser($this->user_id, $this->connector->slug);
    }

    public function isActive(): bool { return $this->status === 'active'; }
}
