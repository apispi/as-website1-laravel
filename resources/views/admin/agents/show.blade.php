@extends('layouts.admin')

@section('title', $agent->name . ' - Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agent-detail-admin"
    data-props="{{ json_encode([
        'user'       => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'  => csrf_token(),
        'agent'      => $agent,
        'skills' => $agent->skills->map(fn($s) => [
            'id'               => $s->id,
            'name'             => $s->pivot->name ?: $s->name,
            'description'      => $s->pivot->description ?: $s->description,
            'category'         => $s->pivot->category ?: $s->category,
            'refreshed_at'     => $s->pivot->refreshed_at,
            'catalog_name'     => $s->name,
            'catalog_desc'     => $s->description,
            'catalog_category' => $s->category,
        ])->values(),
        'connectors'    => $agent->connectors->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'icon' => $c->icon, 'category' => $c->category, 'is_oauth' => $c->is_oauth ?? false])->values(),
        'subscriptions' => $subscriptions->map(fn($s) => [
            'id'         => $s->id,
            'status'     => $s->status,
            'started_at' => $s->started_at,
            'expires_at' => $s->expires_at,
            'user'       => $s->user ? ['id' => $s->user->id, 'name' => $s->user->name, 'email' => $s->user->email] : null,
        ])->values(),
    ]) }}">
</div>
@endsection
