<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'agent_id', 'status', 'started_at', 'expires_at'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
    public function agent(): BelongsTo { return $this->belongsTo(Agent::class); }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'subscription_skill')
            ->withPivot(['name', 'description', 'category', 'refreshed_at']);
    }
}
