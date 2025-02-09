<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show a list of all products
    public function index(Request $request)
    {
        // Retrieve the search query from the request
        $search = $request->get('search');

        // Query the products, applying the search filter if provided
        $products = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })
            ->paginate(10); // Paginate with 10 products per page

        // Return the view with products and the search query
        return view('products.index', compact('products', 'search'));
    }
    // Show a single product's details
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    // Show the form for creating a new product
    public function create()
    {
        return view('products.create');
    }
    // Store a newly created product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }
    // Show the form for editing a product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }
    // Update a product in the database
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);
        // Update product details
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->save();
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
