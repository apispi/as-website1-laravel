<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'category', 'format', 'duration',
        'level', 'modules_count', 'price', 'price_unit', 'badge',
        'topics', 'includes', 'instructor', 'instructor_role',
        'checkout_name', 'checkout_amount', 'stripe_payment_link', 'is_active', 'is_featured',
        'sort_order', 'cta_headline', 'cta_sub',
    ];

    protected function casts(): array
    {
        return [
            'topics'        => 'array',
            'includes'      => 'array',
            'is_active'     => 'boolean',
            'is_featured'   => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('title');
    }
}
