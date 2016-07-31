@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Band Manager</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <h4>Create a new band</h4>
                            @include('parts.new_band')
                        </div>
                        <div class="col-md-6">
                            <h4>Band list</h4>
                            <ul id="band-list">
                            @if(count($bands) == 0)
                                > No Bands have been made yet.
                            @else
                                @foreach ($bands as $band)
                                    <li>
                                        <h4>{{ $band->name }}</h4>
                                        <ul>
                                            <li>Contact: {{ $band->primary_name }}</li>
                                            <li>Email: {{ $band->primary_email }}</li>
                                            <li>Telephone: {{ $band->primary_telephone }}</li>
                                        </ul>
                                    </li>
                                @endforeach
                            @endif
                            <ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
