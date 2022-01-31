@extends('welcome')

@section('menu')
    <li><a href="/">HOME</a></li>
    <li><a href="/admin">admin</a></li>
@endsection

@section('main')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<table class="table table-striped table-bordered">
    <thead>
        <th>Num</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price for one</th>
        <th>Sum</th>
    </thead>
    <tbody>
        {!! $result !!}
    </tbody>
</table>

<form action="" method="get">
    <input type="submit" name="confirm" value="confirm">
    <input type="submit" name="clear" value="clear">
</form>
@endsection