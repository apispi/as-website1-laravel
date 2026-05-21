@extends('layouts.catalog')

@section('title', 'Agent Catalog - ApiSpi')

@section('content')
<div
    id="catalog-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-agents="{{ json_encode($agents->map(fn($a) => [
        'id'          => $a->id,
        'name'        => $a->name,
        'slug'        => $a->slug,
        'description' => $a->description,
        'badge'       => $a->badge,
        'price'       => $a->price,
        'category'    => $a->category,
        'rating'      => $a->rating,
        'users_count' => $a->users_count,
    ])) }}"
></div>
@endsection
