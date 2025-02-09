@extends('layouts.app')
@section('content')
<!-- Search Form -->
<form action="{{ route('users.index') }}" method="GET" class="mt-3">
  <div class="input-group mb-3">
    <input
      type="text"
      name="search"
      class="form-control"
      placeholder="Search users..."
      value="{{ $search }}">
    <button class="btn btn-primary" type="submit">Search</button>
  </div>
</form>

<!-- User List -->
<h1 class="mb-3">Users</h1>
<ul>
  @foreach($users as $user)
  <!-- Add margin-bottom to each list item except the last one -->
  <li class="{{ $loop->last ? '' : 'mb-3' }}">
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <a href="{{ route('users.show', $user->id) }}">{{ $user->first_name . ' ' . $user->last_name}}</a>
        </div>
        <div class="col-sm text-end">
          <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-warning me-3" data-bs-toggle="modal" data-bs-target="#editModal" data-id="
          {{ $user->id }}"
            data-first_name="{{ $user->first_name }}"
            data-last_name="{{ $user->last_name }}"
            data-email="{{ $user->email }}">Edit</a>
          <button class="btn btn-sm btn-danger" onclick="confirmUserDelete('{{ $user->id }}')">Delete</button>
          <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
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
  {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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
  function confirmUserDelete(userId) {
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
        document.getElementById(`delete-form-${userId}`).submit();
      }
    });
  }

  // When the Edit button is clicked, populate the modal with the corresponding user data
  const editButtons = document.querySelectorAll('.btn-warning'); // All edit buttons
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const userId = this.getAttribute('data-id');
      const userFirstName = this.getAttribute('data-first_name');
      const userLastName = this.getAttribute('data-last_name');
      const userEmail = this.getAttribute('data-email');

      // Set the form action dynamically for the correct user update route
      const form = document.getElementById('edit-user-form');
      form.action = '/users/' + userId; // Adjust the URL to the correct user

      // Populate the modal fields with the data from the edit button
      document.getElementById('userFirstName').value = userFirstName;
      document.getElementById('userLastName').value = userLastName;
      document.getElementById('userEmail').value = userEmail;
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordFields = document.getElementById('togglePasswordFields');
    const passwordFields = document.getElementById('passwordFields');

    togglePasswordFields.addEventListener('change', function() {
      if (this.checked) {
        passwordFields.style.display = 'block';
      } else {
        passwordFields.style.display = 'none';
      }
    });
  });
</script>
@endsection

<!-- Bootstrap Modal for Editing User -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-user-form" method="POST" action="{{ route('users.update', 0) }}">
          @csrf
          @method('PUT')

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <div class="mb-3">
            <label for="userFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="userFirstName" name="firstName" required>
          </div>
          <div class="mb-3">
            <label for="userLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="userLastName" name="lastName" required>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="userEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">
              <input type="checkbox" id="togglePasswordFields"> Reset Password
            </label>
          </div>

          <!-- Password Fields (Initially Hidden) -->
          <div id="passwordFields" style="display: none;">
            <div class="mb-3">
              <label for="userNewPassword" class="form-label">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="userNewPassword" name="newPassword">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="userNewPassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label for="userConfirmPassword" class="form-label">Confirm New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="userConfirmPassword" name="newPassword_confirmation">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="userConfirmPassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
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