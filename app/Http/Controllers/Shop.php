<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Shop extends Controller
{
    private $order;

    public function __construct()
    {
        $this->order = DB::table('orders')->where('status', 0)->first();
    }

    public function showAllProducts(Request $request)
    {
        $categories = Category::all();
        $products = Product::orderBy('category_id')->get();
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

        $countItems = DB::table('zakazTovar')->where('order_id', $this->order->id)->count();

        if ($request->has('sort')){
            if ($request->has('maxPrice')){
                $price = $request->get('maxPrice');
            }else{
                $price = $maxPrice;
            }
            $sortBy = preg_replace('#.+_#', '', $request->get('sort'));
            $witchColumn = preg_replace('#sortBy_(.*)_.*#', '\1', $request->get('sort'));
            $products = $this->sort($sortBy, $witchColumn, $price);
        }

        if ($request->has('search')) {
            $keyWord = trim($request->get('search'));
            $searchProduct = Product::where('name', '=', $keyWord)->get();
            $searchCategory = Category::where('name', '=', $keyWord)->get();
            if (!empty($searchProduct)){
                $products = $searchProduct;
                $categories = Category::all();
            }elseif (!empty($searchCategory)){
                $categories = $searchCategory;
                $products = Category::where('name', '=', $keyWord)->first()->products;
            }
        }

        $result = '';
        foreach ($products as $product){
            $result .= '<tr>';
            foreach ($categories as $category){
                    if($category->id == $product->category_id){
                        $result .= '<td>'.$product->name.'</td>';
                        $result .= '<td>'.$category->name.'</td>';
                        $result .= '<td>'.$product->price.'</td>';
                        $result .= '<td>
                                        <form action="" method="get"> 
                                            <input name="pcs">
                                            <input type="hidden" value="'.$product->id.'">
                                    </td>
                                    <td>
                                            <input type="submit" name="buy" value="Buy">
                                        </form>
                                    </td>';
                    }
                $result .= '</tr>';
            }
        }

        return view('shop.showAllProducts', [
            'products'=>$products,
            'categories'=>$categories,
            'result'=>$result,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'countItems'=>$countItems
        ]);
    }

    public function order(Request $request)
    {
        $order = DB::table('orders')
            ->join('zakazTovar', 'orders.id', '=', 'zakazTovar.order_id')
            ->join('products', 'products.id', '=', 'zakazTovar.product_id')
            ->select('orders.ordersNumber', 'zakazTovar.quantity', 'products.name', 'products.price')
            ->get();

        $result = '';
        foreach ($order as $item){
            $result .= '<tr>';
            $result .= '<td>'.$item->ordersNumber.'</td>';
            $result .= '<td>'.$item->name.'</td>';
            $result .= '<td>'.$item->quantity.'</td>';
            $result .= '<td>'.$item->price.'</td>';
            $result .= '<td>'.$item->quantity * $item->price.'</td>';



        }
        return view('shop.order', ['order'=>$order, 'result'=>$result]);
    }

    private function sort($sortBy, $witchColumn, $price)
    {
        if($sortBy == 'A-Z' OR $sortBy == 'to MAX'){
            $sort = 'asc';
        }else{
            $sort = 'desc';
        }
        return Product::where('price', '<=', $price)
            ->orderBy($witchColumn, $sort)->get();
    }
}
