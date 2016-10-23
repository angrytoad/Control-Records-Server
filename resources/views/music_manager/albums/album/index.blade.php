@extends('layouts.app')


@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{$album->album_name}}</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <a href="/music/albums"><button class="btn btn-info">Albums List</button></a>
                </div>
                <div class="col-xs-12">
                    More to come soon!
                </div>
            </div>
        </div>
    </div>
@endsection
