@extends('welcome')

@section('main')
{{--{{ $test }}--}}
    <form action="" method="get">
        <p><input type="radio" name="sort" value="sortBy_name_A-Z">name A-Z</p>
        <p><input type="radio" name="sort" value="sortBy_name_Z-A">name Z-A</p>
        <p><input type="radio" name="sort" value="sortBy_category_id_A-Z">category A-Z</p>
        <p><input type="radio" name="sort" value="sortBy_category_id_A-Z">category A-Z</p>
        <p><input type="radio" name="sort" value="sortBy_price_Z-A">price to min</p>
        <p><input type="radio" name="sort" value="sortBy_price_A-Z">price to max</p>
        <p>
            {{ $minPrice }}
            <input name="maxPrice" type="range" min="{{ $minPrice }}" max="{{ $maxPrice }}" step="1" value="{{ $maxPrice }}">
            {{ $maxPrice }}
        </p>
        <input type="submit" value="sortBy">
    </form>

    <table border="1" class="form">
        <caption>
            <form action="" method="get">
                <input type="search" name="search">
                <input type="submit" value="search">
            </form>
        </caption>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Buy</th>
        {!! $result !!}
    </table>

@endsection