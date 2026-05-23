@extends('layouts.admin')

@section('title', $user->name . ' — ' . $userConnector->connector->name . ' Config - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-connector-config"
    data-props="{{ json_encode([
        'currentUser'    => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'      => csrf_token(),
        'subject'        => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        'userConnector'  => [
            'id'         => $userConnector->id,
            'status'     => $userConnector->status,
            'config'     => $userConnector->config ?? [],
            'connector'  => [
                'id'            => $userConnector->connector->id,
                'name'          => $userConnector->connector->name,
                'icon'          => $userConnector->connector->icon,
                'config_schema' => $userConnector->connector->config_schema ?? [],
            ],
        ],
        'errors'         => $errors->all(),
        'flashSuccess'   => session('success', ''),
        'flashError'     => session('error', ''),
    ]) }}">
</div>
@endsection
