<h1>Product Details</h1>

<div>
    <strong>Name:</strong> {{ $product->name }}
</div>
<div>
    <strong>Description:</strong> {{ $product->description }}
</div>
<div>
    <strong>Price:</strong> ${{ number_format($product->price, 2) }}
</div>

<a href="{{ route('products.index') }}">Back to Product List</a>
<a href="{{ route('products.edit', $product->id) }}">Edit</a>

<form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Product</button>
</form>