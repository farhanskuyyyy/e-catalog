@extends('layouts.app')

@section('title', 'Show User')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Show User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                    <div class="breadcrumb-item">Show</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" readonly
                                value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="phonenumber">Phonenumber</label>
                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" readonly
                                value="{{ $user->phonenumber }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" readonly
                                value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="rounded mt-2"
                                style="width: 90px;height:90px">
                            <br>
                            <x-input-label for="avatar" :value="__('Avatar')" />
                        </div>
                        <div class="form-group">
                            <label for="roles">Roles</label>
                            @forelse ($roles as $role)
                                <div class="flex items-center">
                                    <input {{ $role->users->count() > 0 ? 'checked' : '' }} id="role-checkbox" disabled
                                        type="checkbox" value="{{ $role->name }}" name="roles[]" class="">
                                    <label for="role-checkbox" class="form-label">{{ $role->name }}</label>
                                </div>
                            @empty
                                <p>Role Not Found</p>
                            @endforelse
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
