@extends('layouts.app')

@section('content')
    <!-- current proposal -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">My propose today</div>
            </div>
            <div class="panel-body">
                <ul>
                    @forelse ($todayProposes as $propose)
                        <li>{{ $propose->restaurant->name }} ({{ $propose->proposed_at->diffForHumans() }})</li>
                    @empty
                        <li>No proposed restaurant today, make one!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- restaurant list -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Find Restaurant</div>
            </div>

            <!-- find restaurant -->
            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('propose-restaurant-search') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="searchName">Search</label>
                        <input class="form-control" id="searchName" placeholder="Restaurant Name" name="name">
                    </div>
                    <button class="btn btn-default">Search</button>
                </form>
            </div>
        </div>

        <!-- restaurants -->
        <div class="panel panel-default">
            @if(count($restaurants) === 0)
                <div class="panel-body">
                    <p>Restaurant not found. Be the first to register!</p>
                </div>
            @else
                <div class="panel-body">
                    <div class="pre-scrollable">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($restaurants as $restaurant)
                                <tr>
                                    <td>{{ $restaurant->name }}</td>
                                    <td>{{ $restaurant->description }}</td>
                                    <td>
                                        <!-- propose button -->
                                        <form class="form-inline" method="post"
                                              action="{{ route('propose-make') }}">
                                            {!! csrf_field() !!}
                                            <input type="hidden" class="form-control"
                                                   id="propose-restaurant-id-{{ $restaurant->id }}"
                                                   name="restaurant_id"
                                                   value="{{ $restaurant->id }}">
                                            @if($currentPropose === null)
                                                <button class="btn btn-default">Propose</button>
                                            @elseif($currentPropose->restaurant_id !== $restaurant->id)
                                                <button class="btn btn-default">Re-propose</button>
                                            @else
                                            <!-- disable re-propose of currently proposed restaurant -->
                                                <button class="btn btn-default disabled">Current Propose</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
