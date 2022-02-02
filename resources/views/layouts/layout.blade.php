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
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-sm-12">
            @yield('menu')
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            @yield('aside')
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-5 col-lg-offset-2">
            @yield('main')
        </div>
    </div>
</div>
<footer class="text-center">
    created by Yuriv Anton
</footer>
<script src="/js/app.js"></script>
</body>