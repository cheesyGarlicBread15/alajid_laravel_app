@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Product Details</h2>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $product->name }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Product List</a>
        </div>
    </div>
</div>
@endsection