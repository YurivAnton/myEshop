@extends('welcome')

@section('order')
    <a href="/">HOME</a>
@endsection

@section('main')
<table border="1" class="form">
    <th>Num</th>
    <th>Name</th>
    <th>Quantity</th>
    <th>Price for one</th>
    <th>Sum</th>
    {!! $result !!}
</table>
@endsection