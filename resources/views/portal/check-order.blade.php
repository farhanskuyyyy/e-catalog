@extends('layouts.portal')

@section('title', 'Check Status')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <nav class="navbar navbar-secondary navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">

            </ul>
        </div>
    </nav>

    <div class="main-content">
        <div class="card card-category">
            <div class="card-header">
                <h4>Check Order</h4>
            </div>
            <div class="card-body">
                <form action="" method="get">
                    <div class="form-group">
                        <label for="order_code">Search</label>
                        <input type="text" class="form-control" id="order_code" name="order_code" value="{{ (isset($order_code)) ? $order_code : '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <hr>

                @if (isset($order))
                    <p>Order Code : {{ $order->order_code }}</p>
                    <p>Payment : {{ $order->payment }}</p>
                    <p>Shipping : {{ $order->shipping }}</p>
                    <p>Status : {{ $order->status }}</p>
                    <p>Pickup At : {{ $order->pickup_at }}</p>
                    <p>Total Amount : {{ $order->total_amount }}</p>
                    <p>Note : {{ $order->note }}</p>
                    <hr>
                    <br>
                    <p>Order List</p>
                    @foreach ($order->lists as $key => $list)
                    <p>{{ ($key+1) .". ". $list->product->name }}</p>
                    <p>Price : {{ $list->price }}</p>
                    <p>Quantity : {{ $list->quantity }}</p>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <!-- Page Specific JS File -->
@endpush
