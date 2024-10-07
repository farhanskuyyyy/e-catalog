@extends('layouts.app')

@section('title', 'Create User')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                    <div class="breadcrumb-item">Create</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name') }}">
                                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>
                            <div class="form-group">
                                <label for="phonenumber">Phonenumber</label>
                                <input type="text" class="form-control" id="phonenumber" name="phonenumber" required
                                    value="{{ old('phonenumber') }}">
                                    <x-input-error :messages="$errors->get('phonenumber')" class="mt-1" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ old('email') }}">
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div class="form-group">
                                <div class="mb-3" id="preview-image">
                                </div>
                                <x-input-label for="avatar" :value="__('Avatar')" />
                                <br>
                                <x-text-input id="avatar" class="block form-control" type="file" name="avatar" id="avatar" required
                                    autofocus autocomplete="avatar" />
                                <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
                            </div>
                            <div class="form-group">
                                <label for="roles">Roles</label>
                                @forelse ($roles as $role)
                                    <div class="flex items-center">
                                        <input id="role-checkbox" type="checkbox" value="{{ $role->name }}" name="roles[]"
                                            class="">
                                        <label for="role-checkbox" class="form-label">{{ $role->name }}</label>
                                    </div>
                                @empty
                                    <p>Role Not Found</p>
                                @endforelse
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $('#avatar').change(function() {
            var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg']; //acceptable file types

            var extension = this.files[0].name.split('.').pop().toLowerCase() //file extension from input file
            var isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

            if (isSuccess) { //yes
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image').html(
                        `<img src="${e.target.result}" alt="Avatar" style="width: 90px;height:90px">`
                    );
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#preview-image').html(this.files[0].name);
            }
        });
    </script>
@endpush
