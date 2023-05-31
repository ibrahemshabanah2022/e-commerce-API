<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wishlists = $user->wishlists;

        return response()->json($wishlists);
    }

    public function store(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = $request->user()->id;

        // Check if product already exists in user's wishlist
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            // Product already exists in wishlist
            return response()->json(['message' => 'Product already in wishlist']);
        }

        // Product doesn't exist in wishlist, create new wishlist item
        $wishlist = new Wishlist;
        $wishlist->user_id = $userId;
        $wishlist->product_id = $productId;
        $wishlist->save();

        return response()->json($wishlist);
    }


    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->wishlists;

        // Get the cart product to remove
        $cartProduct = $cart->where('product_id', $request->input('product_id'))->first();

        // Remove the cart product from the cart
        if ($cartProduct) {
            $cartProduct->delete();
            return response()->json([
                'essage' => 'Product removed from wishlist successfully'
            ]);
        } else {
            return response()->json([
                'essage' => 'wishlist product not found'
            ]);
        }
    }
}
