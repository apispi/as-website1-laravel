@extends('layouts.admin')

@section('title', $user->name . ' — Edit ' . $userConnector->connector->name . ' - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-connector-edit"
    data-props="{{ json_encode([
        'currentUser'   => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin],
        'csrfToken'     => csrf_token(),
        'subject'       => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        'userConnector' => [
            'id'               => $userConnector->id,
            'status'           => $userConnector->status,
            'connected_at'     => $userConnector->connected_at?->format('Y-m-d\TH:i'),
            'disconnected_at'  => $userConnector->disconnected_at?->format('Y-m-d\TH:i'),
            'notes'            => $userConnector->notes,
            'connector'        => [
                'id'               => $userConnector->connector->id,
                'name'             => $userConnector->connector->name,
                'slug'             => $userConnector->connector->slug,
                'category'         => $userConnector->connector->category,
                'icon'             => $userConnector->connector->icon,
                'is_oauth'         => $userConnector->connector->is_oauth,
                'has_config_schema'=> !empty($userConnector->connector->config_schema),
            ],
        ],
        'errors'        => $errors->all(),
        'flashSuccess'  => session('success', ''),
        'flashError'    => session('error', ''),
    ]) }}">
</div>
@endsection
