<h1>Create Product</h1>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>
    <label for="price">Price:</label>
    <input type="number" name="price" id="price" required>
    <button type="submit">Create Product</button>
</form>
