@extends('layouts.app')
@section('content')
<style>
  /* Pagination container */
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
    flex-wrap: wrap;
  }

  /* Pagination links */
  .pagination a,
  .pagination span {
    display: inline-block;
    padding: 8px 16px;
    margin: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 50px;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
  }

  /* Pagination hover state */
  .pagination a:hover {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
  }

  /* Active page link */
  .pagination .active span {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
  }

  /* Disabled page link */
  .pagination .disabled span {
    background-color: #f8f9fa;
    color: #6c757d;
    border-color: #ddd;
    cursor: not-allowed;
  }

  /* First and last page links */
  .pagination .first,
  .pagination .last {
    background-color: #f8f9fa;
    color: #007bff;
    font-weight: bold;
    border-radius: 50px;
  }

  /* Add arrows to first and last links */
  .pagination .first::before {
    content: '<<';
    margin-right: 8px;
  }

  .pagination .last::after {
    content: '>>';
    margin-left: 8px;
  }

  /* Pagination on mobile devices */
  @media (max-width: 576px) {
    .pagination {
      gap: 8px;
    }

    .pagination a,
    .pagination span {
      padding: 6px 12px;
      font-size: 14px;
    }

    /* Reduce size of first and last page buttons on small screens */
    .pagination .first::before,
    .pagination .last::after {
      font-size: 12px;
    }
  }
</style>
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
          <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning me-3" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}">Edit</a>
          <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $product->id }}')">Delete</button>
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
  function confirmDelete(productId) {
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
            <input type="number" class="form-control" id="productPrice" name="price" required>
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
            <input type="number" class="form-control" id="newProductPrice" name="price" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
      </div>
    </div>
  </div>
</div>