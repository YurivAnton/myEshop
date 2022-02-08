<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/', 'Shop@showAllProducts');
Route::get('/add', 'Add@add')->middleware('auth');
Route::get('/edit', 'Shop@edit')->middleware('auth');
Route::get('/delete', 'Shop@delete')->middleware('auth');
Route::get('/order', 'Shop@order')->middleware('auth');
Route::get('/admin', 'Admin@admin')->middleware('auth', 'CheckRole:admin');
Route::get('/admin/orders', 'Admin@adminOrders')->middleware('auth', 'CheckRole:admin');