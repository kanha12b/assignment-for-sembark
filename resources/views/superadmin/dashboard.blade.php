@extends('dashboard_layouts.app')

@section('title', 'SuperAdmin')




@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Super Admin Dashboard</h3>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="btn btn-outline-danger">
                Logout &rarr;
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        <hr>

        {{-- ================= Companies Table ================= --}}
          {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('admin_password'))
            <script>
                alert(
                    "Admin credentials:\n" +
                    "Email: {{ session('admin_email') }}\n" +
                    "Password: {{ session('admin_password') }}\n\n" +
                    "Please copy this password now. It will not be shown again."
                );
            </script>
        @endif

        <div class="mb-4">
   <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">Companies</h4>
    <!-- Invite Button triggers modal -->
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#inviteModal">
        Invite
    </button>
</div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Total Users</th>
                            <th>Total Generated URLs</th>
                            <th>Total Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $key => $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $key }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->users_count }}</td>
                                <td>{{ $company->short_urls_count }}</td>
                                <td>{{ $company->total_hits ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No companies found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="text-muted">
                        Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} of {{ $companies->total() }}
                        companies
                    </div>
                    <div>
                        {{ $companies->appends(request()->except('companies_page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('superadmin.invite') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="inviteModalLabel">Add New Company & Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" required>
                            </div>
                            {{-- <div class="mb-3">
                                <label for="admin_name" class="form-label">Admin Name</label>
                                <input type="text" name="admin_name" id="admin_name" class="form-control" required>
                            </div> --}}
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" name="admin_email" id="admin_email" class="form-control" required>
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

        <hr>
        {{-- ================= Company Generated URLs Table ================= --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">All Generated URLs</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Short URL</th>
                            <th>Original URL</th>
                            <th>Hits</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allUrls as $key => $url)
                            <tr>
                                <td>{{ $allUrls->firstItem() + $key }}</td>
                                <td>{{ $url->company->name ?? 'N/A' }}</td>
                                <td><a href="{{ url($url->short_code) }}" target="_blank">{{ url($url->short_code) }}</a></td>
                                <td>{{ $url->original_url }}</td>
                                <td>{{ $url->hits ?? 0 }}</td>
                                <td>{{ $url->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No URLs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="text-muted">
                        Showing {{ $allUrls->firstItem() }} to {{ $allUrls->lastItem() }} of {{ $allUrls->total() }} URLs
                    </div>
                    <div>
                        {{ $allUrls->appends(request()->except('urls_page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
