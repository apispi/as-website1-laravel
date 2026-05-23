@extends('layouts.admin')

@section('title', $user->name . ' — Connectors - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-connectors-manage"
    data-props="{{ json_encode([
        'currentUser'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'           => csrf_token(),
        'subject'             => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        'userConnectors'      => $userConnectors->map(fn($uc) => [
            'id'           => $uc->id,
            'status'       => $uc->status,
            'connected_at' => $uc->connected_at,
            'connector'    => $uc->connector ? [
                'id'       => $uc->connector->id,
                'name'     => $uc->connector->name,
                'category' => $uc->connector->category,
                'icon'     => $uc->connector->icon,
                'is_oauth' => $uc->connector->is_oauth,
            ] : null,
        ]),
        'availableConnectors' => $availableConnectors->map(fn($c) => [
            'id'       => $c->id,
            'name'     => $c->name,
            'category' => $c->category,
            'icon'     => $c->icon,
        ]),
        'flashSuccess'        => session('success', ''),
        'flashError'          => session('error', ''),
    ]) }}">
</div>
@endsection
