@extends('layouts.app')


@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{$song->song_name}}</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <div class="row">
                        <a href="/music"><button class="btn btn-info">Music Manager</button></a>
                        <a href="/music/songs"><button class="btn btn-info">Songs List</button></a>
                        <button class="btn btn-danger pull-right" onClick="confirmDeletion()">Delete Song</button>
                        @if($song->public)
                            <button onClick="makePrivate('/music/song/{{$song->id}}/private')" class="btn btn-warning">Hide song from store</button>
                        @else
                            <button onClick="makePublic('/music/song/{{$song->id}}/public')" class="btn btn-warning">Publish song to store</button>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 no-left">
                    <div class="song-privacy-wrapper">
                        @if($song->public)
                            <div class="alert alert-success">
                                This Song is currently <strong>AVAILABLE</strong> on the store.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                This Song is currently <strong>NOT AVAILABLE</strong> on the store.
                            </div>
                        @endif
                    </div>
                    <h2 class="song-title">
                        @if($song->public)
                            <span title="This song is public" class="public"></span>
                        @else
                            <span title="This song is private" class="private"></span>
                        @endif
                        {{$song->song_name}}
                    </h2>
                    <p>By <a href="/bands/{{$band->id}}">{{ $band->name }}</a></p>
                    <div class="song-listener">
                        <div class="song-audio-wrapper">
                            <h4>Sample File</h4>
                            <audio src="{{$song->sample_url}}" controls>
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                        <div class="song-audio-wrapper">
                            <h4>Paid File</h4>
                            <audio src="{{$paid_link}}" controls>
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                    <div class="song-edit">
                        @if (session('upload_error'))
                            <div class="alert alert-danger">
                                {{ session('upload_error') }}
                            </div>
                        @endif
                        <form id="song-edit-form" method="post" action="/music/song/{{$song->id}}/edit" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="song-name">Song Name:</label>
                                <input type="text" name="song-name" class="form-control" value="{{$song->song_name}}">
                            </div>
                            <div class="form-group">
                                <label for="song-artist">Artist:</label>
                                <select name="song-artist" class="form-control">
                                    @foreach($bands as $bandObject)
                                        @if($bandObject->id === $band->id)
                                            <option selected value={{$bandObject->id}}>{{$bandObject->name}}</option>
                                        @else
                                            <option value={{$bandObject->id}}>{{$bandObject->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="song-album">Album:</label>
                                <select name="song-album[]" class="form-control" multiple>
                                    @foreach($albums as $albumObject)
                                        {{-- */$found=false;/* --}}
                                        @foreach($song->albums as $album)
                                            @if($album->id === $albumObject->id)
                                                {{-- */$found=true;/* --}}
                                            @endif
                                        @endforeach

                                        @if($found)
                                            <option selected value={{$albumObject->id}}>{{$albumObject->album_name}}</option>
                                        @else
                                            <option value={{$albumObject->id}}>{{$albumObject->album_name}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="song-sample">Sample Track:</label>
                                <input type="file" name="song-sample" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="song-paid">Purchase Track:</label>
                                <input type="file" name="song-paid" class="form-control" />
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-warning">Make song edits</button>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 songs-in-album-wrapper">
                    <div class="row">
                        <ul class="songs-in-album">
                            <h4 class="text-left">Appears In...</h4>
                            @foreach($song->albums as $album)
                                <li class="song-in-album">
                                    <a href="/music/album/{{$album->id}}">
                                        <img title="{{$album->album_name}}" src="{{$album->album_image_url}}" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if(count($bandSongs) > 1)
                            <div class="other-works">
                                <h4>Other songs by <strong><a href="/bands/{{$band->id}}">{{$band->name}}</a></strong></h4>
                                <ul>
                                    @foreach($bandSongs as $bandSong)
                                        @if($bandSong->id !== $song->id)
                                            <li>
                                                <div class="col-xs-12 col-md-10">
                                                    <div class="row">
                                                        <audio src="{{$bandSong->sample_url}}" controls>
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                        <a href="/music/song/{{$bandSong->id}}"><h4>{{$bandSong->song_name}}</h4></a>
                                                    </div>
                                                </div>
                                                @if(count($bandSong->albums) > 0)
                                                    <ul class="col-xs-12 col-md-2 text-right">
                                                        @foreach($bandSong->albums as $bandSongAlbum)
                                                            <a href="/music/album/{{$bandSongAlbum->id}}">
                                                                <li>
                                                                    <img class="song-album-teaser" title="{{$bandSongAlbum->album_name}}" src="{{$bandSongAlbum->album_image_url}}" />
                                                                </li>
                                                            </a>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function makePrivate(link){
            swal({
                title: 'Confirm private setting',
                text: "If you make this song PRIVATE, it will not longer be visible from the store.",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Make Private'
            }).then(function() {
                window.location.href = link;
            })
        }

        function makePublic(link){
            swal({
                title: 'Confirm public setting',
                text: "If you make this song PUBLIC it will be visible on the store.",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Make Public'
            }).then(function() {
                window.location.href = link;
            })
        }

        function confirmDeletion(){
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function() {
                window.location.href = '/music/song/{{$song->id}}/delete';
            })
        }
    </script>
@endsection
