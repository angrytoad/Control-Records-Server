@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new news article</div>
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <a href="/news"><button class="btn btn-info">Back to news manager</button></a>
                            <h4>Create an article</h4>
                            <form id="create-news-form" method="post" action="/news/create">
                                <div class="title-input">
                                    <label>Article Title: </label>
                                    <input class="col-sm-12" type="text" name="title" placeholder="Article Title" />
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Article Body: </label>
                                        <p>
                                            This Article Body uses markdown, for more information please see <a target="_blank" href="https://help.github.com/articles/basic-writing-and-formatting-syntax/">HERE.</a>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">
                                                <h4>Editor</h4>
                                                <textarea oninput="this.editor.update()" class="col-sm-12" id="article-body" name="body" placeholder="Article Body"></textarea>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4>Preview</h4>
                                                <div class="col-sm-12 row" id="preview"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input id="create-article-button" type="submit" class="btn btn-success" value="Create Article">
                            </form>
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
        new Editor($id("article-body"), $id("preview"));

    </script>
@endsection
