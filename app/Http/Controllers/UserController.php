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


        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response, 201);
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

    public function getUser()
    {
        $user = auth()->user();
        return response()->json($user);
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

    public function update(Request $request)
    {
        $customer = User::find(auth()->user()->id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json($customer);
    }

    public function update2(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Update the user attributes from the request data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        // Save the updated user to the database
        $user->update();
        // Return a JSON response with the updated user
        return response()->json($user);
    }

    // public function destroy($id)
    // {
    //     $customer = User::find($id);
    //     $customer->delete();

    //     return response()->json(null, 204);
    // }
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if ($user === null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
