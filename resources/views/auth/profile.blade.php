@extends('layouts.profile')

@section('title', 'My Profile - ApiSpi')

@section('content')
<div
    id="profile-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-flash-success="{{ session('success') }}"
    data-flash-error="{{ $errors->first() }}"
    data-flash-field="{{ $errors->keys()[0] ?? '' }}"
></div>
@endsection
