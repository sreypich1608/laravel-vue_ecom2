<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('app/')->middleware('admin')->group(function(){
    Route::post('add_category',[AdminController::class,'add_category']);
    Route::post('edit_category',[AdminController::class,'edit_category']);
    Route::post('delete_category',[AdminController::class,'delete_category']);
    // Route::get('get_category',[AdminController::class,'get_category']);
    // product
    Route::get('get_product',[AdminController::class,'get_product']);
    // product image
    Route::post('upload',[AdminController::class,'upload']);
    Route::post('delete_image',[AdminController::class,'delete_image']);
    Route::post('add_product',[AdminController::class,'add_product']);
    Route::post('edit_product',[AdminController::class,'edit_product']);
    Route::post('delete_product',[AdminController::class,'delete_product']);
    // admin user
    Route::post('add_user',[AdminController::class,'add_user']);
    Route::post('edit_user',[AdminController::class,'edit_user']);
    Route::get('get_user',[AdminController::class,'get_user']);
    Route::post('delete_user',[AdminController::class,'delete_user']);
    // get all customer
    Route::get('get_customer',[AdminController::class,'get_customer']);
    Route::post('delete_customer',[AdminController::class,'delete_customer']);
    // order
    Route::get('get_order',[AdminController::class,'get_order']);
    Route::post('get_order_details',[AdminController::class,'get_order_details']);
    Route::post('edit_order',[AdminController::class,'edit_order']);
    // dahboard
    Route::get('dashboard',[AdminController::class,'dashboard']);
    // admin logout
    Route::post('admin_logout',[LoginController::class,'app_admin_logout']);
});
// login
Route::post('app/admin_login',[LoginController::class,'admin_login']);
Route::post('/customer_register',[LoginController::class,'customer_register']);
Route::post('customer_login',[LoginController::class,'customer_login'])->name('customer_login');

Route::get('app/get_category',[AdminController::class,'get_category']);

// customer prefix
Route::prefix('user/')->middleware('customer')->group(function(){
    Route::post('customer_logout',[LoginController::class,'customer_logout']);
    Route::post('add_to_cart',[CustomerController::class,'add_to_cart']);
    Route::get('get_cart_item',[CustomerController::class,'get_cart_item']);
    Route::post('update_cart_item',[CustomerController::class,'update_cart_item']);
    Route::post('remove_cart_item',[CustomerController::class,'remove_cart_item']);
    Route::get('customer_account',[CustomerController::class,'customer_account']);
    Route::get('get_order',[CustomerController::class,'get_order']);
    Route::post('customer_update_acc',[CustomerController::class,'customer_update_acc']);
    Route::post('place_order',[CustomerController::class,'place_order']);
    Route::post('get_order_details',[CustomerController::class,'get_order_details']);
});
// customer
Route::get('customer_home',[CustomerController::class,'customer_home']);
Route::post('quick_view',[CustomerController::class,'quick_view']);



// Vue SPA Route
Route::get('/{any}', function () {
    return view('admin');
})->where('any', '.*');


// Route::get('/', function () {
//     return view('admin');
// });

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
