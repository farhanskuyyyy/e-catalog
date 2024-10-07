@extends('layouts.app')

@section('title', 'Edit Role')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Role</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.update', ['role' => $role]) }}">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $role->name }}" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>
                            <table class="permissionTable rounded-m overflow-hidden my-4 p-4 text-center">
                                <th class="px-4 py-4 ">
                                    {{ __('Section') }}
                                </th>

                                <th class="px-4 py-4">
                                    <input id="permission-checkbox" type="checkbox" value="" name="permissions[]"
                                        class="grand_selectall">
                                    <label for="permission-checkbox" class="ms-2">{{ __('Select All') }}</label>
                                </th>

                                <th class="px-4 py-4 ">
                                    {{ __('Available permissions') }}
                                </th>
                                <tbody>
                                    @foreach ($permissions as $key => $group)
                                        <tr class="py-5">
                                            <td class="">
                                                <b class="">{{ ucfirst(str_replace('_', ' ', $key)) }}</b>
                                            </td>
                                            <td class="" width="30%">
                                                <input id="permission-checkbox" type="checkbox" value=""
                                                    name="permissions[]" class="selectall">
                                                <label for="permission-checkbox" class="ms-2">
                                                    {{ __('Select All') }}</label>
                                            </td>
                                            <td class="">
                                                <ul>
                                                    @forelse($group as $permission)
                                                        <li style="list-style-type: none;">
                                                            <div class="mb-4">
                                                                <input id="permission-checkbox" type="checkbox"
                                                                    {{ $permission->roles->count() > 0 ? 'checked' : '' }}
                                                                    value="{{ $permission->name }}" name="permissions[]"
                                                                    class="permissioncheckbox">
                                                                <label for="permission-checkbox"
                                                                    class="ms-2">{{ $permission->name }}</label>
                                                            </div>
                                                        </li>
                                                    @empty
                                                        {{ __('No permission in this group !') }}
                                                    @endforelse
                                                </ul>

                                            </td>

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
    <script>
        $(function() {
            calcu_allchkbox();
            selectall();
        });
        $(".permissionTable").on('click', '.selectall', function() {
            if ($(this).is(':checked')) {
                $(this).closest('tr').find('[type=checkbox]').prop('checked', true);
            } else {
                $(this).closest('tr').find('[type=checkbox]').prop('checked', false);
            }
            calcu_allchkbox();
        });
        $(".permissionTable").on('click', '.grand_selectall', function() {
            console.log('asds');
            if ($(this).is(':checked')) {
                $('.selectall').prop('checked', true);
                $('.permissioncheckbox').prop('checked', true);
            } else {
                $('.selectall').prop('checked', false);
                $('.permissioncheckbox').prop('checked', false);
            }
        });

        function selectall() {
            $('.selectall').each(function(i) {
                var allchecked = new Array();
                $(this).closest('tr').find('.permissioncheckbox').each(function(index) {
                    if ($(this).is(":checked")) {
                        allchecked.push(1);
                    } else {
                        allchecked.push(0);
                    }
                });
                if ($.inArray(0, allchecked) != -1) {
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            });
        }

        function calcu_allchkbox() {
            var allchecked = new Array();
            $('.selectall').each(function(i) {
                $(this).closest('tr').find('.permissioncheckbox').each(function(index) {
                    if ($(this).is(":checked")) {
                        allchecked.push(1);
                    } else {
                        allchecked.push(0);
                    }
                });
            });
            if ($.inArray(0, allchecked) != -1) {
                $('.grand_selectall').prop('checked', false);
            } else {
                $('.grand_selectall').prop('checked', true);
            }
        }
        $('.permissionTable').on('click', '.permissioncheckbox', function() {
            var allchecked = new Array;
            $(this).closest('tr').find('.permissioncheckbox').each(function(index) {
                if ($(this).is(":checked")) {
                    allchecked.push(1);
                } else {
                    allchecked.push(0);
                }
            });
            if ($.inArray(0, allchecked) != -1) {
                $(this).closest('tr').find('.selectall').prop('checked', false);
            } else {
                $(this).closest('tr').find('.selectall').prop('checked', true);
            }
            calcu_allchkbox();
        });
    </script>
    <!-- Page Specific JS File -->
@endpush
