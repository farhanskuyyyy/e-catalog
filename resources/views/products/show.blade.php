@extends('layouts.app')

@section('title', 'Show Product')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Show Product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></div>
                    <div class="breadcrumb-item">Show</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" readonly
                                value="{{ $findProduct->name }}">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control" readonly>
                                <option>
                                    {{ $findProduct->category->name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" readonly
                                value="{{ $findProduct->price }}">
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" readonly
                                value="{{ $findProduct->stock }}">
                        </div>
                        <div class="form-group">
                            <label for="estimated_time">Estimated Time (Minutes)</label>
                            <input type="number" class="form-control" id="estimated_time" name="estimated_time" readonly
                                value="{{ $findProduct->estimated_time }}">
                        </div>
                        <div class="mb-3" id="preview-image">
                            <img src="{{ asset('storage/products/' . $findProduct->image) }}" alt="" width="400"
                                height="400" onerror="this.src='https://placehold.co/100x100'">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control " name="description" id="description" cols="40" rows="30" style="height: 100px"
                                readonly>{{ $findProduct->description }}</textarea>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
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
