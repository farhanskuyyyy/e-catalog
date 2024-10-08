@extends('layouts.app')

@section('title', 'Show Permission')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Show Permission</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></div>
                    <div class="breadcrumb-item">Show</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $permission->name }}" required readonly>
                        </div>
                        <a href="{{ route('permissions.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
