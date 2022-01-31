@extends('welcome')

@section('menu')
    <li><a href="/">HOME</a></li>
    <li><a href="/admin/orders">Show all orders</a></li>
    <li><a href="/admin">Show all products</a></li>
@endsection

@section('aside')
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-main">
                    <ol>
                        {!! $li !!}
                    </ol>
            </div>
        </div>
    </nav>
@endsection

@section('main')
    <table class="table table-striped table-bordered">
        <caption>
            {{ $ordersNumber }}
        </caption>
        <thead>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price for one</th>
            <th>Sum</th>
        </thead>
        <tbody>
            {!! $result !!}
        </tbody>
    </table>
@endsection