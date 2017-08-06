@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Restaurants</div>
            <div class="panel-body">
                <ul>
                    @forelse($restaurants as $restaurant)
                        <li>{{ $restaurant->name }}</li>
                    @empty
                        <li>No restaurant in database. Be the first to register one!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
