@extends('layouts.app')

@section('content')

    <!-- users list -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Users</div>
            </div>

            <!-- search -->
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

            <!-- users -->
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Proposed</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['user']->name }}</td>
                        <td>
                            <ul>
                                @forelse($user['proposes'] as $propose)
                                    <li>{{ $propose->restaurant->name }}
                                        ({{ $propose->proposed_at->diffForHumans() }})
                                    </li>
                                @empty
                                    <li>Not yet proposed.</li>
                                @endforelse
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
