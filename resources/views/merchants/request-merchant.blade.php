@extends('layouts.app')

@section('title', 'Category')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/v2/dataTables.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Category</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></div>
                    <div class="breadcrumb-item"><a href="#">Request Merchant</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        @if (Auth::user()->merchant != null)
                            <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center ">
                                <div class="col-md-5 p-lg-5 mx-auto my-5">
                                    @if (Auth::user()->merchant->is_active)
                                        <h1 class="display-4 font-weight-bold text-success">Success Become a Merchant</h1>
                                        <p class="lead font-weight-normal">Terima Kasih!.</p>
                                    @else
                                        <h1 class="display-4 font-weight-bold text-warning">Request has Been Send</h1>
                                        <p class="lead font-weight-normal">Harap Menunggu.</p>
                                    @endif
                                </div>
                                <div class="product-device shadow-sm d-none d-md-block"></div>
                                <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
                            </div>
                        @else
                            <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center ">
                                <div class="col-md-5 p-lg-5 mx-auto my-5">
                                    <h1 class="display-4 font-weight-bold">Merchant</h1>
                                    <p class="lead font-weight-normal">Jadilah bagian dari kami untuk memasarkan product anda.</p>
                                    <form action="{{ route('merchants.store') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            Become Merchant
                                        </button>
                                    </form>
                                </div>
                                <div class="product-device shadow-sm d-none d-md-block"></div>
                                <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
@endpush
