<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Stripe\Stripe;
use App\Models\Product;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// require 'vendor/autoload.php';


class CheckoutController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();

        // Get the cart for the user
        $cart = $user->cart;

        // Get the cart product 
        $cartProducts = $cart->cartProducts()->get();
        // dd($cartProducts);
        // $products = Product::whereIn('id', $cartProducts->pluck('product_id'))->get();

        $totalprice = 0;
        $lineItems = [];
        foreach ($cartProducts as $cartProduct) {
            $totalprice += $cartProduct->quantity * $cartProduct->product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $cartProduct->product->title,
                    ],
                    'unit_amount' =>  intval($cartProduct->product->price),
                ],
                'quantity' => $cartProduct->quantity,
            ];
        }

        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $user_id = $user->id;

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => 'http://example.com',
            'cancel_url' => 'http://example.com',
            'payment_method_types' => ['card'],
            'submit_type' => 'pay',
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $totalprice;
        $order->user_id =  $user_id;
        $order->session_id =   $checkout_session->id;
        $order->save();

        return response()->json([
            'url' =>  $checkout_session->url
        ]);
    }
}
