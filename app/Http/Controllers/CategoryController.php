<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all category from the database
        $categories = Category::all();

        $productCounts = [];

        foreach ($categories as $category) {
            $count = Product::where('category_id', $category->id)->count();
            $productCounts[$category->id] = $count;
        }

        return response()->json([
            'status' => 'success',
            'productCounts' => $productCounts,
            'categories' => $categories
        ]);
        // Return a JSON response with the category
        // return response()->json($category);
    }

    public function index2()
    {
        // Get all category from the database
        $categories = Category::all();

        $productCounts = [];

        foreach ($categories as $category) {
            $count = Product::where('category_id', $category->id)->count();
            $productCounts[$category->id] = $count;
        }

        return response()->json([
            'status' => 'success',
            'productCounts' => $productCounts,
            'categories' => $categories
        ]);
        // Return a JSON response with the category
        // return response()->json($category);
    }

    public function getProductCount(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $count = Product::where('category_id', $category->id)->count();

        return response()->json([
            'status' => 'success',
            'count' => $count,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            // 'image' => 'required|image',
        ]);

        $category = new Category;
        $category->name = $validatedData['name'];


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return response()->json(['message' => 'Category created successfully', 'product' => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the product with the given ID
        $product = Category::findOrFail($id);
        // Return a JSON response with the product
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->name = $request->input('name');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'category deleted successfully'], 200);
    }
}
