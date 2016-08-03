@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">News Manager</div>
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <a href="/news/create"><button class="btn btn-success">Create a new article</button></a>
                            <h4>News Articles</h4>
                            <ul id="news-list">
                                @if(count($articles) == 0)
                                    > No Articles have been made yet.
                                @else
                                    @foreach ($articles as $article)
                                        <li>
                                            <h4>{{ $article->title }}</h4>
                                            <p><strong>Authored By: </strong>{{$article->user->name}}</p>
                                            <div class="article-options">
                                                <a href="/news/{{$article->id}}">
                                                    <button class="btn btn-info">
                                                        Edit Article
                                                    </button>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
