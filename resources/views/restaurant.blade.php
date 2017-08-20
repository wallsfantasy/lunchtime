@extends('layouts.app')

@section('content')

    <!-- register -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Register a Restaurant</div>
            </div>
            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('restaurant-register') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="registerName">Name</label>
                        <input class="form-control" id="registerName" placeholder="Restaurant Name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="registerDescription">Description</label>
                        <input class="form-control" id="registerDescription" placeholder="Restaurant Description"
                               name="description">
                    </div>
                    <button class="btn btn-default">Register</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Restaurants</div>
            </div>

            <!-- search -->
            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('restaurant-search') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="searchName">Search</label>
                        <input class="form-control" id="searchName" placeholder="Restaurant Name" name="name">
                    </div>
                    <button class="btn btn-default">Search</button>
                </form>
            </div>

            <!-- restaurant list -->
            @if(count($restaurants) === 0)
                <div class="panel-body">
                    <p>No restaurant found. Be the first to register!</p>
                </div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($restaurants as $restaurant)
                        <tr>
                            <td>{{ $restaurant->name }}</td>
                            <td>{{ $restaurant->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
