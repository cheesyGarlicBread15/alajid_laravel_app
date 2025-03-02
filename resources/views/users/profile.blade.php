@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Personal Profile</h4>
                    <button type="button" class="btn btn-light btn-sm" onclick="toggleEdit()" id="editButton">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- for usercontroller update -->
                        <input type="hidden" name="isProfile" value="1">

                        <!-- Profile Picture -->
                        <div class="text-center mb-4">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                class="rounded-circle mb-3"
                                style="width: 150px; height: 150px; object-fit: cover;"
                                alt="{{ Auth::user()->first_name }}'s avatar"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white\' style=\'width: 150px; height: 150px; font-size: 3rem;\'>{{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}</div>'">
                            @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                                style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                            @endif

                            <!-- Role Badge -->
                            <div class="text-center mb-4">
                                <span class="badge {{ Auth::user()->role === 'Admin' ? 'bg-danger' : 'bg-primary' }} fs-6">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            <div class="mb-3" id="avatarUpload" style="display: none;">
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" disabled>
                                <small class="text-muted">Upload a new profile picture (optional)</small>
                            </div>
                        </div>

                        @if(session('success'))
                        <p class="alert alert-success">{{ session('success') }}</p>
                        @endif

                        @if(session('error'))
                        <p class="alert alert-danger">{{ session('error') }}</p>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                value="{{ Auth::user()->first_name }}" readonly>
                            @error('firstName')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName"
                                value="{{ Auth::user()->last_name }}" readonly>
                            @error('lastName')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ Auth::user()->email }}" readonly>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="mb-4" id="passwordSection" style="display: none;">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="changePassword" onchange="togglePasswordFields()">
                                <label class="form-check-label" for="changePassword">
                                    Change Password
                                </label>
                            </div>

                            <div id="passwordFields" style="display: none;">
                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword"
                                            disabled data-required-if-enabled="true">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="oldPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('oldPassword')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="newPassword" name="newPassword"
                                            disabled data-required-if-enabled="true">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('newPassword')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm New Password -->
                                <div class="mb-3">
                                    <label for="newPassword_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="newPassword_confirmation"
                                            name="newPassword_confirmation" disabled data-required-if-enabled="true">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newPassword_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end" id="saveButton" style="display: none;">
                            <button type="button" class="btn btn-secondary me-2" onclick="cancelEdit()">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function togglePasswordFields() {
            const passwordFields = document.getElementById('passwordFields');
            const inputs = passwordFields.getElementsByTagName('input');
            const isChecked = document.getElementById('changePassword').checked;

            passwordFields.style.display = isChecked ? 'block' : 'none';

            // Enable/disable password inputs
            for (let input of inputs) {
                if (input.type === 'password') {
                    input.disabled = !isChecked;
                    input.required = isChecked;
                    if (!isChecked) {
                        input.value = ''; // Clear passwords when disabled
                    }
                }
            }
        }

        function toggleEdit() {
            const form = document.getElementById('profileForm');
            const inputs = form.getElementsByTagName('input');
            const saveButton = document.getElementById('saveButton');
            const editButton = document.getElementById('editButton');
            const avatarUpload = document.getElementById('avatarUpload');
            const passwordSection = document.getElementById('passwordSection');

            // Enable all inputs except file input
            for (let input of inputs) {
                if (input.type !== 'hidden') {
                    if (input.type === 'file') {
                        input.disabled = false;
                    } else if (input.type === 'email') {
                        input.readOnly = true;
                    } else {
                        input.readOnly = false;
                    }
                }
            }

            saveButton.style.display = 'block';
            editButton.style.display = 'none';
            avatarUpload.style.display = 'block';
            passwordSection.style.display = 'block';
        }

        function cancelEdit() {
            const form = document.getElementById('profileForm');
            const inputs = form.getElementsByTagName('input');
            const saveButton = document.getElementById('saveButton');
            const editButton = document.getElementById('editButton');
            const avatarUpload = document.getElementById('avatarUpload');
            const passwordSection = document.getElementById('passwordSection');
            const passwordFields = document.getElementById('passwordFields');
            const changePasswordCheckbox = document.getElementById('changePassword');

            // Disable all inputs
            for (let input of inputs) {
                if (input.type !== 'hidden') {
                    if (input.type === 'file') {
                        input.disabled = true;
                    } else {
                        input.readOnly = true;
                    }
                }
            }

            changePasswordCheckbox.checked = false;
            passwordSection.style.display = 'none';
            passwordFields.style.display = 'none';
            saveButton.style.display = 'none';
            editButton.style.display = 'block';
            avatarUpload.style.display = 'none'; // Hide file upload
            form.reset();
        }
        // Make functions globally accessible
        window.toggleEdit = toggleEdit;
        window.cancelEdit = cancelEdit;
        window.togglePasswordFields = togglePasswordFields;
    });
</script>
@endsection