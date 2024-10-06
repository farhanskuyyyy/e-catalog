@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/flag-icon-css/css/flag-icon.min.css') }}">
@endpush

@section('main')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-stats">
                            <div class="card-stats-title">Order Statistics
                            </div>
                            <div class="card-stats-items">
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ $orderStats[0]->status_cancel }}</div>
                                    <div class="card-stats-item-label">Cancel</div>
                                </div>
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ $orderStats[0]->status_pending }}</div>
                                    <div class="card-stats-item-label">Pending</div>
                                </div>
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ $orderStats[0]->status_process }}</div>
                                    <div class="card-stats-item-label">Process</div>
                                </div>
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ $orderStats[0]->status_success }}</div>
                                    <div class="card-stats-item-label">Done</div>
                                </div>
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ $orderStats[0]->status_delivered }}</div>
                                    <div class="card-stats-item-label">Delivered</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-archive"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Orders</h4>
                            </div>
                            <div class="card-body">
                                {{ $orderStats[0]->total }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-chart">
                            <canvas id="balance-chart" height="80"></canvas>
                        </div>
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Balance</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ number_format($totalRevenue[0]->total, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Orders</h4>
                            <div class="card-header-action">
                                <a href="{{ route('orders.index') }}" class="btn btn-danger">View More <i
                                        class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive table-invoice">
                                <table class="table-striped table">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Pickup Date</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td><a href="#">{{ $order->order_code }}</a></td>
                                            <td class="font-weight-600">{{ $order->user->name }}</td>
                                            <td>
                                                @switch(strtolower($order->status))
                                                    @case('pending')
                                                        <div class="badge badge-warning rounded text-black">Pending</div>
                                                    @break
                                                    @case('process')
                                                        <div class="badge badge-primary rounded text-white">Process</div>
                                                    @break

                                                    @case('delivered')
                                                        <div class="badge badge-success rounded text-black">Delivered</div>
                                                    @break

                                                    @case('done')
                                                        <div class="badge badge-success rounded text-black">Done</div>
                                                    @break

                                                    @case('cancel')
                                                        <div class="badge badge-danger rounded text-black">Cancel</div>
                                                    @break

                                                    @default
                                                @endswitch
                                            </td>
                                            <td>{{ $order->pickup_at }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', ['id' => $order->id]) }}"
                                                    class="btn btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    {{-- <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script> --}}
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    {{-- <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script> --}}

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index.js') }}"></script>
@endpush
