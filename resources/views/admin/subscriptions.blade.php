@extends('layouts.admin')

@section('title', 'All Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="all-agents"
    data-props="{{ json_encode([
        'currentUser'   => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'     => csrf_token(),
        'subscriptions' => $subscriptions->map(fn($s) => [
            'id'         => $s->id,
            'status'     => $s->status,
            'started_at' => $s->started_at,
            'expires_at' => $s->expires_at,
            'user'       => $s->user ? ['id' => $s->user->id, 'name' => $s->user->name, 'email' => $s->user->email] : null,
            'agent'      => $s->agent ? [
                'id'       => $s->agent->id,
                'name'     => $s->agent->name,
                'badge'    => $s->agent->badge,
                'category' => $s->agent->category,
                'price'    => $s->agent->price,
            ] : null,
        ]),
    ]) }}">
</div>
@endsection
