@extends('layouts.dashboard-catalog-agent')

@section('title', $agent->name . ' - Catalog - ApiSpi')

@section('content')
<div
    id="dashboard-catalog-agent-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-agent="{{ json_encode([
        'id'                  => $agent->id,
        'name'                => $agent->name,
        'slug'                => $agent->slug,
        'category'            => $agent->category,
        'badge'               => $agent->badge,
        'tagline'             => $agent->tagline,
        'description'         => $agent->description,
        'price'               => $agent->price,
        'price_unit'          => $agent->price_unit,
        'rating'              => $agent->rating,
        'users_count'         => $agent->users_count,
        'is_featured'         => $agent->is_featured,
        'features'            => $agent->features ?? [],
        'includes'            => $agent->includes ?? [],
        'use_cases'           => $agent->use_cases ?? [],
        'stripe_payment_link' => $agent->stripe_payment_link,
        'checkout_amount'     => $agent->checkout_amount,
        'checkout_name'       => $agent->checkout_name,
        'skills'              => $agent->skills->map(fn($s) => [
            'id'          => $s->id,
            'name'        => $s->name,
            'category'    => $s->category,
            'description' => $s->description,
        ]),
    ]) }}"
></div>
@endsection
