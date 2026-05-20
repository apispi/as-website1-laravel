<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'badge', 'rating',
        'users_count', 'price', 'category', 'is_featured',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rating'      => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
