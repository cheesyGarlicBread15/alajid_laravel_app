<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show a list of all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
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
        return redirect()->route('products.index');
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
        $request->validate([
        'name' => 'required|max:255',
        'description' => 'required',
        'price' => 'required|numeric',
        ]);
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('products.index');
    }
    // Delete a product
        public function destroy($id)
        {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index');
    }
}
