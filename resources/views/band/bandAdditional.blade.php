@extends('layouts.app')



@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$band->name}} (Additional)</div>
                    <div class="panel-body">
                        <div class="col-xs-12 col-lg-6">
                            <a href="/bands/{{$band->id}}"><button class="btn btn-info">Basic Info</button></a>
                            <h2>Add Additional Content</h2>

                            <div class="alert alert-info">
                                <p class="alert-info">
                                    NOTE: Please be aware that all images are given a background of <span style="background:#4D4D4D;" class="color-brick"></span>
                                    so if you are uploading a .png, try not to use dark colours as it may show up weird.
                                </p>
                            </div>

                            @if (session('upload_error'))
                                <div class="alert alert-danger">
                                    {{ session('upload_error') }}
                                </div>
                            @endif
                            <form id="banner-upload-form" method="post" action="/bands/{{$band->id}}/additional/banner" enctype="multipart/form-data">
                                <div>
                                    <label>Banner Image</label>
                                    <input type="file" name="banner" class="col-xs-12" />
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-success">Upload Banner Image</button>
                            </form>
                            <form id="avatar-upload-form" method="post" action="/bands/{{$band->id}}/additional/avatar" enctype="multipart/form-data">
                                <div>
                                    <label>Avatar Image</label>
                                    <input type="file" name="avatar" class="col-xs-12" />
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-success">Upload Avatar</button>
                            </form>
                        </div>
                        <div class="col-xs-12 col-lg-6">
                            <h2>Previews</h2>
                            <div class="alert alert-info">
                                <p class="alert-info">
                                    NOTE: Large images may take a bit to upload and so won't be visible right away,
                                    refresh the page in 30 seconds if its still not working
                                </p>
                            </div>
                            @if(isset($bandAdditional))
                                <div class="additional-preview">
                                    <p><strong>Banner</strong></p>
                                    @if(strlen($bandAdditional->band_banner_url) > 0)
                                        <img src={{$bandAdditional->band_banner_url}} />
                                    @else
                                        <p><i>You have not uploaded a banner yet.</i></p>
                                    @endif
                                </div>

                                <div class="additional-preview">
                                    <p><strong>Avatar</strong></p>
                                    @if(strlen($bandAdditional->band_avatar_url) > 0)
                                        <img src={{$bandAdditional->band_avatar_url}} />
                                    @else
                                        <p><i>You have not uploaded an avatar yet.</i></p>
                                    @endif
                                </div>
                            @else
                                <h4><i>Nothing uploaded yet</i></h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

