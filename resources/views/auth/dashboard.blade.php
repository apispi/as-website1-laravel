@extends('layouts.dashboard')

@section('title', 'Dashboard - ApiSpi')

@section('content')
<div
    id="dashboard-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'created_at' => Auth::user()->created_at, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
></div>
@endsection
