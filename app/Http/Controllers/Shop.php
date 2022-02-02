<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Shop extends Controller
{
    private $orders;
    private $categories;
    private $products;
    private $minPrice;
    private $maxPrice;

    public function __construct()
    {
        $this->orders = DB::table('orders')
            ->where('status', '=', 0)
            ->first();
        $this->categories = Category::all();
        $this->products = Product::orderBy('category_id')->get();
        $this->minPrice = Product::min('price');
        $this->maxPrice = Product::max('price');
    }

    public function showAllProducts(Request $request)
    {
        $id = Auth::id();
        $order = DB::table('orders')
            ->where([
                ['status', '=', 0],
                ['user_id', '=', $id]
            ])
            ->first();
        if (!empty($order)) {
            $countItems = DB::table('zakazTovar')
                ->where('order_id', $order->id)
                ->count();
        }else{
            $countItems = '';
        }

        if ($request->has('sort')) {
            $this->products = $this->sort($request->get('sort'), $request->get('maxPrice'));
        }

        if ($request->has('search')) {
            $this->search($request->get('search'));
        }

        $result = '';
        foreach ($this->products as $product) {
            $result .= '<tr>';
            foreach ($this->categories as $category) {
                if ($category->id == $product->category_id) {
                    $result .= '<form action="/add" method="get">';
                    $result .= '<td style="padding: 0"><input size="1" type="text" name="productName" value="' . $product->name . '" class="form-control" disabled="disabled"></td>';
                    $result .= '<td style="padding: 0"><input size="1" type="text" name="categoryName" value="' . $category->name . '" class="form-control" disabled="disabled"></td>';
                    $result .= '<td style="padding: 0"><input size="1" type="text" name="productPrice" value="' . $product->price . '" class="form-control" disabled="disabled"></td>';
                    $result .= '<td style="padding: 0"><input size="1" type="text" name="quantity" class="form-control"></td>';
                    $result .= '<input type="hidden" name="productId" value="' . $product->id . '">';
                    $result .= '<td style="padding: 0"><input size="1" type="submit" name="Buy" value="Buy" class="form-control"></td>';
                    $result .= '</form>';








                    /*$result .= '<td>' . $product->name . '</td>';
                    $result .= '<td>' . $category->name . '</td>';
                    $result .= '<td>' . $product->price . '</td>';
                    $result .= '<td>
                                        <form action="/add" method="get"> 
                                            <input name="quantity">
                                            <input type="hidden" name="productId" value="' . $product->id . '">
                                            <input type="hidden" name="productName" value="' . $product->name . '">
                                            <input type="hidden" name="productPrice" value="' . $product->price . '">
                                    </td>
                                    <td>
                                            <input type="submit" name="Buy" value="Buy">
                                        </form>
                                    </td>';*/
                }
                $result .= '</tr>';
            }
        }
        //$request->session()->forget('maxOrderId');
        return view('shop.showAllProducts', [
            'result' => $result,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'countItems' => $countItems
        ]);
    }

    public function add(Request $request)
    {

        $userId = Auth::id();
        $orderId = DB::table('orders')
            ->where('user_id', '=', $userId)
            ->max('id');
        $ordersNumber = '2022000'.$orderId+1;
        $status = 0;
        $date = date('Y-m-d H:i:s');
        //$userId = Auth::id();
        $productId = $request->productId;

        if ($request->quantity == ''){
            $quantity = 1;
        }else {
            $quantity = $request->quantity;
        }

        if ($request->has('addNew')){
            $rules = [
                'newProductName'=>'required|alpha_num',
                'price'=>'required|numeric'
            ];
            $this->validate($request, $rules);

            $newProduct = new Product;
            $newProduct->name = $request->get('newProductName');
            $newProduct->price = $request->get('price');
            if ($request->has('newCategoryName')){
                $newCategory = new Category;
                $newCategory->name = $request->get('newCategoryName');
                $newCategory->save();
                $category_id = Category::max('id');
            }else{
                $category_id = $request->get('oldCategoryName');
            }
            $newProduct->category_id = $category_id;
            $newProduct->save();
            return redirect('/admin')->with('message', 'add successfully');
        }
        if ($request->has('Buy')) {
            if ($request->session()->has('maxOrderId')) {
                if ($request->session()->get('maxOrderId') == $orderId) {
                    DB::table('zakazTovar')->insert([
                        'order_id' => $orderId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                    return redirect('/');
                }
            } else {
                $orderExist = DB::table('orders')
                    ->where([
                        ['user_id', '=', $userId],
                        ['status', '=', 0]
                    ])
                    ->first();
                $maxOrderId = $orderExist->id;
                if (empty($orderExist)) {
                    DB::table('orders')->insert([
                        'ordersNumber' => $ordersNumber,
                        'date' => $date,
                        'status' => $status,
                        'user_id' => $userId
                    ]);
                    $maxOrderId = $orderId;
                }

                DB::table('zakazTovar')->insert([
                    'order_id' => $maxOrderId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
                $request->session()->put('maxOrderId', $maxOrderId);
                return redirect('/');
            }
        }
    }

    public function order(Request $request)
    {
        $userId = Auth::id();

        if ($request->has('confirm')){
            $request->session()->forget('maxOrderId');
            DB::table('orders')
                ->where('user_id', $userId)
                ->update(['status'=>1]);
            return redirect('/')->with('message', 'order was confirmed! Thank you');
        }

        $order = DB::table('orders')
            ->where([
                ['orders.status', '=',0],
                ['orders.user_id', '=', $userId],
                /*['orders.id', '=', $maxId]*/
            ])
            ->join('zakazTovar', 'orders.id', '=', 'zakazTovar.order_id')
            ->join('products', 'products.id', '=', 'zakazTovar.product_id')
            ->select('orders.ordersNumber', 'orders.id', 'zakazTovar.quantity', 'zakazTovar.id as zakazId', 'products.name', 'products.price')
            ->get();

        if ($request->has('clear')){
            DB::table('orders')
                ->where('id', '=', $order[0]->id)
                ->delete();
            DB::table('zakazTovar')
                ->where('order_id', '=', $order[0]->id)
                ->delete();
            $request->session()->forget('maxOrderId');
            return redirect('/')->with('message', 'order was cleared! Bey');
        }

        $result = '';
        foreach ($order as $item){
            $result .= '<tr>';
            $result .= '<td>'.$item->ordersNumber.'</td>';
            $result .= '<td>'.$item->name.'</td>';

            $result .= '<td><a href="/order?edit='.$item->quantity.'&zakazId='.$item->zakazId.'">'.$item->quantity.'</a></td>';
            $result .= '<td>'.$item->price.'</td>';
            $result .= '<td>'.$item->quantity * $item->price.'</td>';
            $result .= '<td>
                            <a href="/delete?delete='.$item->zakazId.'&deleteId='.$item->id.'&table_1=zakazTovar&table_2=orders&whichColumn=order_id">delete</a>
                        </td>';
            $result .= '</tr>';
        }

        if ($request->has('edit')){
            $result .=
                '<form action="/edit" method="get">
                    edit quantity
                    <input name="name" placeholder="'.$request->get('edit').'">
                    <input type="hidden" name="zakazId" value="'.$request->get('zakazId').'">
                    <input type="submit" value="edit">
                </form>';
        }

        return view('shop.order', ['order'=>$order, 'result'=>$result]);
    }

    public function edit(Request $request)
    {
        $rules = [
            'name'=>'required|alpha_num'
        ];
        $this->validate($request, $rules);

        if ($request->has('productId')){
            if ($request->has('price')){
                $whichColumn = 'price';
            }else{
                $whichColumn = 'name';
            }
            DB::table('products')
                ->where('id', '=', $request->get('productId'))
                ->update([$whichColumn=>$request->get('name')]);
            return redirect('/admin')->with('message', 'change is successfully');
        }

        if ($request->has('categoryId')){
            DB::table('categories')
                ->where('id', '=', $request->get('categoryId'))
                ->update(['name'=>$request->get('name')]);
            return redirect('/admin')->with('message', 'change is successfully');
        }

        DB::table('zakazTovar')
            ->where('id', '=', $request->get('zakazId'))
            ->update(['quantity'=>$request->get('name')]);
        return redirect('/order')->with('message', 'quantity was changed');
    }

    public function delete(Request $request)
    {
        $table_1 = $request->get('table_1');
        $table_2 = $request->get('table_2');
        $whichColumn = $request->get('whichColumn');
        $deleteId = $request->get('deleteId');

        DB::table($table_1)
            ->where('id', '=', $request->get('delete'))
            ->delete();

        $count = DB::table($table_1)
            ->where($whichColumn, '=', $deleteId)
            ->count();
        if ($count == 0){
            DB::table($table_2)
                ->where('id', '=', $deleteId)
                ->delete();
            $request->session()->forget('maxOrderId');
        }
        if ($table_1 == 'zakazTovar' AND $request->has('ordersNumber')){
            $ordersNumber = $request->get('ordersNumber');
            return redirect("/admin/orders?ordersNumber=$ordersNumber")->with('message', 'delete');
        }elseif ($table_1 == 'zakazTovar'){
            return redirect('/order')->with('message', 'delete');
        }elseif($table_1 == 'products') {
            return redirect('/admin')->with('message', 'delete');
        }
    }

    public function admin(Request $request)
    {
        if ($request->has('sort')) {
            $this->products = $this->sort($request->get('sort'), $request->get('maxPrice'));
        }

        if ($request->has('search')) {
            $this->search($request->get('search'));
        }

        $result = '';

        foreach ($this->products as $product) {
            $result .= '<tr>';
            $selectCategory = '<select name="oldCategoryName">';
            foreach ($this->categories as $category) {
                $selectCategory .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                if ($category->id == $product->category_id) {
                    $result .= '<td>
                                    <a href="/admin?edit=' . $product->name . '&productId='.$product->id.'">' . $product->name . '</a>
                                </td>';
                    $result .= '<td>
                                    <a href="/admin?edit=' . $category->name . '&categoryId='.$category->id.'">' . $category->name . '</a>
                                </td>';
                    $result .= '<td>
                                    <a href="/admin?edit=' . $product->price . '&productId='.$product->id.'&price=' . $product->price . '">' . $product->price . '</a>
                                </td>';
                    $result .= '<td>
                                    <a href="/delete?delete='.$product->id.'&deleteId='.$category->id.'&table_1=products&table_2=categories&whichColumn=category_id">delete</a>    
                                </td>';
                }
                $result .= '</tr>';
            }
            $selectCategory .= '</select>';
        }

        if ($request->has('edit')){
            $result .=
                '<form action="/edit" method="get">
                    <input name="name" placeholder="'.$request->get('edit').'">
                    <input type="hidden" name="productId" value="'.$request->get('productId').'">
                    <input type="hidden" name="categoryId" value="'.$request->get('categoryId').'">
                    <input type="hidden" name="price" value="'.$request->get('price').'">
                    <input type="submit" value="edit">
                </form>';
        }

        return view('shop.admin', [
            'result' => $result,
            'selectCategory' => $selectCategory,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
        ]);
        return view('shop.admin');
    }

    public function adminOrders(Request $request)
    {
        $ordersNumber = $request->get('ordersNumber');

        $orders = DB::table('orders')
            ->orderBy('date', 'desc')
            ->get();
        $li = '';
        foreach ($orders as $order){
            $li .= '<li><a href="/admin/orders?ordersNumber='.$order->ordersNumber.'">'.$order->ordersNumber.'</a></li>';
        }

        $result = '';
        if ($request->has('ordersNumber')){
            $order = DB::table('orders')
                ->where('ordersNumber', '=', $request->get('ordersNumber'))
                ->join('zakazTovar', 'orders.id', '=', 'zakazTovar.order_id')
                ->join('products', 'products.id', '=', 'zakazTovar.product_id')
                ->select('orders.ordersNumber', 'orders.id', 'zakazTovar.quantity', 'zakazTovar.id as zakazId', 'products.name', 'products.price')
                ->get();
            foreach ($order as $item){
                $result .= '<tr>';
                $result .= '<td>'.$item->name.'</td>';
                $result .= '<td>'.$item->quantity.'</td>';
                $result .= '<td>'.$item->price.'</td>';
                $result .= '<td>'.$item->quantity * $item->price.'</td>';
                $result .= '<td>
                            <a href="/delete?delete='.$item->zakazId.'&deleteId='.$item->id.'&ordersNumber='.$ordersNumber.'&table_1=zakazTovar&table_2=orders&whichColumn=order_id">delete</a>
                        </td>';
                $result .= '</tr>';
            }

        }

        return view('shop.adminOrders', ['li'=>$li, 'ordersNumber'=>$ordersNumber, 'result'=>$result]);
    }

    private function sort($sort, $price)
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
        $searchCategory = Category::where('name', '=', $keyWord)
                                    ->orWhere('name', '=', strtoupper($keyWord))->get();
        $searchProduct = Product::where('name', '=', $keyWord)->get();

        if(!empty($searchCategory[0])) {
            $this->categories = $searchCategory;
        }

        if (!empty($searchProduct[0])) {
            $this->products = $searchProduct;
        }
    }
}
