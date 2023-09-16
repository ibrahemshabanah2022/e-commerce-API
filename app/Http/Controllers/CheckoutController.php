<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Stripe\StripeClient;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        // dd($lineItems);
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $user_id = $user->id;

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.cancel', [], true),
            // 'success_url' => 'http://localhost:5173/PaymentSuccessPage?session_id={CHECKOUT_SESSION_ID}',
            // 'cancel_url' => 'http://example.com',
            'payment_method_types' => ['card'],
            'submit_type' => 'pay',
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $totalprice;
        $order->user_id =  $user_id;
        $order->session_id =   $checkout_session->id;
        $order->save();
        // Save the products that the user ordered
        foreach ($cartProducts as $cartProduct) {
            DB::table('product_order')->insert([
                'order_id' => $order->id,
                'ProductName' => $cartProduct->product->title,
                'ProductPrice' => $cartProduct->product->price,

                'quantity' => $cartProduct->quantity,
            ]);
        }
        return response()->json([
            'url' =>  $checkout_session->url
        ]);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');


        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        if (!$session) {
            throw new NotFoundHttpException;
        }
        // $customer = \Stripe\Customer::retrieve($session->customer);

        $order = Order::where('session_id', $session->id)->first();
        if (!$order) {
            throw new NotFoundHttpException();
        }
        if ($order->status === 'unpaid') {
            $order->status = 'paid';
            $order->save();
        }

        $user_id = $order->user_id;
        $cart = Cart::where('user_id', $user_id)->first();


        $cartId = $cart->id;
        // dd($cartId);
        $cart->cartproducts()->where('cart_id', $cartId)->delete();




        return view('product.checkout-success');
    }
}
