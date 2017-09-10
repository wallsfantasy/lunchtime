@extends('layouts.app')

@section('content')

    <!-- My Cycles -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">My Cycles</div>
            </div>
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
                    <span class="panel-title">{{ $cycleProposes['cycle']->name }}&nbsp;
                        @if ($cycleProposes['cycle']->propose_until == $cycleProposes['cycle']->lunchtime)
                            ({{ $cycleProposes['cycle']->lunchtime }})
                        @else
                            ({{ $cycleProposes['cycle']->lunchtime }} | {{ $cycleProposes['cycle']->propose_until }})
                        @endif
                    </span>
                </div>
                <div class="panel-body">
                    <ul>
                        @foreach($cycleProposes['proposes'] as $propose)
                            <li>({{ $propose->proposed_at->diffForHumans() }}
                                ) {{ $propose['user']->name }} {{ $propose['restaurant']->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection
