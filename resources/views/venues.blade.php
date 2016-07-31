@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Venue Manager</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <h4>Create a new venue</h4>
                            @include('parts.new_venue')
                        </div>
                        <div class="col-md-6">
                            <h4>Venue list</h4>
                            @include('parts.venue_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
