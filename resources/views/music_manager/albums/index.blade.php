@extends('layouts.app')


@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Album Manager</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <a href="/music/albums/create"><button class="btn btn-info">Create an album</button></a>
                        <a href="/music"><button class="btn btn-info">Music Manager</button></a>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <table class="stats-table">
                            <tr>
                                <td>Total:</td>
                                <td>{{$albumCount}}</td>
                            </tr>
                            <tr>
                                <td>Latest Album:</td>
                                @if(isset($albums[0]))
                                    <td><a href="/music/album/{{$albums[0]['id']}}">{{$albums[0]['album_name']}}</a></td>
                                @else
                                    <td><i>N/A</i></td>
                                @endif

                            </tr>
                            <tr>
                                <td>Added in the last week:</td>
                                <td>{{$lastWeek}}</td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="col-xs-12">
                    <h2 class="text-center">Albums</h2>
                    <div class="row albums-wrapper">
                        @foreach($albums as $album)
                            <a href="/music/album/{{$album->id}}">
                                <div class="album-wrapper">
                                    <img src={{$album->album_image_url}} />
                                    <p>{{$album->album_name}}</p>
                                    @if($album->public)
                                        <i class="fa fa-stop public" aria-hidden="true" title="this album is currently showing on the store"></i>
                                    @else
                                        <i class="fa fa-stop private" aria-hidden="true" title="this album is currently hidden from the store"></i>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
