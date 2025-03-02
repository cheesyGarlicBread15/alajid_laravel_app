@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">User Activity Logs</h1>

    @if (session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="px-4 py-3">User ID</th>
                            <th scope="col" class="px-4 py-3">User</th>
                            <th scope="col" class="px-4 py-3">Role</th>
                            <th scope="col" class="px-4 py-3">Action</th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            <th scope="col" class="px-4 py-3">IP Address</th>
                            <th scope="col" class="px-4 py-3">Platform</th>
                            <th scope="col" class="px-4 py-3">Device Type</th>
                            <th scope="col" class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                        <tr>
                            <td class="px-4">{{ $log->user_id }}</td>
                            <td class="px-4">{{ $log->user->first_name . ' ' . $log->user->last_name }}</td>
                            <td class="px-4">{{ $log->user->role }}</td>
                            <td class="px-4">
                                <span class="badge bg-{{ $log->action === 'Login' ? 'success' : ($log->action === 'Logout' ? 'danger' : 'primary') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4">{{ $log->description }}</td>
                            <td class="px-4">{{ $log->ip }}</td>
                            <td class="px-4">{{ $log->platform }}</td>
                            <td class="px-4">{{ $log->device_type }}</td>
                            <td class="px-4 text-muted">
                                <small>{{ $log->created_at->format('l, F j, Y, g:i A') }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

<style>
    .table {
        font-size: 0.95rem;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 1em;
    }
</style>