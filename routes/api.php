<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\RegisterController@register');
Route::post('login', 'Api\RegisterController@login');


Route::middleware('auth:api')->group( function () {
        Route::get('/list-products', 'Api\ProductController@listProducts');
        Route::post('/add-products', 'Api\ProductController@addProduct');
        
        Route::get('/list-category', 'Api\CategoryController@listCategory');
        Route::post('/add-category', 'Api\CategoryController@addCategory');
        
        Route::post('/buy-product', 'Api\CartController@buyProduct');
        Route::get('/my-cart', 'Api\CartController@myCart');
});