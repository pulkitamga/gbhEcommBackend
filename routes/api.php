<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\CategoreyController;


Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

//get category
Route::get('getCategory',[FrontendController::class,'category']);
Route::get('childcategory',[FrontendController::class,'childcategory']);
Route::get('vieworder',[OrderController::class,'vieworder']);
// product by category
Route::get('fetchproducts/{slug}',[FrontendController::class,'product']);
//Fetch all product
Route::get('fetchallproducts',[FrontendController::class,'index']);
//Fetch single product with category
Route::get('view-product/{category_slug}/{product_slug}',[FrontendController::class,'viewproduct']);
//Cart
Route::get('cart',[CartController::class,'viewcart']);
//Cart quantity Update
Route::put('cart-updatequantity/{card_id}/{scope}',[CartController::class,'updatequantity']);
Route::delete('delete-cartitem/{cart_id}',[CartController::class,'deletecartitem']);
//checkout api
Route::post('place-order',[CheckoutController::class,'placeorder']);
Route::post('validate-order',[CheckoutController::class,'validateorder']);

//add to cart
Route::post('add-to-cart',[CartController::class,'addtocart']);

//search
Route::get('search/{key}',[ProductController::class,'search']);
Route::get('userorder/{id}',[OrderController::class,'userorder']);
Route::get('viewUserorder/{id}',[OrderController::class,'viewUserorder']);

//admin api
Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function()
{
    Route::get('/checkingAuthenticated',function()
    {
        return response()->json(['message'=>'You are in','status'=>200],200);
    });
    //category data
    Route::post('store-category',[CategoreyController::class,'store']);
    Route::get('view-category',[CategoreyController::class,'index']);
    Route::get('edit-category/{id}',[CategoreyController::class,'edit']);
    Route::delete('delete-category/{id}',[CategoreyController::class,'destroy']);
    Route::put('update-category/{id}',[CategoreyController::class,'update']);
    Route::get('all-category',[CategoreyController::class,'allcategory']);
    
    //Profile Data
    Route::post('me',[AuthController::class,'me']);
    Route::get('view-user',[AuthController::class,'index']);
    Route::get('edit-user/{id}',[AuthController::class,'user_edit']);
    Route::post('change-password',[AuthController::class,'change_password']);
    Route::put('update-user/{id}',[AuthController::class,'update_user']);
    Route::post('logout',[AuthController::class,'logout']);

   //Products data
    Route::post('store-product',[ProductController::class,'store']);
    Route::get('view-product',[ProductController::class,'index']);
    Route::get('edit-product/{id}',[ProductController::class,'edit']);
    Route::post('update-product/{id}',[ProductController::class,'update']);
    Route::post('changeStatus/{id}',[AuthController::class,'statusupdate']);
    Route::get('getSlug',[ProductController::class,'getSlug']);
    Route::get('getbrand',[ProductController::class,'getbrand']);

    //order
    Route::get('orders',[OrderController::class,'index']);
    Route::get('latestorder',[OrderController::class,'latestorder']);
    Route::get('view-order/{id}',[OrderController::class,'view']);
    Route::get('ordersitems/{id}',[OrderController::class,'ordersitems']);
    Route::get('show/{id}',[OrderController::class,'show']);
    Route::post('statusUpdates/{id}',[OrderController::class,'update']);
    //brand
   Route::post('addBrand',[BrandController::class,'store']);
   Route::get('view-brand',[BrandController::class,'index']);
   //varient
   Route::post('addColor',[ColorController::class,'store']);
   Route::get('view-color',[ColorController::class,'index']);
});

Route::middleware(['auth:sanctum'])->group(function()
{
   Route::post('logout',[AuthController::class,'logout']);
});



