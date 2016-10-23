@extends('layouts.app')

@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Music Manager</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <h4>Soon you will see vital statistics about our music library here.</h4>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6">
                    <h4>Actions</h4>
                    <a href="/music/albums/create"><button class="btn btn-info">Create an album</button></a>
                    <a href="/music/songs/create"><button class="btn btn-info">Upload a song</button></a>
                    <br />
                    <a href="/music/albums"><button class="btn btn-info">View all Albums</button></a>
                    <a href="/music/songs"><button class="btn btn-info">View all Songs</button></a>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6">
                    <h4>Latest Uploads</h4>
                    <div class="latest-uploads-wrapper">
                        <h4>Last 10 Song Uploads</h4>
                        <ul>
                            @foreach($lastSongUploads as $song)
                                <li>
                                    <div class="col-xs-12 col-md-10">
                                        <div class="row">
                                            <audio src="{{$song->sample_url}}" controls>
                                                Your browser does not support the audio element.
                                            </audio>
                                            <a href="/music/song/{{$song->id}}"><h4>{{$song->song_name}}</h4></a>
                                        </div>
                                    </div>
                                    @if(count($song->albums) > 0)
                                        <ul class="col-xs-12 col-md-2 text-right">
                                            @foreach($song->albums as $album)
                                                <a href="/music/album/{{$album->id}}">
                                                    <li>
                                                        <img class="song-album-teaser" title="{{$album->album_name}}" src="{{$album->album_image_url}}" />
                                                    </li>
                                                </a>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="latest-uploads-wrapper">
                        <h4>Last 10 Album Uploads</h4>
                        <ul>
                            @foreach($lastAlbumUploads as $album)
                                <a class="album-upload" href="/music/album/{{$album->id}}">
                                    <li>
                                        <img class="song-album-teaser" title="{{$album->album_name}}" src="{{$album->album_image_url}}" />
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
