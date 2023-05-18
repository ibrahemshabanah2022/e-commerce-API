<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{



    public function addProduct(Request $request)
    {
        $user = Auth::user();

        // Check if a cart already exists for the user
        if (!$user->cart) {
            // Create a new cart for the user
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->save();
        }
        $cartId = $user->cart->id;

        // Check if product already exists in cart
        $existingProduct = CartProduct::where('cart_id', $cartId)
            ->where('product_id', $request->input('product_id'))
            ->first();
        if ($existingProduct) {
            return response()->json([
                'message' => 'Product already exists in cart',
                'cart_product' => $existingProduct
            ]);
        }

        // Add new product to cart
        $cartProduct = new CartProduct;
        $cartProduct->cart_id = $cartId;
        $cartProduct->product_id = $request->input('product_id');
        $cartProduct->quantity = 1;
        $cartProduct->save();

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart_product' => $cartProduct
        ]);
    }
    // public function addProduct(Request $request)
    // {



    //     // $request->user()
    //     $user = Auth::user();
    //     // dd($user);
    //     // Create a new cart for the user
    //     $cart = new Cart;
    //     $cart->user_id = $user->id;
    //     $cart->save();
    //     $cartId = $user->cart->id;




    //     $cartProduct = new CartProduct;
    //     $cartProduct->cart_id = $cartId;
    //     $cartProduct->product_id = $request->input('product_id');
    //     $cartProduct->quantity = 1;
    //     $cartProduct->save();



    //     return response()->json([
    //         'message' => 'Product added to cart successfully',
    //         'cart_product' => $cartProduct
    //     ]);
    // }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
