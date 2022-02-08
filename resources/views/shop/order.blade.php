@extends('layouts.layout')

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

<table class="table table-bordered">
    <thead>
        <th>Order's Number</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price for one</th>
        <th>Sum</th>
    </thead>
    <tbody>
@if(!empty($editQuantity))
    <form role="form" action="/edit" method="get">
        <label for="quantity">Edit quantity</label>
        <div class="input-group">
            <input type="text" name="name" class="form-control" id="quantity" placeholder="{{ $editQuantity }}">
            <input type="hidden" name="order_product_id" value="{{ $order_product_id }}">
            <span class="input-group-btn">
                <button class="btn btn-info" type="submit">
                    <i class="glyphicon">edit</i>
                </button>
            </span>
        </div>
    </form>
@endif
    @foreach($quantity as $elem)
        @foreach($order as $item)
            @if($elem->product_id == $item->id)
            <tr>
                <td style="padding: 0"><input type="text" name="ordersNumber" value="{{ $ordersNumber->ordersNumber }}" class="form-control" disabled="disabled"></td>
                <td style="padding: 0"><input type="text" name="productName" value="{{ $item->name }}" class="form-control" disabled="disabled"></td>
                    <form action="" method="get">
                        <td style="padding: 0"><input size="1" type="submit" name="quantity" value="{{ $elem->quantity }}" class="form-control" ></td>
                    </form>
                <td style="padding: 0"><input type="text" name="price" value="{{ $item->price }}" class="form-control" disabled="disabled"></td>
                <td style="padding: 0"><input type="text" name="sum" value="{{ $item->price * $elem->quantity}}" class="form-control" disabled="disabled"></td>
                <form action="/delete" method="get">
                    <td style="padding: 0"><input size="1" type="submit" name="delete" value="delete order" class="form-control"></td>
                    <input type="hidden" name="deleteId" value="{{ $item->pivot['order_id'] }}">
                    <input type="hidden" name="deleteOrder" value="{{ $elem->id }}">
                </form>
            </tr>
            @php break; @endphp
            @endif
        @endforeach
    @endforeach
    </tbody>
</table>

<form role="form" action="" method="get">
    <input class="btn btn-success" type="submit" name="confirm" value="confirm">
    <input class="btn btn-danger" type="submit" name="clear" value="clear">
</form>
@endsection