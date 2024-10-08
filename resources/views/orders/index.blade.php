@extends('layouts.app')

@section('title', 'Order')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/v2/dataTables.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('orders.create') }}" class="btn btn-success">Add Order</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table" id="table-orders" style="text-align: center">
                                <thead>
                                    <tr>
                                        <th class="text-center">Order Code</th>
                                        <th class="text-center">Nama User</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Shipping</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Pickup_at</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/v2/dataTables.min.js') }}"></script>
    <script>
        getDataList()

        function getDataList() {
            $("#table-orders").DataTable({
                ajax: {
                    'type': 'get',
                    'url': "{{ route('orders.list') }}",
                    'data': {
                        // "dates": dates,
                    }
                },
                initComplete: function(settings, data) {
                    // console.log(data);
                },
                language: {
                    emptyTable: "Data not found.",
                    processing: 'Proccesing data competition...',
                },
                order: [
                    [0, 'asc']
                ],
                // responsive: true,
                columns: [{
                        data: 'order_code',
                    },
                    {
                        data: 'user.name',
                    },
                    {
                        data: 'payment',
                    },
                    {
                        data: 'shipping',
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            switch (data.status.toLowerCase()) {
                                case 'pending':
                                    return '<div class="bg-warning rounded text-black">Pending</div>'
                                    break;
                                case 'process':
                                    return '<div class="bg-primary rounded text-white">Process</div>'
                                    break;
                                case 'delivered':
                                    return '<div class="bg-success rounded text-black">Delivered</div>'
                                    break;
                                case 'done':
                                    return '<div class="bg-success rounded text-black">Done</div>'
                                    break;
                                case 'cancel':
                                    return '<div class="bg-danger rounded text-black">Cancel</div>'
                                    break;
                                default:
                                    return "";
                                    break;
                            }
                        },
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            return (data.pickup_at) ? moment(data.pickup_at).format('YYYY-MM-DD HH:mm:ss') : "-"
                        },
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            return data.action
                        },
                    }
                ]
            });
        }

        function deleteOrder(url) {
            Swal.fire({
                title: 'Do you want to delete the order?',
                showCancelButton: true,
                icon: 'warning',
                confirmButtonColor: '#eb2626',
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: "DELETE",
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Please Wait !',
                                html: 'Updating Data ...', // add html attribute if you want or remove
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading()
                                },
                            });
                        },
                        success: function(data) {
                            swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: data.message,
                                timer: 2000
                            });
                            $('#table-orders').DataTable().destroy();
                            getDataList();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: jqXHR.responseJSON.message,
                                timer: 2000
                            });
                        }
                    });
                }
            })
        }
    </script>
@endpush
