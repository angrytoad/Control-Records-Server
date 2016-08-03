@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Gigs Manager</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <h4>Create a new gig</h4>
                            @include('parts.new_gig')
                        </div>
                        <div class="col-md-6">
                            <h4>Gig list</h4>
                            <ul id="gig-list">
                            @if(count($gigs) == 0)
                            > No new gigs at the moment
                            @else
                                @foreach ($gigs as $gig)
                                    <li>
                                        <div>
                                        {{ \Carbon\Carbon::parse($gig->date)->toDayDateTimeString() }} @ <strong>{{ $gig->venue->venue_name }}</strong>
                                        </div>
                                        <div>
                                        <h4>{{ $gig->band->name }}</h4>
                                        </div>
                                        <div class="gig-options">
                                            <a href="/gigs/{{$gig->id}}">
                                                <button class="btn btn-info">
                                                    Edit
                                                </button>
                                            </a>
                                        </div>
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
