@extends('layouts.agent-detail')

@section('title', ($subscription->agent->name ?? 'Agent') . ' - ApiSpi')

@section('content')
<div
    id="agent-detail-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-subscription="{{ json_encode([
        'id'         => $subscription->id,
        'status'     => $subscription->status,
        'started_at' => $subscription->started_at,
        'expires_at' => $subscription->expires_at,
        'agent'      => $subscription->agent ? [
            'id'          => $subscription->agent->id,
            'name'        => $subscription->agent->name,
            'slug'        => $subscription->agent->slug,
            'description' => $subscription->agent->description,
            'badge'       => $subscription->agent->badge,
            'price'       => $subscription->agent->price,
            'category'    => $subscription->agent->category,
            'connectors'  => $subscription->agent->connectors->map(fn($c) => [
                'id'           => $c->id,
                'name'         => $c->name,
                'slug'         => $c->slug,
                'category'     => $c->category,
                'description'  => $c->description,
                'website_url'  => $c->website_url,
                'icon'         => $c->icon,
                'is_oauth'     => $c->is_oauth,
                'user_status'  => $userConnectors->get($c->id)?->status ?? null,
                'connected_at' => $userConnectors->get($c->id)?->connected_at?->toDateString() ?? null,
            ])->values(),
        ] : null,
        'skills' => $subscription->skills->map(fn($s) => [
            'id'          => $s->id,
            'name'        => $s->pivot->name ?: $s->name,
            'category'    => $s->pivot->category ?: $s->category,
            'description' => $s->pivot->description ?: $s->description,
        ])->values(),
    ]) }}"
></div>
@endsection
