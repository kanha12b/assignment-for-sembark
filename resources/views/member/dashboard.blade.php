@extends('dashboard_layouts.app')

{{-- @yield('title','Admin') --}}
@section('title', 'Member')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Member Dashboard</h3>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="btn btn-outline-danger">
            Logout &rarr;
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <hr>
<div class="container py-4">
    <div class="mb-3 d-flex align-items-center gap-3">
        <h4 class="mb-0">My Generated URLs</h4>
        <!-- Generate Short URL button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateUrlModal">
            Generate
        </button>
    </div>

    {{-- Table of URLs --}}
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
        @forelse($userUrls as $key=>$url)
            <tr>
                 <td>{{ $userUrls->firstItem() + $key }}</td>
                <td>
                    <a href="{{ url($url->short_code) }}" target="_blank">
                        {{ url($url->short_code) }}
                    </a>
                </td>
                <td>{{ $url->original_url }}</td>
                <td>{{ $url->hits ?? 0 }}</td>
                <td>{{ $url->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No URLs created yet</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center mt-3">
    {{-- Left: Showing X to Y of Z --}}
    <div class="text-muted">
        Showing {{ $userUrls->firstItem() }} to {{ $userUrls->lastItem() }} of {{ $userUrls->total() }} URLs
    </div>

    {{-- Right: Pagination buttons --}}
    <div>
        {{ $userUrls->links('pagination::bootstrap-4') }}
    </div>
</div>
</div>

{{-- @include('member.partials.generate_url_modal') --}}

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

@endsection
