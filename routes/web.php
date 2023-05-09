<?php

use App\Models\Category;
use App\Mail\PlaceOrderMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
Route::get('mail',function()
{
    Mail::to('pulkitamga0610@gmail.com')
    ->send(new PlaceOrderMailable());
});
Route::get('test',function(){
    return Category::with('childs')
    ->whereNull('parent_id')
    ->get();
   
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
