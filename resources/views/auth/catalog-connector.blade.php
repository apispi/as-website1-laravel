@extends('layouts.dashboard-catalog-connector')

@section('title', $connector->name . ' - Connector - ApiSpi')

@section('content')
<div
    id="dashboard-catalog-connector-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-connector="{{ json_encode([
        'id'          => $connector->id,
        'name'        => $connector->name,
        'slug'        => $connector->slug,
        'category'    => $connector->category,
        'icon'        => $connector->icon,
        'description' => $connector->description,
        'vendor'      => $connector->vendor,
        'version'     => $connector->version,
        'website_url' => $connector->website_url,
        'is_oauth'    => $connector->is_oauth,
        'sla_tier'    => $connector->sla_tier,
        'tags'        => $connector->tags ?? [],
    ]) }}"
    data-user-connector="{{ json_encode($userConnector ? [
        'id'           => $userConnector->id,
        'status'       => $userConnector->status,
        'connected_at' => $userConnector->connected_at?->toDateString(),
    ] : null) }}"
></div>
@endsection
