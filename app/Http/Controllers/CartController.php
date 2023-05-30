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
        $cart = $user->cart;
        if (!$cart) {
            // Create a new cart for the user
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->save();
        }

        // Check if product already exists in cart
        $existingProduct = $cart->products()->where('product_id', $request->input('product_id'))->first();
        if ($existingProduct) {
            return response()->json([
                'essage' => 'Product already exists in cart',
                'cart_product' => $existingProduct
            ]);
        }

        // Add new product to cart
        $cartProduct = new CartProduct;
        $cartProduct->cart_id = $cart->id;
        $cartProduct->product_id = $request->input('product_id');
        $cartProduct->quantity = 1;
        $cartProduct->save();

        return response()->json([
            'essage' => 'Product added to cart successfully',
            'cart_product' => $cartProduct
        ]);
    }


    // public function removeProduct(Request $request)
    // {
    //     $user = Auth::user();

    //     // Get the cart for the user
    //     $cart = $user->cart;

    //     // Get the cart product to remove
    //     $cartProduct = $cart->products()->where('id', $request->input('cart_product_id'))->first();

    //     // Remove the cart product from the cart
    //     if ($cartProduct) {
    //         $cartProduct->delete();
    //         return response()->json([
    //             'essage' => 'Product removed from cart successfully'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'essage' => 'Cart product not found'
    //         ]);
    //     }
    // }
    public function removeProduct(Request $request)
    {
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->cart;

        // Get the cart product to remove
        $cartProduct = $cart->cartProducts()->where('cart_product.product_id', $request->input('cart_product_id'))->first();

        // Remove the cart product from the cart
        if ($cartProduct) {
            $cartProduct->delete();
            return response()->json([
                'essage' => 'Product removed from cart successfully'
            ]);
        } else {
            return response()->json([
                'essage' => 'Cart product not found'
            ]);
        }
    }
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
