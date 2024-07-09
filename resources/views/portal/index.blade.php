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

        input,
        textarea {
            border: 1px solid #eeeeee;
            box-sizing: border-box;
            margin: 0;
            outline: none;
            padding: 10px;
        }

        input[type="button"] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .group-input {
            clear: both;
            /* margin: 15px 0; */
            position: relative;
        }

        .group-input input[type='button'] {
            background-color: #eeeeee;
            min-width: 38px;
            width: auto;
            transition: all 300ms ease;
        }

        .group-input .button-minus,
        .group-input .button-plus {
            font-weight: bold;
            height: 38px;
            padding: 0;
            width: 38px;
            position: relative;
        }

        .group-input .quantity-field {
            position: relative;
            height: 38px;
            left: -6px;
            text-align: center;
            width: 62px;
            display: inline-block;
            font-size: 13px;
            margin: 0 0;
            resize: vertical;
        }

        .button-plus {
            left: -13px;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: none;
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
                            <div class="row">
                                @if (count($category->products) > 0)
                                    @foreach ($category->products as $product)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="card text-center border" style="width: 18rem;">
                                                <img src="{{ asset('storage/product/' . $product->image) }}" alt=""
                                                    class="p-4" style="width: 100%;height:100%;"
                                                    onerror="this.src='https://placehold.co/100x100'">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $product->name }}</h5>
                                                    <p class="card-text">{{ $product->description }}</p>
                                                    <p class="card-text">Rp.
                                                        {{ number_format($product->price, 2, ',', '.') }}</p>
                                                    <button class="btn btn-primary add-product" type="button"
                                                        data-bs-toggle="offcanvas" data-bs-target="#addProduct"
                                                        aria-controls="addProduct" data-id="{{ $product->id }}"
                                                        data-price="{{ $product->price }}"
                                                        data-name="{{ $product->name }}"
                                                        data-stock="{{ $product->stock }}">Add
                                                        Product</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Data Not Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="addProduct" aria-labelledby="addProductLabel"
        style="height: 20vh">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="pre-product-label">Product Name</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            <div class="row">
                <div class="col-5 col-md-2">
                    <div class="group-input">
                        <input type="button" value="-" class="button-minus" data-field="pre-input-quantity">
                        <input type="number" step="1" max="" value="1" name="pre-input-quantity"
                            id="pre-input-quantity" class="quantity-field">
                        <input type="button" value="+" class="button-plus" data-field="pre-input-quantity">
                    </div>
                </div>
                <div class="col-7 col-md-10 mt-2">
                    <button type="button" id="pre-input-button" class="btn btn-primary w-100">
                        Add <span id="pre-price-product"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="showList" aria-labelledby="showListLabel"
        style="height: 55vh">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="showListLabel">List Pesanan</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            <div class="list-group">
            </div>
            <a href="#" class="list-group-item list-group-item-action bg-secondary" aria-current="true">
                <div class="row">
                    <div class="col-6 col-md-9">
                        <h5 class="">Total Semua</h5>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="cart-total-harga">Rp. 0</p>
                    </div>
                </div>
            </a>
            <hr class="my-3">
            <form action="{{ route('create-order') }}" method="POST" id="cart-form">
                @csrf
                <div class="form-group">
                    <label for="name">Jenis Pesanan</label>
                    <select name="shipping" id="shipping" class="form-control" required>
                        <option value="">Select Jenis Pesanan</option>
                        @foreach ($shippings as $shipping)
                            <option value="{{ $shipping }}" {{ $shipping == old('shipping') ? 'selected' : '' }}>
                                {{ $shipping }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment">Jenis Pembayaran</label>
                    <select name="payment" id="payment" class="form-control" required>
                        <option value="">Select Jenis Pembayaran</option>
                        @foreach ($payments as $payment)
                            <option value="{{ $payment }}" {{ $payment == old('payment') ? 'selected' : '' }}>
                                {{ $payment }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nama Pesanan</label>
                    <input type="text" class="form-control mb-1" id="name" name="name" required
                        placeholder="Masukkan Nama" value="{{ old('name') }}">
                    <input type="text" class="form-control mb-1" id="phonenumber" name="phonenumber" required
                        placeholder="Masukkan Nomor Telepon" value="{{ old('phonenumber') }}">
                    <input type="text" class="form-control" id="note" name="note" required
                        placeholder="Masukkan Catatan" value="{{ old('note') }}">
                </div>
                <button class="btn btn-primary" type="submit">Pesan</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <div class="sticky-footer bg-primary">
        <div class="container">
            <div class="total-price">
                <span>Jumlah Item : <span id="cart-total-item">0</span></span>
                <br>
                <span>Total : <span class="cart-total-harga">0</span></span>
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
            var base_url = $('meta[name=base_url]').attr('content');
            var id, name, price, quantity, stock;
            var data = {};
            const addProductCanvas = new bootstrap.Offcanvas($('#addProduct'))
            const showListCanvas = new bootstrap.Offcanvas($('#showList'))

            // input
            $(document).on('click', '.button-plus', function(e) {
                incrementValue(e);
            });

            $(document).on('click', '.button-minus', function(e) {
                decrementValue(e);
            });

            $('.nav-category').click(function() {
                $('.nav-category').removeClass('active');
                $(this).addClass('active');

                $('body').removeClass('sidebar-show');
                $('body').addClass('sidebar-gone');

                var card_id = $(this).attr('data-id');
                $('.card-category').hide();
                $(`#${card_id}`).show();
            })

            $('.add-product').click(function() {
                $('#pre-input-quantity').val(1)
                id = $(this).attr('data-id');
                name = $(this).attr('data-name')
                price = $(this).attr('data-price')
                quantity = $('#pre-input-quantity').val();
                stock = $(this).attr('data-stock')
                $('#pre-input-quantity').attr('max', stock)
                $('#pre-product-label').text(name);
                refreshPricePrepare()
            })

            $('#pre-input-quantity').change(function() {
                if (parseInt($(this).attr('max')) < parseInt($(this).val())) {
                    $(this).val($(this).attr('max'))
                }
                quantity = $(this).val();
                refreshPricePrepare()
            })

            $('#pre-input-button').click(function(event) {
                addProductCanvas.hide()
                addProduct(id, quantity, name, price, stock)
                loadJumlahItem();
            })

            $('.list-group').on('change', '.cart-input-quantity', function() {
                quantity = parseInt($(this).val());
                id = $(this).attr('data-id');
                if (quantity === 0) {
                    if (data.hasOwnProperty("prod" + id)) {
                        delete data["prod" + id];
                        loadJumlahItem();
                    }
                } else {
                    if (parseInt(data["prod" + id].stock) < parseInt(quantity)) {
                        quantity = data["prod" + id].stock;
                        $(this).val(quantity)
                    }
                    price = $(this).attr('data-price');
                    data["prod" + id].quantity = quantity;
                    loadJumlahItem(false);
                    $(`#cart-total-harga-${id}`).text(convertCurrency(quantity * price))
                }
            })

            $('#cart-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: `${base_url}/create-order`,
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
                    data: {
                        products: data,
                        note: $('#note').val(),
                        name: $('#name').val(),
                        phonenumber: $('#phonenumber').val(),
                        shipping: $('#shipping :selected').val(),
                        payment: $('#payment :selected').val(),
                    },
                    success: function(response) {
                        if (response.status == true) {
                            showListCanvas.hide()
                            swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: response.message,
                                timer: 2000
                            });
                            setTimeout(() => {
                                window.location.replace(
                                    `${base_url}/check-order?order_code=${response.data.order_code}`
                                );
                            }, 2000);
                        } else {
                            swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: response.message,
                                timer: 2000
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Server Error",
                            timer: 2000
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);

                    },
                });
            })

            function incrementValue(e) {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

                if (!isNaN(currentVal)) {
                    parent.find('input[name=' + fieldName + ']').val(currentVal + 1).change();
                } else {
                    parent.find('input[name=' + fieldName + ']').val(0).change();
                }
            }

            function decrementValue(e) {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

                if (!isNaN(currentVal) && currentVal > 0) {
                    parent.find('input[name=' + fieldName + ']').val(currentVal - 1).change();
                } else {
                    parent.find('input[name=' + fieldName + ']').val(0).change();
                }
            }

            function refreshPricePrepare() {
                $('#pre-price-product').text(convertCurrency(quantity * price));
            }

            function loadJumlahItem(withAppendProduct = true) {
                var html = "";
                var harga = 0;
                var totalItem = 0;
                Object.keys(data).forEach(function(key) {
                    var totalHargaProduct = data[key].quantity * data[key].price;
                    html += `<a href="#" class="list-group-item list-group-item-action" aria-current="true">
                                <div class="row">
                                    <div class="col-6 col-md-9">
                                        <h5 class="">${data[key].name}</h5>
                                        <p class="">${convertCurrency(data[key].price)}</p>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="group-input">
                                            <input type="button" value="-" class="button-minus" data-field="input-quantity-${data[key].id}">
                                            <input type="number" step="1" max="" name="input-quantity-${data[key].id}" value="${data[key].quantity}" data-id="${data[key].id}" data-price="${data[key].price}"
                                                class="quantity-field cart-input-quantity">
                                            <input type="button" value="+" class="button-plus" data-field="input-quantity-${data[key].id}">
                                        </div>
                                        <p class="" id="cart-total-harga-${data[key].id}">${convertCurrency(totalHargaProduct)}</p>
                                    </div>
                                </div>
                            </a>`
                    harga += totalHargaProduct;
                    totalItem += 1;
                });

                if (withAppendProduct) {
                    $('.list-group').html(html);
                }

                $('#cart-total-item').text(totalItem);
                $('.cart-total-harga').text(convertCurrency(harga));
            }

            function addProduct(id, quantity, name = "", price = "", stock = 1) {
                var key = "prod" + id;
                if (data[key]) {
                    // If the product already exists, update the quantity
                    data[key].quantity += parseInt(quantity);
                } else {
                    // If the product does not exist, add it as a new entry
                    data[key] = {
                        "id": parseInt(id),
                        "name": name,
                        "price": parseInt(price),
                        "quantity": parseInt(quantity),
                        "stock": parseInt(stock),
                    };
                }
            }

            function convertCurrency(price) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(
                    price,
                );
            }
        })
    </script>
    <!-- Page Specific JS File -->
@endpush
