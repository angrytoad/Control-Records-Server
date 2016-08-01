@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Users Manager</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <h4>Create a new user</h4>
                            @include('parts.new_user')
                        </div>
                        <div class="col-md-6">
                            <h4>User list</h4>
                            <ul id="gig-list">
                                @if(count($users) == 0)
                                    > No new gigs at the moment
                                @else
                                    @foreach ($users as $user)
                                        <li>
                                            <h4>{{ $user->name }}</h4>
                                            <strong>{{ $user->email }}</strong>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
