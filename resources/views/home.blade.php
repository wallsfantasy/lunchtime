@extends('layouts.app')

@section('content')

    <!-- My Cycles -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">My Cycles</div>
            <div class="panel-body">
                <ul>
                    @forelse($cycles as $cycle)
                        <li><a href="#{{ kebab_case($cycle->name) }}">{{ $cycle->name }}</a></li>
                    @empty
                        <li>You haven't join any cycle. Click here to add some!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Cycle lists -->
    @forelse($userProposesByCycle as $cycleName => $userPropose)
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading"><a name="{{ kebab_case($cycleName) }}"></a>
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
