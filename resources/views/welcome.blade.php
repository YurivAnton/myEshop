<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="/css/app.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>
<header>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @yield('menu')
        </ul>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
            @yield('aside')
        </div>
        <div class="col-xs-12 col-sm-8 col-sm-push-1 col-md-8 col-md-push-1 col-lg-8">
            @yield('main')
        </div>
    </div>
</div>

</body>












<!--    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        &lt;!&ndash; Fonts &ndash;&gt;
&lt;!&ndash;        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">&ndash;&gt;

        &lt;!&ndash; Styles &ndash;&gt;
        <link href="/css/app.css" rel="stylesheet">
        <script>
            window.Laravel = <?php /*echo json_encode([
                'csrfToken' => csrf_token(),
            ]); */?>
        </script>
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                /*font-family: 'Raleway', sans-serif;*/
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

            .form{
                margin-left: auto;
                margin-right: auto;
            }

            .content {
                border: 1px solid black;
                width: 800px;
                margin-left: auto;
                margin-right: auto;
                color: black;
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

            #countItems{
                border: 1px solid black;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            aside{
                max-width: 200px;

            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            {{--@if (Route::has('login'))--}}
                <div class="top-right links">
                    {{--@if (Auth::check())--}}
&lt;!&ndash;                        <a href="{{ url('/home') }}">Home</a>&ndash;&gt;
                        {{--@yield('order')--}}


                        <a href="{{ url('/logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                    {{--@else--}}

                        <a href="{{ url('/login') }}">Login</a>

                        <a href="{{ url('/register') }}">Register</a>
                    {{--@endif--}}
                </div>
            {{--@endif--}}
            <aside>
                {{--@yield('aside')--}}
            </aside>
            <div class="content">
                {{--@if (session('message'))--}}
                    <div class="alert alert-success">
                        {{--{{ session('message') }}--}}
                    </div>
            {{--@endif--}}
            {{--@yield('main')--}}
            </div>
        </div>
        <script src="/js/app.js"></script>
    </body>-->
</html>
