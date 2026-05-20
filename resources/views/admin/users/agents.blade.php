@extends('layouts.admin')

@section('title', $user->name . ' — Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-agents"
    data-props="{{ json_encode([
        'currentUser'     => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'       => csrf_token(),
        'subject'         => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        'subscriptions'   => $subscriptions->map(fn($s) => [
            'id'         => $s->id,
            'status'     => $s->status,
            'started_at' => $s->started_at,
            'expires_at' => $s->expires_at,
            'agent'      => $s->agent ? [
                'id'       => $s->agent->id,
                'name'     => $s->agent->name,
                'slug'     => $s->agent->slug,
                'badge'    => $s->agent->badge,
                'category' => $s->agent->category,
                'price'    => $s->agent->price,
            ] : null,
        ]),
        'availableAgents' => $availableAgents->map(fn($a) => [
            'id'    => $a->id,
            'name'  => $a->name,
            'price' => $a->price,
            'badge' => $a->badge,
        ]),
        'flashSuccess'    => session('success', ''),
        'flashError'      => session('error', ''),
    ]) }}">
</div>
@endsection
