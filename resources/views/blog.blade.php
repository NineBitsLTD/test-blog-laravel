<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <link href="https://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:200,500" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .list-group>a{
                padding-left: 120px;
                min-height: 120px;
            }
            .list-group img{
                position: absolute;
                left: 10px;
                top: 10px;
                max-width: 100px;
                max-height: 100px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('admin/blog') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    {{ config('app.name') }}
                </div>
                <article class="form-group">
                    @if (isset($items) && is_array($items) && isset($items['data']) && is_array($items['data']))
                    <h3>List of posts.</h3>
                    <div class="list-group">
                        <?php foreach ($items['data'] as $item) { ?>
                        <a class="list-group-item list-group-item-action flex-column align-items-start" href="{{ url('blog/'.$item['code']) }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $item['title'] }}</h5>
                                <small class="text-muted">{{ date("H:i d.m.Y", strtotime($item['created_at'])) }}</small>
                            </div>
                            @if (file_exists('img/post/'.$item['id']))
                            <img class="img-thumbnail float-left" src="{{ asset('img/post/'.$item['id']) }}">
                            @endif
                            <p class="mb-1">{{ $item['preview'] }}</p>
                        </a>
                        <?php } ?>
                        <?= view('layouts.pagination',['items'=>$items]) ?>
                    </div>
                    @else
                    <h3>Posts not found.</h3>
                    @endif
                </article>
            </div>
        </div>
    </body>
</html>
