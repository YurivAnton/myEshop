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
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-6 col-xs-offset-3 col-sm-8 col-md-9 col-lg-5 col-lg-offset-2">
            <form role="form" action="/add" method="get">
                <div class="form-group">
                    <label for="product">Type new product name</label>
                    <input name="newProductName" class="form-control" id="product" value="{{ old('newProductName') }}" placeholder="Type new product name">
                </div>
                <div class="form-group">
                    <label for="oldCategory">Select category Name</label>
                    <select name="oldCategoryName" id="oldCategory" class="form-control">
                        @foreach($categories as $category)
                            @if($category->name == old('oldCategoryName'))
                                <option selected value="{{ $category->name }}">{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="newCategory">Or type new category name</label>
                    <input name="newCategoryName" class="form-control" id="newCategory" placeholder="Type new category name">
                </div>
                <div class="form-group">
                    <label for="price">Type price</label>
                    <input name="price"  class="form-control" id="price" placeholder="Type the price">
                </div>
                <input name="addNew" type="submit" class="btn btn-success" value="addNew">
            </form>
        </div>
    </div>
    <table class="table table-striped table-bordered">
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
            <th>Price</th>
            <th>Category</th>
            <th>Delete</th>
            <th>Delete whole category</th>
        </thead>
        <tbody>
        @if(!empty($editId))
            <form role="form" action="/edit" method="get">
                <label for="{{ $editName }}">Edit</label>
                <div class="input-group">
                    <input type="text" name="name" class="form-control" id="{{ $editName }}" placeholder="{{ $editName }}">
                    <input type="hidden" name="editingId" value="{{ $editId }}">
                    <input type="hidden" name="editing" value="{{ $editing }}">
                    <span class="input-group-btn">
                <button class="btn btn-info" type="submit">
                    <i class="glyphicon">edit</i>
                </button>
            </span>
                </div>
            </form>
        @endif
        @foreach($categories as $category)
            @foreach($products as $product)
                <tr>
                    @if ($category->id == $product->category_id)
                        <form action="/admin" method="get" role="form">
                            <td style="padding: 0"><input type="submit" name="edit" value="{{ $product->name }}" class="form-control"></td>
                            <input type="hidden" name="productId" value="{{ $product->id }}">
                        </form>

                        <form action="/admin" method="get" role="form">
                            <td style="padding: 0"><input type="submit" name="edit" name="productPrice" value="{{ $product->price }}" class="form-control"></td>
                            <input type="hidden" name="priceId" value="{{ $product->id }}">
                        </form>
                        <form action="/admin" method="get" role="form">
                            <td style="padding: 0"><input type="submit" name="edit" value="{{ $category->name }}" class="form-control"></td>
                            <input type="hidden" name="categoryId" value="{{ $category->id }}">
                        </form>
                        <form action="/delete" method="get">
                            <td style="padding: 0"><input size="1" type="submit" name="delete" value="deleteProduct" class="form-control"></td>
                            <td style="padding: 0"><input size="1" type="submit" name="delete" value="Delete whole category {{ $category->name }}" class="form-control"></td>
                            <input type="hidden" name="categoryId" value="{{ $category->id }}">
                            <input type="hidden" name="deleteProductId" value="{{ $product->id }}">
                        </form>
                    @endif
                </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
@endsection