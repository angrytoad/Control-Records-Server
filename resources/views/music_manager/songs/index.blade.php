@extends('layouts.app')


@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Song Manager</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <a href="/music/songs/create"><button class="btn btn-info">Upload a new song</button></a>
                                <a href="/music"><button class="btn btn-info">Music Manager</button></a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <table class="stats-table row">
                                <tr>
                                    <td>Total:</td>
                                    <td>{{$songCount}}</td>
                                </tr>
                                <tr>
                                    <td>Latest Upload:</td>
                                    @if(isset($songLatest))
                                        <td><a href="/music/song/{{$songLatest->id}}">{{$songLatest->song_name}}</a></td>
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
                </div>
                <div class="col-xs-12">
                    <h2 class="text-center">Songs</h2>
                    <div class="row songs-wrapper">
                        <ul>
                            @foreach($songs as $song)
                                <li>
                                    <div class="pull-left">
                                        <audio src="{{$song->sample_url}}" controls>
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                    <div class="pull-left">
                                        <a class="pull-left" href="/music/song/{{$song->id}}">
                                            <h4>{{$song->song_name}}</h4>
                                        </a>
                                        <a class="pull-left" href="/bands/{{$song->band_id}}">
                                            <h4 class="no-border">({{$song->band->name}})</h4>
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        @if(count($song->albums) > 0)
                                            <ul class="pull-right">
                                                @foreach($song->albums as $album)
                                                    <a href="/music/album/{{$album->id}}">
                                                        <li>
                                                            <img class="song-album-teaser" title="{{$album->album_name}}" src="{{$album->album_image_url}}" />
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>
                                            <h4 class="pull-right no-border">Albums: </h4>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
