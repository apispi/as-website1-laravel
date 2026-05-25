@extends('layouts.connectors-user')

@section('title', 'Configure ' . $userConnector->connector->name . ' - ApiSpi')

@section('content')
<div
    id="user-connector-edit-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-user-connector="{{ json_encode([
        'id'          => $userConnector->id,
        'status'      => $userConnector->status,
        'config'      => $userConnector->config ?? [],
        'connected_at'=> $userConnector->connected_at?->toDateString(),
        'connector'   => [
            'id'            => $userConnector->connector->id,
            'name'          => $userConnector->connector->name,
            'slug'          => $userConnector->connector->slug,
            'category'      => $userConnector->connector->category,
            'icon'          => $userConnector->connector->icon,
            'is_oauth'      => $userConnector->connector->is_oauth,
            'website_url'   => $userConnector->connector->website_url,
            'config_schema' => $userConnector->connector->config_schema ?? [],
        ],
    ]) }}"
    data-flash-success="{{ session('success', '') }}"
    data-flash-error="{{ session('error', '') }}"
    data-errors="{{ json_encode($errors->all()) }}"
></div>
@endsection
