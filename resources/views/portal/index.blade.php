@extends('layouts.portal')

@section('title', 'Menu')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #ff9800;
            color: white;
            padding: 10px 0;
            z-index: 1000;
        }

        .sticky-footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
@endpush

@section('main')
    <nav class="navbar navbar-secondary navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">
                @foreach ($categories as $key => $category)
                    <li class="nav-item nav-category {{ $key == 0 ? 'active' : '' }}" data-id="{{ 'card-' . $category->id }}">
                        <a href="#" class="nav-link"><span>{{ $category->name }}</span></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                @foreach ($categories as $key => $category)
                    <div class="card card-category" id="{{ 'card-' . $category->id }}"
                        style=" {{ $key == 0 ? '' : 'display:none;' }}">
                        <div class="card-header">
                            <h4>{{ $category->name }}</h4>
                        </div>
                        <div class="card-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#addProduct" aria-controls="addProduct">Toggle bottom
                                offcanvas</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="addProduct" aria-labelledby="addProductLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="addProductLabel">Input Jumlah</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            ...
        </div>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="showList" aria-labelledby="showListLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="showListLabel">List Pesanan</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            ...
        </div>
    </div>

@endsection

@push('scripts')
    <div class="sticky-footer bg-primary">
        <div class="container">
            <div class="total-price">
                <span>Jumlah Item : 1</span>
                <br>
                <span>Total: Rp 55,000</span>
            </div>
            <div>
                <a href="#" class="text-white" style="text-decoration: none;" data-bs-toggle="offcanvas"
                    data-bs-target="#showList" aria-controls="showList">Lihat Pesanan ></a>
            </div>
        </div>
    </div>
    <!-- JS Libraies -->
    <script>
        $(document).ready(function() {
            $('.nav-category').click(function() {

                $('.nav-category').removeClass('active');
                $(this).addClass('active');

                $('body').removeClass('sidebar-show');
                $('body').addClass('sidebar-gone');

                var card_id = $(this).attr('data-id');
                $('.card-category').hide();
                $(`#${card_id}`).show();
            })
        })
    </script>
    <!-- Page Specific JS File -->
@endpush
