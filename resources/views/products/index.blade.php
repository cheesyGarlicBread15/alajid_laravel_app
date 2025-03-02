@extends('layouts.app')
@section('content')
<!-- Search Form -->
<form action="{{ route('products.index') }}" method="GET" class="mt-3">
  <div class="input-group mb-3">
    <input
      type="text"
      name="search"
      class="form-control"
      placeholder="Search products..."
      value="{{ $search }}">
    <button class="btn btn-primary" type="submit">Search</button>
  </div>
</form>

<!-- Product List -->
<h1>Product List</h1>
<a href="{{ route('products.create') }}" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Create New Product</a>
<!-- <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create New Product</a> -->
<ul>
  @foreach($products as $product)
  <!-- Add margin-bottom to each list item except the last one -->
  <li class="{{ $loop->last ? '' : 'mb-3' }}">
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
        </div>
        <div class="col-sm text-end">
          <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning me-3" data-bs-toggle="modal" data-bs-target="#editModal" data-id="
          {{ $product->id }}"
            data-name="{{ $product->name }}"
            data-description="{{ $product->description }}"
            data-price="{{ $product->price }}">
            Edit</a>
          <button class="btn btn-sm btn-danger" onclick="confirmProductDelete('{{ $product->id }}')">Delete</button>
          <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
          </form>
        </div>
      </div>
    </div>
  </li>
  @endforeach
</ul>

<!-- Pagination Links -->
<div class="mt-3">
  {{ $products->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
</div>

<!-- Success Message -->
@if(session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: "{{ session('success') }}"
  });
</script>
@endif


<!-- JS -->
<script>
  function confirmProductDelete(productId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${productId}`).submit();
      }
    });
  }

  // When the Edit button is clicked, populate the modal with the corresponding product data
  const editButtons = document.querySelectorAll('.btn-warning'); // All edit buttons
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const productId = this.getAttribute('data-id');
      const productName = this.getAttribute('data-name');
      const productDescription = this.getAttribute('data-description');
      const productPrice = this.getAttribute('data-price');

      // Set the form action dynamically for the correct product update route
      const form = document.getElementById('edit-product-form');
      form.action = '/products/' + productId; // Adjust the URL to the correct product

      // Populate the modal fields with the data from the edit button
      document.getElementById('productName').value = productName;
      document.getElementById('productDescription').value = productDescription;
      document.getElementById('productPrice').value = productPrice;
    });
  });
</script>
@endsection

<!-- Bootstrap Modal for Editing Product -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-product-form" method="POST" action="{{ route('products.update', 0) }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="productDescription" class="form-label">Description</label>
            <textarea class="form-control" id="productDescription" name="description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="productPrice" name="price" max="999999" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Modal for Creating New Product -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Create New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="create-product-form" method="POST" action="{{ route('products.store') }}">
          @csrf
          <div class="mb-3">
            <label for="newProductName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="newProductName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="newProductDescription" class="form-label">Description</label>
            <textarea class="form-control" id="newProductDescription" name="description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="newProductPrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="newProductPrice" name="price" max ="999999" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
      </div>
    </div>
  </div>
</div>