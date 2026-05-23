<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'badge', 'rating',
        'users_count', 'price', 'category', 'is_featured',
        'is_active', 'sort_order',
        'features', 'includes', 'use_cases', 'pricing_plans', 'faqs',
        'target_audience', 'tagline', 'cta_headline', 'cta_sub', 'checkout_name',
    ];

    protected function casts(): array
    {
        return [
            'rating'        => 'decimal:2',
            'is_featured'   => 'boolean',
            'is_active'     => 'boolean',
            'features'      => 'array',
            'includes'      => 'array',
            'use_cases'     => 'array',
            'pricing_plans' => 'array',
            'faqs'          => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->orderBy('sort_order');
    }

    public function connectors()
    {
        return $this->belongsToMany(Connector::class)->orderBy('sort_order')->orderBy('name');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
