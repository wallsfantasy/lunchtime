@extends('layouts.app')

@section('content')
    <!-- search -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline" action="{{ route('propose-restaurant-search') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="searchName">Search</label>
                        <input class="form-control" id="searchName" placeholder="Restaurant Name" name="name">
                    </div>
                    <button class="btn btn-default">Search</button>
                </form>
            </div>
        </div>
    </div>

    <!-- show today proposal -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">My Proposal Today</div>
            <div class="panel-body">
                @if($todayProposal !== null)
                    <p>{{ $todayRestaurant->name }}</p>
                @else
                    <p>No proposed restaurant today, propose one!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- restaurant list -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Restaurants</div>

            @if(count($restaurants) === 0)
                <div class="panel-body">
                    <p>Restaurant not found. Be the first to register!</p>
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
                            <td>
                                @if($todayProposal === null)
                                    <form class="form-inline" method="post" action="{{ route('propose-make') }}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control"
                                               id="propose-restaurant-id-{{ $restaurant->id }}" name="restaurant_id"
                                               value="{{ $restaurant->id }}">
                                        <button class="btn btn-default">Propose</button>
                                    </form>
                                @elseif($todayProposal !== null && $todayRestaurant->id !== $restaurant->id)
                                    <form class="form-inline" method="post" action="{{ route('propose-re-propose') }}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control"
                                               id="propose-restaurant-id-{{ $restaurant->id }}" name="restaurant_id"
                                               value="{{ $restaurant->id }}">
                                        <button class="btn btn-default">Re-propose</button>
                                    </form>
                                @else
                                    <button class="btn btn-default disabled">Re-propose</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
