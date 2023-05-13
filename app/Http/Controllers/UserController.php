<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('web');
    // }

    function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // // Create a new cart for the user
        // $cart = new Cart();
        // $cart->user_id = $user->id;
        // $cart->save();



        // Save the cart ID in the session
        // $request->session()->put('cart_id', $cart->id);


        //check if the cart_id is stored in session or not
        // if (Session::has('cart_id')) {
        //     $message = 'The cart ID is stored in the session.';
        //     return response()->json(['message' => $message]);
        // } else {
        //     $message = 'The cart ID is not stored in the session.';
        //     return response()->json(['message' => $message]);
        // }




        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }



    public function index2()
    {
        $customers = User::all();
        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = User::find($id);
        return response()->json($customer);
    }

    public function store(Request $request)
    {
        $customer = new User;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json($customer, 201);
    }

    public function update(Request $request, $id)
    {
        $customer = User::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = User::find($id);
        $customer->delete();

        return response()->json(null, 204);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
