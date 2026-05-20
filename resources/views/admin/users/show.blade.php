@extends('layouts.admin')

@section('title', $user->name . ' - Users - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-detail"
    data-props="{{ json_encode([
        'currentUser' => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'   => csrf_token(),
        'subject'     => [
            'id'                 => $user->id,
            'name'               => $user->name,
            'email'              => $user->email,
            'is_admin'           => $user->is_admin,
            'created_at'         => $user->created_at,
            'email_verified_at'  => $user->email_verified_at,
        ],
        'flashSuccess' => session('success', ''),
        'flashError'   => session('error', ''),
    ]) }}">
</div>
@endsection
