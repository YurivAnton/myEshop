<?php

namespace App\Http\Controllers;

use App\Category;
use App\Order;
use App\Product;
use Illuminate\Http\Request;

class Admin extends Shop
{
    public function admin(Request $request)
    {
        $classActiveAdmin = 'class="active"';
        $this->products = Product::all();
        $this->categories = Category::all();
        $maxPrice = Product::max('price');
        $minPrice = Product::min('price');
        $editId = '';
        $editName = '';
        $editing = '';

        if ($request->has('sort')) {
            $this->products = $this->sort($request->get('sort'), $request->get('maxPrice'));
        }

        if ($request->has('search')) {
            $this->search($request->get('search'));
        }

        if ($request->has('productId')){
            $editId = $request->get('productId');
            $editName = $request->get('edit');
            $editing = 'Product name';
        }elseif ($request->has('categoryId')){
            $editId = $request->get('categoryId');
            $editName = $request->get('edit');
            $editing = 'Category name';
        }elseif ($request->has('priceId')){
            $editId = $request->get('priceId');
            $editName = $request->get('edit');
            $editing = 'Price';
        }

        return view('shop.admin', [
            'products' => $this->products,
            'categories'=> $this->categories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'classActiveAdmin'=>$classActiveAdmin,
            'editId'=>$editId,
            'editName'=>$editName,
            'editing'=>$editing
        ]);
    }

    public function adminOrders(Request $request)
    {
        $classActiveAdmin = 'class="active"';
        $orders = Order::orderBy('ordersNumber', 'desc')->get();
        if ($request->has('ordersNumber')){
            $ordersNumber = $request->get('ordersNumber');
            $orderId = $request->get('orderId');
            $showOrder = Order::find($orderId)->products;
            $quantity = Order::find($orderId)->order_products;
        }else{
            $showOrder = '';
            $ordersNumber = '';
            $quantity = [];
        }
        return view('shop.adminOrders', [
            'quantity'=>$quantity,
            'showOrder'=>$showOrder,
            'ordersNumber'=>$ordersNumber,
            'orders'=>$orders,
            'classActiveAdmin'=>$classActiveAdmin
        ]);
    }
}
