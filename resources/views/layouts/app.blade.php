<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location.href = "{{ route('auth.logout') }}";
                    document.getElementById('logout-form').submit();
                }
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        this.innerHTML = '<i class="fas fa-eye"></i>';
                    }
                });
            });

            const editModal = document.getElementById('editModal');
            const editForm = document.getElementById('edit-user-form');

            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    editForm.reset(); // Reset all fields inside the form

                    // Reset password fields and eye icons
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        const targetId = button.getAttribute('data-target');
                        const input = document.getElementById(targetId);

                        if (input) {
                            input.type = 'password'; // Reset to hidden
                        }

                        button.innerHTML = '<i class="fas fa-eye"></i>'; // Reset icon
                    });
                });
            }
        });
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand ms-4" href="#">Alajid</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left-aligned navigation links -->
                <ul class="navbar-nav ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-bold' : '' }}" href="{{ route('users.index') }}">Users</a>
                    </li>
                    @if(Auth::check() && Auth::user()->role === 'Admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.logs') ? 'active fw-bold' : '' }}" href="{{ route('users.logs') }}">Logs</a>
                    </li>
                    @endif
                </ul>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <div class="ms-auto me-4 d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link p-0 dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                class="rounded-circle"
                                style="width: 40px; height: 40px; object-fit: cover;"
                                alt="{{ Auth::user()->first_name }}'s avatar"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white\' style=\'width: 40px; height: 40px;\'>{{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}</div>'">
                            @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                            @endif
                            <span class="ms-2">{{ Auth::user()->first_name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.profile') }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmLogout();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>


</body>

</html>