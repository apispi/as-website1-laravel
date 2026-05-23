@extends('layouts.admin')

@section('title', isset($connector) ? 'Edit Connector - Admin - ApiSpi' : 'New Connector - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="connector-form"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'connector' => $connector ?? null,
        'errors'    => $errors->all(),
    ]) }}">
</div>
@endsection
