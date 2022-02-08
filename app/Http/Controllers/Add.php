<?php

namespace App\Http\Controllers;

use App\Category;
use App\Order;
use App\order_product;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Add extends Shop
{
    public function add(Request $request)
    {
        if ($request->has('Buy')) {
            $orderId = Order::where([
                ['status', 0],
                ['user_id', Auth::id()]
            ])->first();

            if (empty($orderId)) {
                $ordersNumber = Order::where('user_id', Auth::id())->max('ordersNumber');
                if ($ordersNumber == 0) {
                    $ordersNumber = '20220001';
                } else {
                    $ordersNumber += 1;
                }
                $orders = new Order();
                $orders->ordersNumber = $ordersNumber;
                $orders->date = date('Y-m-d H:i:s');
                $orders->status = 0;
                $orders->user_id = Auth::id();
                $orders->save();
            }

            $order_id = Order::where('user_id', Auth::id())->max('id');
            $order_product = new order_product();
            $order_product->order_id = $order_id;
            $order_product->product_id = $request->get('productId');
            if (!empty($request->get('quantity'))) {
                $order_product->quantity = $request->get('quantity');
            } else {
                $order_product->quantity = 1;
            }
            $order_product->save();
            return redirect('/');
        }

        if ($request->has('addNew')){
            $rules = [
                'newProductName'=>'required|alpha_num',
                'price'=>'required|numeric'
            ];
            $this->validate($request, $rules);

            $category = new Category();
            if ($request->has('newCategoryName')) {
                $newCategoryName = Category::where('name', '=', $request->get('newCategoryName'))->first();
                if (empty($newCategoryName)) {
                    $category->name = $request->get('newCategoryName');
                    $category->save();
                    $categoryId = Category::max('id');
                    //return $request->get('newCategoryName');
                } else {
                    return redirect('/admin')->with('message', 'exist category name');
                }
            }else{
                $category = Category::where('name', '=', $request->get('oldCategoryName'))->first();
                $categoryId = $category->id;
            }

            $product = new Product();
            $newProductName = Product::where([
                ['name', '=', $request->get('newProductName')],
                ['category_id', '=', $categoryId]
            ])->first();
            if (empty($newProductName)) {
                $product->name = $request->get('newProductName');
                $product->price = $request->get('price');
                $product->category_id = $categoryId;
                $product->save();
                return redirect('/admin')->with('message', 'add successfully');
            } else {
                return redirect('/admin')->with('message', 'exist product name');
            }
        }
    }
}
