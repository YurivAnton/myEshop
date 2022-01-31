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
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/add" method="get">
        <label>
            <p>
                Type new product name<br>
                <input name="newProductName">
            </p>
        </label>
            <p>
                <label>Select category Name</label><br>
                {!! $selectCategory !!}
            </p>
        Or<br>
        <label>
            <p>
                Type new category name<br>
                <input name="newCategoryName">
            </p>
        </label><br>
        <label>
            <p>
                Type price<br>
                <input name="price">
            </p>
        </label>
        <p>
            <input type="submit" name="addNew" value="addNew">
        </p>
    </form>

    <table class="table table-striped table-bordered">
        <caption>
            <form role="search">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="search">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                            <i class="glyphicon glyphicon-search">search</i>
                        </button>
                    </span>
                </div>
            </form>
        </caption>
        <thead>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Delete</th>
        </thead>
        <tbody>
            {!! $result !!}
        </tbody>
    </table>
@endsection