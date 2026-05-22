@extends('layouts.admin')

@section('title', 'Activity Log - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="activity-log"
    data-props="{{ json_encode([
        'user'       => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'  => csrf_token(),
        'logs'       => $logs,
        'pagination' => $pagination,
        'filters'    => $filters,
    ]) }}">
</div>
@endsection
