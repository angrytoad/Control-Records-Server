@extends('layouts.app')



@section('content')

    <div class="container" id="band-additional">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$band->name}} (Additional)</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-xs-12">
                            <a href="/bands/{{$band->id}}"><button class="btn btn-info">Basic Info</button></a>
                        </div>
                        <div class="col-xs-12 col-lg-8">
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
                            <hr>
                            <form id="band-about-form" method="post" action="/bands/{{$band->id}}/additional/about">


                                <div id="about-editor">
                                    <div class="col-xs-12">
                                        <label>About The Band</label>
                                    </div>
                                    <div class="col-xs-12">
                                        <button class="btn btn-success">Update About</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <h4>Editor</h4>
                                        <textarea oninput="this.editor.update()" class="col-sm-12" id="additional-about-body" name="about" placeholder="About the band">{{$band->band_additional->about}}</textarea>
                                    </div>
                                    <div class="col-xs-6">
                                        <h4>Preview</h4>
                                        <div class="col-sm-12" id="preview"> </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>



                        </div>
                        <div class="col-xs-12 col-lg-4">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown.js/0.5.0/markdown.min.js"></script>
    <script>
        function Editor(input, preview) {
            this.update = function () {
                preview.innerHTML = markdown.toHTML(input.value);
            };
            input.editor = this;
            this.update();
        }
        var $id = function (id) { return document.getElementById(id); };
        new Editor($id("additional-about-body"), $id("preview"));

    </script>
@endsection

