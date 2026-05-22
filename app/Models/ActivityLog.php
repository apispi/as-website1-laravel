<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'actor_id', 'action', 'description', 'ip_address', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'created_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public static function log(string $action, string $description, ?int $userId = null, ?int $actorId = null, ?array $metadata = null): void
    {
        static::create([
            'user_id'     => $userId ?? Auth::id(),
            'actor_id'    => $actorId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'metadata'    => $metadata,
        ]);
    }
}
