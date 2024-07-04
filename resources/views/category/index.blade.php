@extends('layouts.app')

@section('title', 'DataTables')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Category</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></div>
                    <div class="breadcrumb-item">Index</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('category.create') }}" class="btn btn-success">Add Category</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table" id="table-category"  style="text-align: center">
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
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        var base_url = $('meta[name=base_url]').attr('content');
        getDataList()

        function getDataList() {
            $("#table-category").DataTable({
                ajax: {
                    'type': 'get',
                    'url': "{{ route('category.list') }}",
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
                            var action_html =
                                `<a href="${base_url}/category/${data.id}/show"  class="btn btn-success btn-sm" alt="View Detail" title="View Detail"><i class="fa fa-eye"></i></a>
                            <a href="${base_url}/category/${data.id}/edit"  class="btn btn-warning btn-sm" alt="View Edit" title="View Edit"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)" onclick="deleteCategory('${data.id}')" class="btn btn-danger btn-sm" alt="Delete" title="Delete"><i class="fa fa-trash"></i></a> `;
                            return action_html;
                        },
                    }
                ]
            });
        }

        function deleteCategory(id) {
                Swal.fire({
                    title: 'Do you want to delete the category?',
                    showCancelButton: true,
                    icon: 'warning',
                    confirmButtonColor: '#eb2626',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: base_url + "/category/" + id + "/delete",
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
                                    timer : 2000
                                });
                                $('#table-category').DataTable().destroy();
                                getDataList();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: jqXHR.responseJSON.message,
                                    timer : 2000
                                });
                            }
                        });
                    }
                })
            }
    </script>
@endpush
