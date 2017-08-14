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
    @forelse($proposesByCycle as $cycleProposes)
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading"><a name="{{ kebab_case($cycleProposes['cycle']->name) }}"></a>
                    <span class="panel-title pull-left">{{ $cycleProposes['cycle']->name }}</span>
                    <div class="pull-right">
                        <p>
                            Propose Until: {{ $cycleProposes['cycle']->propose_until }}<br>
                            Lunchtime: {{ $cycleProposes['cycle']->lunchtime }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <ul>
                        @foreach($cycleProposes['proposes'] as $propose)
                            <li>{{ $propose['user']->name }} {{ $propose['restaurant']->name }} {{ $propose->proposed_at }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection
