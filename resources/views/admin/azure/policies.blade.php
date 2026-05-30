@extends('layouts.admin')

@section('title', 'Azure Policies - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="azure-policies"
    data-props="{{ json_encode([
        'user'     => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'policies' => $policies,
        'roles'    => $roles,
        'roleMap'  => $roleMap,
        'tenant'   => $tenant,
        'ready'    => $ready,
    ]) }}">
</div>
@endsection
