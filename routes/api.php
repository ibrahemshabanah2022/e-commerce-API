<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Api\ProductController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    //All secure URL's
    Route::post('/cart/addproduct', [CartController::class, 'addProduct']);
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
// ******************************************************************
Route::get('/category', [CategoryController::class, 'index']);

// ******************************************************


Route::get('/getcustomers', [UserController::class, 'index2']);
Route::get('/showcustomer/{customer}', [UserController::class, 'show']);
Route::post('/signup', [UserController::class, 'store']);
Route::put('/updatecustomer/{customer}', [UserController::class, 'update']);
Route::delete('/deletecustomer/{customer}', [UserController::class, 'destroy']);

//********************************************************
// Route::middleware('session')->group(function () {
//     // Your protected routes here
// });
Route::post('/login', [UserController::class, 'login']);


Route::post('/logout', [UserController::class, 'logout']);


Route::post('/checkout', [CheckoutController::class, 'checkout']);