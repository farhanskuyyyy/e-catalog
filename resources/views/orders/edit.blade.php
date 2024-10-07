@extends('layouts.app')

@section('title', 'Edit Order')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('orders.update', ['order' => $order]) }}">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="order_code">Order Code</label>
                                <input type="text" name="order_code" id="order_code" class="form-control" readonly
                                    value="{{ $order->order_code }}">
                            </div>
                            <div class="form-group">
                                <label for="user">User</label>
                                <select name="user" id="user" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == $order->user->id ? 'selected' : '' }}>
                                            {{ "{$user->name} ( {$user->phonenumber} )" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="payment">Payment</label>
                                <select name="payment" id="payment" class="form-control" required>
                                    <option value="">Select Payment</option>
                                    @foreach ($payments as $payment)
                                        <option value="{{ $payment }}"
                                            {{ $payment == $order->payment ? 'selected' : '' }}>{{ $payment }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="shipping">Shipping</label>
                                <select name="shipping" id="shipping" class="form-control" required>
                                    <option value="">Select Shipping</option>
                                    @foreach ($shippings as $shipping)
                                        <option value="{{ $shipping }}"
                                            {{ $shipping == $order->shipping ? 'selected' : '' }}>{{ $shipping }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    @foreach ($status as $stat)
                                        <option value="{{ $stat }}"
                                            {{ $stat == $order->status ? 'selected' : '' }}>
                                            {{ $stat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pickup_at">Pickup At</label>
                                <input type="text" name="pickup_at" id="pickup_at" class="form-control datetimepicker"
                                    value="{{ $order->pickup_at }}">
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <input type="text" name="note" id="note" class="form-control"
                                    value="{{ $order->note }}">
                            </div>
                            <hr>
                            <p>Order List</p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->lists as $key => $list)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>{{ $list->product->name }}</td>
                                            <td>Rp. {{ number_format($list->price, 2, ',', '.') }}</td>
                                            <td>{{ $list->quantity }}</td>
                                            <td>Rp. {{ number_format($list->price * $list->quantity, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
