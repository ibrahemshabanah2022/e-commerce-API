<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all products from the database
        $products = Product::all();
        // Return a JSON response with the products
        return response()->json($products);
    }

    public function AdminIndex()
    {


        $products = Product::with('category')->get();


        // Return a JSON response with the products
        return response()->json(
            // 'ProductCategory' => $CategoryProduct,
            $products
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the product with the given ID
        $product = Product::findOrFail($id);
        // Return a JSON response with the product
        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image',
        ]);

        $product = new Product;
        $product->title = $validatedData['title'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->category_id = $validatedData['category_id'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }
    // public function store(Request $request)
    // {
    //     // Check if the request method is POST
    //     if ($request->method() !== 'POST') {
    //         return response()->json(['error' => 'Invalid request method'], 405);
    //     }

    //     // Create a new product instance
    //     $product = new Product();
    //     // Set the product attributes from the request data
    //     $product->title = $request->input('title');
    //     $product->price = $request->input('price');
    //     $product->description = $request->input('description');
    //     $product->category_id = $request->input('category_id');
    //     // Save the image to the storage directory
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $path = 'public/images/' . $image->getClientOriginalName();
    //         $image->move(public_path('images'), $path);
    //         $product->image = $path;
    //     }
    //     // Save the product to the database
    //     $product->save();
    //     // Return a JSON response with the saved product
    //     return response()->json($product);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the product with the given ID
        $product = Product::findOrFail($id);
        // Update the product attributes from the request data
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        // Save the updated product to the database
        $product->save();
        // Return a JSON response with the updated product
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
