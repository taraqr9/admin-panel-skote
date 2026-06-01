@extends('layout.master', ['page_title' => $page_title])

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            @include('partials.breadcrumb', [
                'items' => [
                    [
                        'label' => 'Settings',
                    ],
                    [
                        'label' => 'Organization',
                    ],
                ],
            ])

            {{-- FILTER SECTION --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-1">Organization Filters</h5>
                                </div>

                                @can('organization-create')
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('organizations.create') }}"
                                               class="btn btn-success waves-effect waves-light">
                                                <i class="mdi mdi-plus me-1"></i>
                                                Add Organization
                                            </a>
                                        </div>
                                    </div>
                                @endcan
                            </div>

                            <form action="{{ route('organizations.index') }}" method="GET">
                                <div class="row g-2 align-items-end">

                                    <div class="col-md-2">
                                        <label class="form-label">Fiscal Year</label>
                                        <select name="fiscal_year" class="form-control select2">
                                            <option value="">All Fiscal Years</option>

                                            @for($year = 2017; $year <= now()->year + 2; $year++)
                                                @php
                                                    $fiscalYearLabel = $year . '-' . ($year + 1);
                                                @endphp

                                                <option value="{{ $year }}"
                                                    @selected((string) request('fiscal_year') === (string) $year)>
                                                    {{ $fiscalYearLabel }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Start Month</label>
                                        <select name="fiscal_start_month" class="form-control select2">
                                            <option value="">All Start Months</option>

                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ $month }}"
                                                    @selected((string) request('fiscal_start_month') === (string) $month)>
                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">End Month</label>
                                        <select name="fiscal_end_month" class="form-control select2">
                                            <option value="">All End Months</option>

                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ $month }}"
                                                    @selected((string) request('fiscal_end_month') === (string) $month)>
                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Search</label>
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="text"
                                                       name="keyword"
                                                       value="{{ request('keyword') }}"
                                                       class="form-control"
                                                       id="searchTableList"
                                                       placeholder="Search by organization name...">

                                                <i class="bx bx-search-alt search-icon"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('organizations.index') }}"
                                       class="btn btn-light waves-effect">
                                        Reset
                                    </a>

                                    <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-magnify me-1"></i>
                                        Search
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table align-middle table-hover w-100 mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Name</th>
                                        <th>Fiscal Year</th>
                                        <th>Fiscal Start Month</th>
                                        <th>Fiscal End Month</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th class="text-center" style="width: 160px;">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse($organizations as $organization)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration + ($organizations->currentPage() - 1) * $organizations->perPage() }}
                                            </td>

                                            <td>
                                                <strong>{{ $organization->name }}</strong>
                                            </td>

                                            <td>
                                                @if($organization->fiscal_year)
                                                    {{ $organization->fiscal_year }}-{{ $organization->fiscal_year + 1 }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($organization->fiscal_start_month)
                                                    {{ \Carbon\Carbon::create()->month($organization->fiscal_start_month)->format('F') }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($organization->fiscal_end_month)
                                                    {{ \Carbon\Carbon::create()->month($organization->fiscal_end_month)->format('F') }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge {{ $organization->is_active->badgeClass() }}">
                                                    {{ $organization->is_active->label() }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $organization->created_at ? $organization->created_at->timezone('Asia/Dhaka')->format('d M Y h:i A') : 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                @can('organization-edit')
                                                    <a href="{{ route('organizations.edit', $organization->id) }}"
                                                       class="btn btn-sm btn-warning waves-effect waves-light">
                                                        <i class="mdi mdi-pencil"></i>
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('organization-delete')
                                                    <form
                                                        action="{{ route('organizations.destroy', $organization->id) }}"
                                                        method="POST"
                                                        class="d-inline delete-organization-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                                class="btn btn-sm btn-danger waves-effect waves-light delete-organization-btn">
                                                            <i class="mdi mdi-trash-can-outline"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                No organization found.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                {{ $organizations->links('partials.pagination') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('JScript')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                allowClear: true
            });

            $('#searchTableList').on('keypress', function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });

            $('.delete-organization-btn').on('click', function () {
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This organization will be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f46a6a',
                    cancelButtonColor: '#74788d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
