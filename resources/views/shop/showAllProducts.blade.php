@extends('layouts.layout')

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
                <form  action="" method="get">
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_name_A-Z" value="sortBy_name_A-Z">
                        <label for="sortBy_name_A-Z">name A-Z</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_name_Z-A" value="sortBy_name_Z-A">
                        <label for="sortBy_name_Z-A">name Z-A</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_category_id_A-Z" value="sortBy_category_id_A-Z">
                        <label for="sortBy_category_id_A-Z">category A-Z</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_category_id_Z-A" value="sortBy_category_id_Z-A">
                        <label for="sortBy_category_id_Z-A">category Z-A</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_price_Z-A" value="sortBy_price_Z-A">
                        <label for="sortBy_price_Z-A">price to min</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_price_A-Z" value="sortBy_price_A-Z">
                        <label for="sortBy_price_A-Z">price to max</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="sort" id="sortBy_price_A-Z" value="sortBy_price_A-Z">
                        <label for="sortBy_price_A-Z">price to max</label>
                    </div>
                    <div class="form-group">
                        {{ $minPrice }}<input name="maxPrice" type="range" min="{{ $minPrice }}" max="{{ $maxPrice }}" step="1" value="{{ $maxPrice }}">{{ $maxPrice }}
                    </div>
                    <button type="submit" value="sortBy" class="btn btn-default">sortBy</button>
                </form>
            </div>
        </div>
    </nav>
@endsection

@section('main')
    <table class="table table-striped table-bordered table-responsive">
        <caption>
            <form role="search">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="search">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                            <i class="glyphicon">search</i>
                        </button>
                    </span>
                </div>
            </form>
        </caption>
        <thead>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Buy</th>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
            @foreach($categories as $category)
                @if ($category->id == $product->category_id)
                    <form action="/add" method="get" role="form">
                        <td style="padding: 0"><input type="text" name="productName" value="{{ $product->name }}" class="form-control" disabled="disabled"></td>
                        <td style="padding: 0"><input type="text" name="categoryName" value="{{ $category->name }}" class="form-control" disabled="disabled"></td>
                        <td style="padding: 0"><input type="text" name="productPrice" value="{{ $product->price }}" class="form-control" disabled="disabled"></td>
                        <td style="padding: 0"><input size="1" type="text" name="quantity" class="form-control"></td>
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <td style="padding: 0"><input size="1" type="submit" name="Buy" value="Buy" class="form-control"></td>
                    </form>
                @endif
            </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
@endsection