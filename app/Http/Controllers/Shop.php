<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class Shop extends Controller
{
    public function showAllProducts()
    {
        $categories = Category::all();
        $products = Product::all();
        $result = '';
        foreach ($products as $product){
            $result .= '<tr>';
            foreach ($categories as $category){


                $result .= '<td>'.$product->name.'</td>';
                    if($category->id == $product->category_id){
                        $result .= '<td>'.$category->name.'</td>';
                    }else{
                        $result .= '';
                    }
                $result .= '</tr>';
            }

        }
        return view('shop.showAllProducts', ['products'=>$products, 'categories'=>$categories, 'result'=>$result]);
    }
}
