@extends('layouts.dashboard')

@section('title', 'Dashboard - ApiSpi')

@section('content')
<div
    id="dashboard-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'created_at' => Auth::user()->created_at, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-trainings="{{ json_encode($trainings->map(fn($t) => [
        'id'                  => $t->id,
        'title'               => $t->title,
        'slug'                => $t->slug,
        'category'            => $t->category,
        'format'              => $t->format,
        'duration'            => $t->duration,
        'level'               => $t->level,
        'price'               => $t->price,
        'price_unit'          => $t->price_unit,
        'badge'               => $t->badge,
        'is_featured'         => $t->is_featured,
        'stripe_payment_link' => $t->stripe_payment_link,
        'checkout_amount'     => $t->checkout_amount,
        'checkout_name'       => $t->checkout_name,
    ])) }}"
    data-subscriptions="{{ json_encode($subscriptions->map(fn($s) => [
        'id'         => $s->id,
        'status'     => $s->status,
        'started_at' => $s->started_at,
        'expires_at' => $s->expires_at,
        'agent'      => $s->agent ? [
            'id'          => $s->agent->id,
            'name'        => $s->agent->name,
            'slug'        => $s->agent->slug,
            'description' => $s->agent->description,
            'badge'       => $s->agent->badge,
            'price'       => $s->agent->price,
            'category'    => $s->agent->category,
        ] : null,
    ])) }}"
></div>
@endsection
