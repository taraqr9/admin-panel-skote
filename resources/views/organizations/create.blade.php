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
                    [
                        'label' => 'Create',
                    ],
                ],
            ])

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-1">Create Organization</h5>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('organizations.index') }}"
                                           class="btn btn-light waves-effect">
                                            Back
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('organizations.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Name <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   placeholder="Enter organization name">

                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fiscal Year <span class="text-danger">*</span>
                                            </label>

                                            <select name="fiscal_year"
                                                    class="form-control select2 @error('fiscal_year') is-invalid @enderror">
                                                <option value="">Select Fiscal Year</option>

                                                @for($year = 2017; $year <= now()->year + 2; $year++)
                                                    @php
                                                        $fiscalYearLabel = $year . '-' . ($year + 1);
                                                    @endphp

                                                    <option value="{{ $year }}"
                                                        @selected((string) old('fiscal_year') === (string) $year)>
                                                        {{ $fiscalYearLabel }}
                                                    </option>
                                                @endfor
                                            </select>

                                            @error('fiscal_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fiscal Start Month <span class="text-danger">*</span>
                                            </label>

                                            <select name="fiscal_start_month"
                                                    class="form-control select2 @error('fiscal_start_month') is-invalid @enderror">
                                                <option value="">Select Start Month</option>

                                                @foreach(range(1, 12) as $month)
                                                    <option value="{{ $month }}"
                                                        @selected((string) old('fiscal_start_month') === (string) $month)>
                                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('fiscal_start_month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fiscal End Month <span class="text-danger">*</span>
                                            </label>

                                            <select name="fiscal_end_month"
                                                    class="form-control select2 @error('fiscal_end_month') is-invalid @enderror">
                                                <option value="">Select End Month</option>

                                                @foreach(range(1, 12) as $month)
                                                    <option value="{{ $month }}"
                                                        @selected((string) old('fiscal_end_month') === (string) $month)>
                                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('fiscal_end_month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Remarks
                                            </label>

                                            <textarea name="remarks"
                                                      id="remarks"
                                                      class="form-control @error('remarks') is-invalid @enderror"
                                                      rows="5"
                                                      placeholder="Enter remarks">{{ old('remarks') }}</textarea>

                                            @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Status</label>

                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="isActive"
                                                @checked((string) old('is_active', '1') === '1')>

                                            <label class="form-check-label" for="isActive">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-2">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('organizations.index') }}"
                                           class="btn btn-light waves-effect">
                                            Cancel
                                        </a>

                                        <button type="submit"
                                                class="btn btn-primary waves-effect waves-light">
                                            <i class="mdi mdi-content-save-outline me-1"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>

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
        });
    </script>
@endsection
