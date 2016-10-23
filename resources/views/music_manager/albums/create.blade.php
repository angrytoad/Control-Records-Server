@extends('layouts.app')

@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Create a new album</div>
            <div class="panel-body">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <h4>Create an album</h4>
                        @if (session('upload_error'))
                            <div class="alert alert-danger">
                                {{ session('upload_error') }}
                            </div>
                        @endif
                        <form id="album-creation-form" method="post" action="/music/albums/create" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="usr">Album Name:</label>
                                <input type="text" name="album-name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="usr">Album Image (1:1 ratio):</label>
                                <input type="file" name="album-image" class="form-control" />
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-success">Create Album</button>
                        </form>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <h4>Recently Created Albums</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
