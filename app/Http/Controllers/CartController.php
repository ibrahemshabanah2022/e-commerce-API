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



    public function removeProduct(Request $request)
    {
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->cart;
        // dd($cart);
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

    public function removeAllProducts(Request $request)
    {
        //remove all products for authenticated  user  from cart 
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->cart;
        $cartID =  $cart->id;
        // dd($cartID);
        // Get the cart product to remove
        $cartProduct = $cart->cartProducts()->where('cart_product.cart_id', $cartID)->get();

        // Remove the cart product from the cart
        foreach ($cartProduct as $cartProduct) {
            $cartProduct->delete();
        }
        return response()->json([
            'message' => 'All products removed from cart successfully'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->cart;

        // Get the cart product 
        $cartProductIds = $cart->cartProducts->pluck('product_id');
        $products = Product::whereIn('id', $cartProductIds)->get();
        $cartProduct = $cart->cartProducts;

        return response()->json([
            'products' =>  $products,
            'cartProduct' => $cartProduct
        ]);
    }

    public function increaseQuantity(Request $request)
    {
        $product = CartProduct::where('cart_product.product_id', $request->input('id'))->first();
        $product->quantity += 1;
        $product->save();

        return response()->json([
            'essage' => 'Quantity increased successfully',
            'quantity' => $product->quantity
        ]);
    }
    public function decreaseQuantity(Request $request)
    {
        $product = CartProduct::where('cart_product.product_id', $request->input('id'))->first();
        $product->quantity -= 1;
        $product->save();

        return response()->json([
            'essage' => 'Quantity decreased successfully',
            'quantity' => $product->quantity
        ]);
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
