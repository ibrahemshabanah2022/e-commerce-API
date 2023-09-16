<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get();

        return response()->json($orders);
    }
    ///////////
    public function showOrder($id)
    {
        $order = Order::with('user')->where('id', $id)->get();

        return response()->json($order);
    }

    public function show($id)
    {
        // Find the product with the given ID
        // $orderProduct = ProductOrder::where('order_id', $id)->get();
        $orderProduct = ProductOrder::with('product')->with('order')->where('order_id', $id)->get();

        // Return a JSON response with the product
        return response()->json($orderProduct);
    }
}
