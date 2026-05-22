@extends('layouts.admin')

@section('title', 'Training Catalog - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="trainings"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'trainings'    => $trainings->map(fn($t) => [
            'id'           => $t->id,
            'slug'         => $t->slug,
            'title'        => $t->title,
            'description'  => $t->description,
            'category'     => $t->category,
            'format'       => $t->format,
            'duration'     => $t->duration,
            'level'        => $t->level,
            'price'        => $t->price,
            'badge'        => $t->badge,
            'is_active'    => $t->is_active,
            'is_featured'  => $t->is_featured,
            'sort_order'   => $t->sort_order,
        ])->values()->all(),
        'flashSuccess' => session('success'),
        'flashError'   => session('error'),
    ]) }}">
</div>
@endsection
