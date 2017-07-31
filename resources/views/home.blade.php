@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Your Cycles</div>
            <div class="panel-body">
                <ul>
                    @forelse($cycles as $cycle)
                        <li>{{ $cycle->name }}</li>
                    @empty
                        <li>You haven't join any cycle. Click here to add some!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @forelse($userProposesByCycle as $cycleName => $userPropose)
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $cycleName }}
                </div>
                <div class="panel-body">
                    <ul>
                        @foreach($userPropose as $userName => $restaurantName)
                            @if($restaurantName !== null)
                                <li>{{ $userName }} {{ $restaurantName }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection
