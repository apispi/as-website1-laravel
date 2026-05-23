@extends('layouts.connectors-user')

@section('title', 'My Connectors - ApiSpi')

@section('content')
<div
    id="user-connectors-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-user-connectors="{{ json_encode($userConnectors->map(fn($uc) => [
        'id'           => $uc->id,
        'status'       => $uc->status,
        'connected_at' => $uc->connected_at,
        'connector'    => $uc->connector ? [
            'id'       => $uc->connector->id,
            'name'     => $uc->connector->name,
            'slug'     => $uc->connector->slug,
            'category' => $uc->connector->category,
            'icon'     => $uc->connector->icon,
            'is_oauth' => $uc->connector->is_oauth,
        ] : null,
    ])) }}"
    data-available="{{ json_encode($availableConnectors->map(fn($c) => [
        'id'       => $c->id,
        'name'     => $c->name,
        'slug'     => $c->slug,
        'category' => $c->category,
        'icon'     => $c->icon,
        'is_oauth' => $c->is_oauth,
    ])) }}"
    data-flash-success="{{ session('success', '') }}"
    data-flash-error="{{ session('error', '') }}"
></div>
@endsection
