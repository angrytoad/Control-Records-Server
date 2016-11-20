@extends('layouts.app')

@section('content')
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Store Configuration Manager - Edit the store homepage</div>
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
                @if(isset($current_config))
                    <div class="row" id="active-configuration">
                        <div class="col-xs-12 col-md-6 col-lg-8">
                            <h4>Your Current Configuration</h4>
                            @if(isset($current_config))
                                <div class="other-config active col-xs-12">
                                    <h4>
                                        {{$current_config->configuration_name}}
                                    </h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Article:</strong></p>
                                            <p>
                                                <a href="/news/{{$current_config->store_article->id}}">
                                                    {{$current_config->store_article->title}}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Album:</strong></p>
                                            <p>
                                                <a href="/music/album/{{$current_config->store_album->id}}">
                                                    <img class="config-avatar" src="{{$current_config->store_album->album_image_url}}" />
                                                    {{$current_config->store_album->album_name}}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Artist:</strong></p>
                                            <p>
                                                <a href="/bands/{{$current_config->store_artist->id}}">
                                                    @if(isset($current_config->store_artist->band_additional))
                                                        <img class="config-avatar" src="{{$current_config->store_artist->band_additional->band_avatar_url}}" />
                                                    @endif
                                                    {{$current_config->store_artist->name}}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                        <p><i>You do not have any configurations set up yet.</i></p>
                @endif
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-8">
                        <h4>Your Other Configurations</h4>
                        @if(count($other_configurations) > 0)
                            @foreach($other_configurations as $other_config)
                                <div class="other-config col-xs-12">
                                    <h4>
                                        {{$other_config->configuration_name}}
                                        <a href="/store/config/{{$other_config->id}}/activate"><button class="btn btn-info">Set Active Config</button></a>
                                        <a href="/store/config/{{$other_config->id}}/delete"><button class="btn btn-warning pull-right">Delete Config</button></a>
                                    </h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Article:</strong></p>
                                            <p>
                                                <a href="/news/{{$other_config->store_article->id}}">
                                                    {{$other_config->store_article->title}}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Album:</strong></p>
                                            <p>
                                                <a href="/music/album/{{$other_config->store_album->id}}">
                                                    <img class="config-avatar" src="{{$other_config->store_album->album_image_url}}" />
                                                    {{$other_config->store_album->album_name}}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <p><strong>Featured Artist:</strong></p>
                                            <p>
                                                <a href="/bands/{{$other_config->store_artist->id}}">
                                                    @if(isset($other_config->store_artist->band_additional))
                                                        <img class="config-avatar" src="{{$other_config->store_artist->band_additional->band_avatar_url}}" />
                                                    @endif
                                                    {{$other_config->store_artist->name}}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><i>You do not have any additional configurations set up yet.</i></p>
                        @endif

                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <h4>Create a new configuration</h4>
                        <form method="POST" action="/store/config/create" id="#newStoreConfiguration">
                            <div class="form-group">
                                <label>Configuration Name:</label>
                                <input type="text" name="config-name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Featured Article (Last 6 months):</label>
                                <select class="form-control" name="featured-article">
                                    @foreach($articles as $article)
                                        <option value="{{$article->id}}">{{$article->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Featured Album:</label>
                                <select class="form-control" name="featured-album">
                                    @foreach($albums as $album)
                                        <option value="{{$album->id}}">{{$album->album_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Featured Artist:</label>
                                <select class="form-control" name="featured-artist">
                                    @foreach($artists as $artist)
                                        <option value="{{$artist->id}}">{{$artist->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-success">Create New Configuration</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
