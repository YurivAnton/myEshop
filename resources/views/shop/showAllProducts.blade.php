@extends('welcome')

@section('main')
    <table border="1">
        <th>Name</th>
        <th>Category</th>
        <th>Buy</th>
        {!! $result !!}
    </table>
    {{ $products }}
    {{ $categories }}
@endsection