@extends('dashboard_layouts.app')

{{-- @yield('title','Admin') --}}
@section('title', 'Admin')

@section('content')


    {{-- Header with Logout --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Admin Dashboard</h3>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="btn btn-outline-danger">
            Logout &rarr;
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <hr>

<div class="mb-3 d-flex align-items-center gap-3">
    <h4 class="mb-0">Company Generated URLs</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateUrlModal">
        Generate
    </button>
</div>

<!-- Generate Short URL Modal -->
<div class="modal fade" id="generateUrlModal" tabindex="-1" aria-labelledby="generateUrlModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.short-url.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="generateUrlModalLabel">Generate Short URL</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Long URL</label>
                <input type="url" name="original_url" class="form-control"
                       placeholder="https://example.com/page"
                       required>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Generate</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="table-responsive mb-4">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Short URL</th>
                <th>Original URL</th>
                <th>Hits</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companyUrls as $key => $url)
                <tr>
                    <td>{{ $companyUrls->firstItem() + $key }}</td>
                    <td><a href="{{ url($url->short_code) }}" target="_blank">{{ url($url->short_code) }}</a></td>
                    <td>{{ $url->original_url }}</td>
                    <td>{{ $url->hits ?? 0 }}</td>
                    <td>{{ $url->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No URLs found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="text-muted">
            Showing {{ $companyUrls->firstItem() }} to {{ $companyUrls->lastItem() }} of {{ $companyUrls->total() }} URLs
        </div>
        <div>
            {{ $companyUrls->appends(request()->except('urls_page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>


<hr>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Company Users</h4>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
            Invite
        </button>
    </div>

    <!-- Invite User Modal -->
    <div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.invite.user') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="inviteUserModalLabel">Invite User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select role</option>
                                <option value="ADMIN">Admin</option>
                                <option value="MEMBER">Member</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Invite</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('password'))
        <script>
            alert(
                "credentials:\n" +
                "Email: {{ session('email') }}\n" +
                "Password: {{ session('password') }}\n\n" +
                "Please copy this password now. It will not be shown again."
            );
        </script>
    @endif

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created URLs</th>
                <th>Total Hits</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companyUsers as $key => $user)
                <tr>
                    <td>{{ $companyUsers->firstItem() + $key }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst(strtolower($user->role)) }}</td>
                    <td>{{ $user->short_urls_count }}</td>
                    <td>{{ $user->total_hits ?? 0 }}</td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No users found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="text-muted">
            Showing {{ $companyUsers->firstItem() }} to {{ $companyUsers->lastItem() }} of {{ $companyUsers->total() }} users
        </div>
        <div>
            {{ $companyUsers->appends(request()->except('users_page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection