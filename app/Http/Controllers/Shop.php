<?php

namespace App\Http\Controllers;

use App\Category;
use App\Order;
use App\order_product;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Shop extends Controller
{
    public $categories;
    public $products;

    public function showAllProducts(Request $request)
    {
        $classActiveHome = 'class="active"';
        $this->products = Product::all();
        $this->categories = Category::all();
        $maxPrice = Product::max('price');
        $minPrice = Product::min('price');
        $orderId = Order::where([
            ['status', 0],
            ['user_id', Auth::id()]
        ])->first();
        if(!empty($orderId)) {
            $countItems = order_product::where('order_id', $orderId->id)->count();
        }else{
            $countItems = '';
        }

        if ($request->has('sort')) {
            $products = $this->sort($request->get('sort'), $request->get('maxPrice'));
        }

        if ($request->has('search')) {
            $this->search($request->get('search'));
        }

        return view('shop.showAllProducts', [
            'products' => $this->products,
            'categories'=> $this->categories,
            'classActiveHome'=> $classActiveHome,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'countItems' => $countItems
        ]);
    }


    public function order(Request $request)
    {
        $classActiveOrder = 'class="active"';
        $orderId = Order::where([
            ['status', 0],
            ['user_id', Auth::id()]
        ])->first();

        if ($request->has('confirm')){
            $order = $orderId;
            $order->status = 1;
            $order->save();
            return redirect('/')->with('message', 'order was confirmed! Thank you');
        }

        if ($request->has('clear')){
            order_product::where('order_id', '=', $orderId->id)->delete();
            return redirect('/')->with('message', 'order was cleared! Bey');
        }

        if(!empty($orderId)) {
            $order = Order::find($orderId->id)->products;
            $quantity = Order::find($orderId->id)->order_products;
        }else{
            $order = [];
            $quantity = [];
        }

        $order_product_id = '';

        if ($request->has('quantity')){
            $editQuantity = $request->get('quantity');
            foreach ($quantity as $item){
                if ($item->quantity == $editQuantity){
                    $order_product_id = $item->id;
                }
            }
        }else{
            $editQuantity = '';
        }

        return view('shop.order', [
            'order_product_id'=>$order_product_id,
            'editQuantity'=>$editQuantity,
            'ordersNumber'=>$orderId,
            'order'=>$order,
            'quantity'=>$quantity,
            'classActiveOrder'=>$classActiveOrder
        ]);

    }
    public function edit(Request $request)
    {
        $rules = [
            'name'=>'required|alpha_num'
        ];
        $this->validate($request, $rules);

        if ($request->has('editing')) {
            $id = $request->get('editingId');
            $name = $request->get('editing');
            if ($request->get('editing') == 'Category name') {
                $category = Category::find($id);
                $category->name = $request->get('name');
                $category->save();
            } else {
                $product = Product::find($id);
                if ($request->get('editing') == 'Price') {
                    $product->price = $request->get('name');
                } else {
                    $product->name = $request->get('name');
                }
                $product->save();

            }
            return redirect('/admin')->with('message', "$name was changed");
        }

        if ($request->has('order_product_id')) {
            $id = $request->get('order_product_id');
            $order_product = order_product::find($id);
            $order_product->quantity = $request->get('name');
            $order_product->save();
            return redirect('/order')->with('message', 'quantity was changed');
        }
    }

    public function delete(Request $request)
    {
        $delete = $request->get('delete');
        if ($delete == 'delete order'){
            $order_product_id = $request->get('deleteOrder');
            order_product::where('id', '=', $order_product_id)->delete();
            return redirect('/order')->with('message', 'delete');
        }elseif ($delete == 'deleteProduct'){
            $productId = $request->get('deleteProductId');
            $categoryId = $request->get('categoryId');
            Product::where('id', '=', $productId)->delete();
            $count = Product::where('category_id', '=', $categoryId)->count();
            if($count == 0){
                Category::where('id', '=', $categoryId)->delete();
            }
            return redirect('/admin')->with('message', 'delete');
        }else{
            $categoryId = $request->get('categoryId');
            Product::where('category_id', '=', $categoryId)->delete();
            Category::where('id', '=', $categoryId)->delete();
            return redirect('/admin')->with('message', 'delete whole category');
        }
    }



    public function sort($sort, $price)
    {
        $sortBy = preg_replace('#.+_#', '', $sort);
        $witchColumn = preg_replace('#sortBy_(.*)_.*#', '\1', $sort);

        if($sortBy == 'A-Z' OR $sortBy == 'to MAX'){
            $sort = 'asc';
        }else{
            $sort = 'desc';
        }
        return Product::where('price', '<=', $price)
            ->orderBy($witchColumn, $sort)->get();
    }

    public function search($search)
    {
        $keyWord = trim($search);
        $searchCategory = Category::where('name', 'LIKE', "%$keyWord%")->get();
        $searchProduct = Product::where('name', 'LIKE', "%$keyWord%")->get();

        if(!empty($searchCategory[0])) {
            $this->categories = $searchCategory;
        }elseif (!empty($searchProduct[0])) {
            $this->products =  $searchProduct;
        }

    }
}
