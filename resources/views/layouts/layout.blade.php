<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="/css/app.css" rel="stylesheet">
<!--    <script src="https://code.jquery.com/jquery.min.js"></script>-->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>
<header class="page-header text-center">
    <h1>myEshop</h1>
</header>
<main>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-sm-12 col-md-12 col-lg-12">
            <div>
                <ul class="nav nav-pills">
                    <li {!! $classActiveHome or '' !!}><a href="/">Home</a></li>
                    <li {!! $classActiveOrder or '' !!}><a href="/order">Order<span class="badge">{{ $countItems or ''}}</span></a></li>
                    <li {!! $classActiveAdmin or '' !!}class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin">Admin home</a></li>
                            <li><a href="/admin/orders">Show all orders</a></li>
                            <li><a href="/admin">Show all products</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Examples</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            @yield('aside')
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-5 col-lg-offset-2">
            @yield('main')
        </div>
    </div>
</div>
</main>
<footer class="text-center">
    created by Yuriv Anton
</footer>
<script src="/js/app.js"></script>
</body>