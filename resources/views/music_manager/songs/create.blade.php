@extends('layouts.app')

@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Upload a song</div>
            <div class="panel-body">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <h4>Upload a song</h4>
                        @if (session('upload_error'))
                            <div class="alert alert-danger">
                                {{ session('upload_error') }}
                            </div>
                        @endif
                        <form id="song-creation-form" method="post" action="/music/songs/create" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="song-name">Song Name:</label>
                                <input type="text" name="song-name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="song-artist">Artist:</label>
                                <select name="song-artist" class="form-control">
                                @foreach($bands as $band)
                                    <option value={{$band->id}}>{{$band->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="song-album">Album:</label>
                                <select multiple name="song-album[]" class="form-control">
                                    @foreach($albums as $album)
                                        <option value={{$album->id}}>{{$album->album_name}}</option>
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
                            <button class="btn btn-success">Add Song to Catalogue</button>
                        </form>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <h4>Recently Uploaded Songs</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
