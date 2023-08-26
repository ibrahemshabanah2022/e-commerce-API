<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CommentController;
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
//put secure url's here that user should be authenticated to use
Route::group(['middleware' => 'auth:sanctum'], function () {
    //All secure URL's
    Route::post('/cart/addproduct', [CartController::class, 'addProduct']);
    Route::middleware('cors')->group(function () {

        Route::delete('/cart/deleteproduct', [CartController::class, 'removeProduct']);
    });
    Route::delete('/destroy', [WishlistController::class, 'destroy']);


    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    //get authenticated user data
    Route::get('/authuser', [UserController::class, 'getUser']);
    //update authenticated user data from progile
    Route::put('/updatecustomer', [UserController::class, 'update']);
    Route::get('/cartProducts', [CartController::class, 'index']);

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful'])
            ->header('Access-Control-Allow-Origin', 'http://127.0.0.1:5173');
    });
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::delete('/deleteProducts', [CartController::class, 'removeAllProducts']);
    Route::post('/success', [CheckoutController::class, 'success']);
    //enable user to write comment for product
    Route::post('/PostComment', [CommentController::class, 'store']);
});
//end secure url's


//get products by there category
Route::get('/getProductByCategory', [ProductController::class, 'getProductByCategory']);
//filter products by price
Route::get('/filterProductsBYprice', [ProductController::class, 'filterProductsBYprice']);

Route::get('/getCategory', [ProductController::class, 'getCategory']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);
// ******************************************************************
Route::get('/category', [CategoryController::class, 'index']);

// ******************************************************



Route::get('/getcustomers', [UserController::class, 'index2']);
Route::get('/showcustomer/{customer}', [UserController::class, 'show']);
Route::post('/signup', [UserController::class, 'store']);
Route::delete('/deletecustomer/{id}', [UserController::class, 'destroy']);
Route::post('/cart/increase-quantity', [CartController::class, 'increaseQuantity']);
Route::post('/cart/decrease-quantity', [CartController::class, 'decreaseQuantity']);

Route::get('/getComments', [CommentController::class, 'index']);


//********************************************************
// Route::middleware('session')->group(function () {
//     // Your protected routes here
// });
Route::post('/login', [UserController::class, 'login']);


Route::post('/logout', [UserController::class, 'logout']);



/////////////////to make dashbord accsseable for only admin use : ->middleware('auth:sanctum', 'admin') ///////////////////
Route::get('/adminproducts', [ProductController::class, 'AdminIndex'])->middleware('auth:sanctum', 'admin');
Route::post('/products', [ProductController::class, 'store'])->middleware('auth:sanctum', 'admin');
Route::post('/category', [CategoryController::class, 'store'])->middleware('auth:sanctum', 'admin');
Route::get('/categories/{id}/product-count', [CategoryController::class, 'getProductCount'])->middleware('auth:sanctum', 'admin');
Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->middleware('auth:sanctum', 'admin');
Route::post('/category/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum', 'admin');
Route::get('/category/{id}', [CategoryController::class, 'show'])->middleware('auth:sanctum', 'admin');
Route::put('/updateUser/{id}', [UserController::class, 'update2'])->middleware('auth:sanctum', 'admin');
Route::get('/showUser/{id}', [UserController::class, 'show'])->middleware('auth:sanctum', 'admin');
Route::post('/products/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum', 'admin');
Route::get('/categoryAdmin', [CategoryController::class, 'index2'])->middleware('auth:sanctum', 'admin');
