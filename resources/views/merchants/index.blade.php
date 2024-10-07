@extends('layouts.app')

@section('title', 'Merchant')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/v2/dataTables.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Merchant</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('merchants.index') }}">Merchants</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table" id="table-merchants" style="text-align: center">
                                <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Request Date</th>
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
            $("#table-merchants").DataTable({
                ajax: {
                    'type': 'GET',
                    'url': "{{ route('merchants.list') }}",
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
                        data: 'user.name',
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            if (data.is_active) {
                                return `<span class="text-success">Active</span>`
                            } else {
                                return `<span class="text-warning">Pending</span>`
                            }
                        },
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            return moment(data.created_at).format('YYYY-MM-DD HH:mm:ss')
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

        function approveMerchant(url) {
            Swal.fire({
                title: 'Do you want to approve the merchant?',
                showCancelButton: true,
                icon: 'warning',
                confirmButtonColor: '#1EBBD7',
                confirmButtonText: 'Approve',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: "PUT",
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

                            $('#table-merchants').DataTable().destroy();
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
