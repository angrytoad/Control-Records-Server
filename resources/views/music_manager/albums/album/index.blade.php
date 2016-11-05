@extends('layouts.app')


@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{$album->album_name}}</div>
            <div class="panel-body">
                <div id="vital-statistics" class="col-xs-12">
                    <a href="/music/albums"><button class="btn btn-info">Albums List</button></a>
                    <button onClick="unlinkAllSongs('/music/album/{{$album->id}}/unlink')" class="btn btn-danger">Unlink all songs</button>
                    <button onClick="deleteAlbum('/music/album/{{$album->id}}/delete')" class="btn btn-danger">Delete this Album</button>
                </div>
                <div class="album-privacy-wrapper">
                    @if($album->public)
                        <div class="public"><h3>This Album is Currently On The Store</h3></div>
                    @else
                        <div class="private"><h3>This Album is Currently Hidden On The Store</h3></div>
                    @endif
                </div>
                <div class="col-xs-12 col-md-6">
                    <h2 class="album-title"><img src="{{$album->album_image_url}}"/> {{$album->album_name}}</h2>
                    <hr>
                    <h4>Store Options</h4>
                    @if($album->public)
                        <button onClick="makePrivate('/music/album/{{$album->id}}/private')" class="btn btn-warning">Hide album from store</button>
                    @else
                        <button onClick="makePublic('/music/album/{{$album->id}}/public')" class="btn btn-info">Publish album to store</button>
                    @endif
                </div>
                <div class="col-xs-12 col-md-6">
                    <h2>Song List</h2>
                    <ul class="song-lister">
                        @foreach($album->songs as $song)
                            <li>
                                {{$song->song_name}} - <i>{{$song->band->name}}</i>
                                <div>
                                    Actions:
                                    <i class="fa fa-chain-broken private-text" title="Remove song from this album" aria-hidden="true" onClick="confirmDeletion('{{'/music/album/'.$album->id.'/'.$song->id.'/delete'}}')"></i>
                                    <a href="/music/song/{{$song->id}}" title="Link to song page"><i class="fa fa-music" aria-hidden="true"></i></a>
                                    <a href="/bands/{{$song->band->id}}" title="Link to {{$song->band->name}}"><i class="fa fa-users" aria-hidden="true"></i></a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        function makePrivate(link){
            swal({
                title: 'Confirm private setting',
                text: "If you make this album PRIVATE, it will not longer be visible from the store.",
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
                text: "If you make this album PUBLIC it will be visible on the store.",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Make Public'
            }).then(function() {
                window.location.href = link;
            })
        }

        function deleteAlbum(link){
            swal({
                title: 'Confirm Album Deletion',
                text: "If you delete this album, all songs will be unlinked and the album will be removed from the store.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete album'
            }).then(function() {
                window.location.href = link;
            })
        }

        function unlinkAllSongs(link){
            swal({
                title: 'Confirm Unlink of all songs',
                text: "If you unlink all songs, you will have an empty album AND the album will be hidden from the store.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Unlink all songs'
            }).then(function() {
                window.location.href = link;
            })
        }


        function confirmDeletion(link){
            swal({
                title: 'Confirm Unlink',
                text: "You will have to add this song back in later if you change your mind",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Unlink song from album'
            }).then(function() {
                window.location.href = link;
            })
        }

    </script>
@endsection
