@extends('layouts.app')

@section('content')
    <!-- search -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline" method="post" action="{{ route('user-search') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="searchName">Search</label>
                        <input class="form-control" id="searchName" placeholder="User Name" name="name">
                    </div>
                    <button class="btn btn-default">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Users</div>
            </div>
            @foreach($users as $user)
            <div class="panel-body">
                {{ $user['user']->name }}

                <ul>
                @forelse($user['proposes'] as $propose)
                        <li>{{ $propose->restaurant->name }} ({{ $propose->proposed_at->diffForHumans() }})</li>
                @empty
                    <li>Not yet proposed.</li>
                @endforelse
                </ul>
            </div>
            @endforeach
        </div>
    </div>
@endsection
