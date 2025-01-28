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
        value="{{ $search }}"
        >
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
    </form>

    <!-- Product List -->
    <h1>Product List</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Create New Product</a>
    <form action="{{ route('products.index') }}" method="GET" class="mt-3">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
    <ul>
        @foreach($products as $product)
            <li>
                <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a> |
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}">Edit</a> |
                <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $product->id }}')">Delete</button>
                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </li>
        @endforeach
    </ul>

    <!-- Pagination Links -->
    <div class="mt-3">
        {{ $products->appends(['search' => request('search')])->links() }}
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
            button.addEventListener('click', function () {
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
