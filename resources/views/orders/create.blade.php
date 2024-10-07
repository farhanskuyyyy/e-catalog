@extends('layouts.app')

@section('title', 'Create Order')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></div>
                    <div class="breadcrumb-item">Create</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="user">User</label>
                                <select name="user" id="user" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == old('user') ? 'selected' : '' }}>{{ "{$user->name} ( {$user->email} )" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="payment">Payment</label>
                                <select name="payment" id="payment" class="form-control" required>
                                    <option value="">Select Payment</option>
                                    @foreach ($payments as $payment)
                                        <option value="{{ $payment }}" {{ $payment == old('payment') ? 'selected' : '' }}>{{ $payment }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="shipping">Shipping</label>
                                <select name="shipping" id="shipping" class="form-control" required>
                                    <option value="">Select Shipping</option>
                                    @foreach ($shippings as $shipping)
                                        <option value="{{ $shipping }}" {{ $shipping == old('shipping') ? 'selected' : '' }}>{{ $shipping }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    @foreach ($status as $stat)
                                        <option value="{{ $stat }}" {{ $stat == old('stat') ? 'selected' : '' }}>{{ $stat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pickup_at">Pickup At</label>
                                <input type="text" name="pickup_at" id="pickup_at" class="form-control datetimepicker">
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <input type="text" name="note" id="note" class="form-control"
                                    value="{{ old('note') }}">
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
    <!-- JS Libraies -->
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
