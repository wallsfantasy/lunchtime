@extends('layouts.app')

@section('content')

    <!-- search user -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Find Users</div>
            </div>
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

        <!-- users -->
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="pre-scrollable">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
