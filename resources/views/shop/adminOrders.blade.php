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
                    <ol>
                        @foreach($orders as $order)
                            <li><a href="/admin/orders?ordersNumber={{ $order->ordersNumber }}&orderId={{ $order->id }}">{{ $order->ordersNumber }}</a></li>
                        @endforeach
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
            @foreach($quantity as $elem)
                @foreach($showOrder as $item)
                    @if($elem->product_id == $item->id)
                        <tr>
                            <td style="padding: 0"><input type="text" name="productName" value="{{ $item->name }}" class="form-control" disabled="disabled"></td>
                            <td style="padding: 0"><input type="text" name="quantity" value="{{ $elem->quantity }}" class="form-control" disabled="disabled"></td>
                            <td style="padding: 0"><input type="text" name="price" value="{{ $item->price }}" class="form-control" disabled="disabled"></td>
                            <td style="padding: 0"><input type="text" name="sum" value="{{ $item->price * $elem->quantity}}" class="form-control" disabled="disabled"></td>
                        </tr>
                        @php break; @endphp
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection