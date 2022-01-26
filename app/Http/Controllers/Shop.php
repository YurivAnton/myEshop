<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class Shop extends Controller
{
    public function showAllProducts(Request $request)
    {
        $categories = Category::all();
        $products = Product::orderBy('category_id')->get();
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

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

        if ($request->has('search')){
            $keyWord = trim($request->get('search'));

            foreach ($categories as $category){
                if ($category->name == $keyWord){
                    $categories = Category::where('name', '=', $keyWord)
                        ->get();
                }else{
                    $products = Product::where('category_id', '=', $category->id)->get();
                    foreach ($products as $product){
                        if ($product->name == $keyWord){
                            $products = Product::where('name', '=', $keyWord)
                                ->orWhere('name', '=', lcfirst($keyWord))
                                ->orWhere('name', '=', ucfirst($keyWord))
                                ->get();
                        }
                    }
                }
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
            'maxPrice'=>$maxPrice
        ]);
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
