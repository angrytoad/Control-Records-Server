<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Control Records</div>
        <h1>Please login</h1>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="login-form" method="post" action="/login">
            <input type="text" name="email" placeholder="Email" />
            <input type="text" name="password" placeholder="Password"/>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" />
        </form>
    </div>
</div>
</body>
</html>
