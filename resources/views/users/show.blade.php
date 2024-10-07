@extends('layouts.app')

@section('title', 'Show User')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Show User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" readonly value="{{ $findUser->name }}">
                        </div>
                        <div class="form-group">
                            <label for="phonenumber">Phonenumber</label>
                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" readonly value="{{ $findUser->phonenumber }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" readonly value="{{ $findUser->email }}">
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
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
