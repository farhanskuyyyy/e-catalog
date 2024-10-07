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
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('categories.create') }}" class="btn btn-success">Add Category</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table" id="table-categories" style="text-align: center">
                                <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Latest Update</th>
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
            $("#table-categories").DataTable({
                ajax: {
                    'type': 'GET',
                    'url': "{{ route('categories.list') }}",
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
                        data: 'name',
                    },
                    {
                        data: null,
                        class: 'text-center',
                        render: function(data) {
                            return moment(data.updated_at).format('YYYY-MM-DD HH:mm:ss')
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

        function deleteCategory(url) {
            Swal.fire({
                title: 'Do you want to delete the category?',
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

                            $('#table-categories').DataTable().destroy();
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