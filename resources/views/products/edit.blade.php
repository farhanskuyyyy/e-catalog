@extends('layouts.app')

@section('title', 'Edit Product')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('products.update',['id' => $findProduct->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ $findProduct->name }}">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $findProduct->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required
                                    value="{{ $findProduct->price }}">
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required
                                    value="{{ $findProduct->stock }}">
                            </div>
                            <div class="form-group">
                                <label for="estimated_time">Estimated Time (Minutes)</label>
                                <input type="number" class="form-control" id="estimated_time" name="estimated_time"
                                    required value="{{ $findProduct->estimated_time }}">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                    value="{{ $findProduct->image }}">
                            </div>
                            <div class="mb-3" id="preview-image">
                                <img src="{{ asset('storage/products/'.$findProduct->image) }}" alt="" width="400" height="400" onerror="this.src='https://placehold.co/100x100'">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control " name="description" id="description" cols="40" rows="30" style="height: 100px">{{ $findProduct->description }}</textarea>
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
    $('#image').change(function() {
        var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg']; //acceptable file types

        var extension = this.files[0].name.split('.').pop().toLowerCase() //file extension from input file
        var isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

        if (isSuccess) { //yes
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').html(
                    `<img src="${e.target.result}" alt="Avatar" width="400" height="400">`
                );
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#preview-image').html(this.files[0].name);
        }
    });
</script>
@endpush
