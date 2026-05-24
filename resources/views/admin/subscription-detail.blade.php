@extends('layouts.admin')

@section('title', ($subscription->agent?->name ?? 'Agent') . ' — ' . ($subscription->user?->name ?? 'User') . ' - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="subscription-detail"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'subscription' => [
            'id'         => $subscription->id,
            'status'     => $subscription->status,
            'started_at' => $subscription->started_at,
            'expires_at' => $subscription->expires_at,
        ],
        'subUser' => $subscription->user ? [
            'id'       => $subscription->user->id,
            'name'     => $subscription->user->name,
            'email'    => $subscription->user->email,
            'is_admin' => $subscription->user->is_admin ?? false,
        ] : null,
        'agent' => $subscription->agent ? [
            'id'          => $subscription->agent->id,
            'name'        => $subscription->agent->name,
            'slug'        => $subscription->agent->slug,
            'badge'       => $subscription->agent->badge,
            'category'    => $subscription->agent->category,
            'price'       => $subscription->agent->price,
            'description' => $subscription->agent->description,
        ] : null,
        'agentConnectors' => $subscription->agent?->connectors->map(fn($c) => [
            'id'       => $c->id,
            'name'     => $c->name,
            'icon'     => $c->icon,
            'category' => $c->category,
            'is_oauth' => $c->is_oauth ?? false,
        ])->values() ?? [],
        'agentSkills' => $subscription->agent?->skills->map(fn($s) => [
            'id'       => $s->id,
            'name'     => $s->name,
            'category' => $s->category,
        ])->values() ?? [],
        'userConnectors' => $subscription->user?->userConnectors->map(fn($uc) => [
            'id'           => $uc->id,
            'connector_id' => $uc->connector_id,
            'status'       => $uc->status,
            'connected_at' => $uc->connected_at,
        ])->values() ?? [],
    ]) }}">
</div>
@endsection
