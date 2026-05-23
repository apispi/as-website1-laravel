@extends('layouts.catalog')

@section('title', 'Catalog - ApiSpi')

@section('content')
<div
    id="catalog-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-agents="{{ json_encode($agents->map(fn($a) => [
        'id'          => $a->id,
        'name'        => $a->name,
        'slug'        => $a->slug,
        'description' => $a->description,
        'badge'       => $a->badge,
        'price'       => $a->price,
        'category'    => $a->category,
        'rating'      => $a->rating,
        'users_count' => $a->users_count,
    ])) }}"
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
