@extends('layouts.app')

@section('content')

    <!-- create cycle -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Create Cycle</div>
            </div>

            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('cycle-create') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="cycleName">Name</label>
                        <input class="form-control" id="cycleName" placeholder="Name" name="name">
                        <label for="lunchtime">Lunchtime</label>
                        <input type="time" class="form-control" id="lunchtime" placeholder="Lunchtime" name="lunchtime">
                    </div>
                    <button class="btn btn-default">Create</button>
                </form>
            </div>
        </div>
    </div>

    <!-- cycles list -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Find Cycles</div>
            </div>

            <!-- find cycles -->
            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('cycle-search') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="searchName">Search</label>
                        <input class="form-control" id="searchName" placeholder="Cycle Name" name="name">
                    </div>
                    <button class="btn btn-default">Search</button>
                </form>
            </div>
        </div>

        <!-- cycles -->
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="pre-scrollable">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Cycle</th>
                            <th>Members</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cycles as $cycle)
                            <tr>
                                <td>{{ $cycle->name }}</td>
                                <td>
                                    <ul>
                                        @foreach($cycle->members as $member)
                                            <li>{{ $member->user->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @if($cycle->is_my_cycle === true)
                                        <form class="form-inline" method="post" action="{{ route('cycle-leave') }}">
                                            <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
                                            <button class="btn btn-default">Leave</button>
                                        </form>
                                    @else
                                        <form class="form-inline" method="post" action="{{ route('cycle-join') }}">
                                            <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
                                            <button class="btn btn-default">Join</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
