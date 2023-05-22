<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

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
        $wishlist = new Wishlist;
        $wishlist->user_id = $request->user()->id;
        $wishlist->product_id = $request->input('product_id');
        $wishlist->save();

        return response()->json($wishlist);
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::findOrFail($id);

        if ($wishlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $wishlist->delete();

        return response()->json(['success' => true]);
    }
}
