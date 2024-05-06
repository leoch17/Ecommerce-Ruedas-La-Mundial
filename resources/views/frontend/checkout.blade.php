@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.shop') }}">Tienda</a></li>
                    <li class="breadcrumb-item">Pasarela de Pago</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id="orderForm" name="orderForm" action="" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Dirección de Envío</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="Nombre"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Apellido"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Correo Electrónico"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="state" id="state" class="form-control">
                                                <option value="">Selecciona un Estado</option>
                                                @if ($states->isNotEmpty())
                                                    @foreach ($states as $state)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->state_id == $state->id ? 'selected' : '' }}
                                                            value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Dirección" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Departamento, suit, unidad, etc. (opcional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="Ciudad"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Código Postal"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Teléfono"
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Notas de envío (opcional)"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Resumen del Pedido</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">

                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ $item->price * $item->qty }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                                </div>

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Descuento</strong></div>
                                    <div class="h6"><strong id="discount_value">${{ $discount }}</strong></div>
                                </div>

                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Envío</strong></div>
                                    <div class="h6"><strong
                                            id="shippingAmount">${{ number_format($totalShippingCharge, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong
                                            id="grandTotal">${{ number_format($grandTotal, 2) }}</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Código de Cupón" class="form-control"
                                name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Aplicar Cupón</button>
                        </div>

                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="mt-4" id="discount-response">
                                    <strong>{{ Session::get('code')->code }}</strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                                </div>
                            @endif
                        </div>


                        <div class="card payment-form ">

                            <h3 class="card-title h5 mb-3">Metodos de Pago</h3>
                            <div class="">
                                <input checked type="radio" name="payment_method" value="cod"
                                    id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>

                            <div class="">
                                <input type="radio" name="payment_method" value="cod" id="payment_method_two"
                                    disabled>
                                <label for="payment_method_two" class="form-check-label">Tarjeta</label>
                            </div>

                            <div class="">
                                <input type="radio" name="payment_method" value="cod" id="payment_method_three">
                                <label for="payment_method_three" class="form-check-label">Zelle</label>
                            </div>

                            <div class="card-body p-0 d-none mt-3" id="card-payment-form-1">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Número de Tarjeta</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="0000 0000 0000 0000" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Fecha de Caducidad</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0 d-none mt-3" id="card-payment-form-2">
                                <div class="mb-3">
                                    <label for="direccion-correo" class="mb-2">Correo Electrónico</label>
                                    <input type="text" name="direccion-correo" id="direccion-correo"
                                        placeholder="example@email.com" class="form-control">

                                    <label for="name-complete" class="mb-2">Nombre Completo</label>
                                    <input type="text" name="name-complete" id="name-complete"
                                        placeholder="John Freeman" class="form-control">

                                    <label for="concept" class="mb-2">Concepto</label>
                                    <input type="text" name="concept" id="concept" placeholder="Compra de Producto"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Comprar Ahora</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Comprar Ahora</button>
                            </div>
                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_one").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-1").addClass('d-none');
            }
        });

        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-1").removeClass('d-none');
            }
        });

        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-2").addClass('d-none');
            }
        });

        $("#payment_method_three").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-2").removeClass('d-none');
            }
        });

        $("#payment_method_one").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-2").addClass('d-none');
            }
        });

        $("#payment_method_three").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-2").removeClass('d-none');
            }
        });

        $("#payment_method_three").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-1").addClass('d-none');
            }
        });

        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form-1").removeClass('d-none');
            }
        });

        $("#orderForm").submit(function(event) {
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route('frontend.processCheckout') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled', false);

                    if (response.status == false) {
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.first_name);
                        } else {
                            $("#first_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.last_name);
                        } else {
                            $("#last_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.state) {
                            $("#state").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.state);
                        } else {
                            $("#state").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.address);
                        } else {
                            $("#address").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.city) {
                            $("#city").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.city);
                        } else {
                            $("#city").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.zip) {
                            $("#zip").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.zip);
                        } else {
                            $("#zip").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.mobile);
                        } else {
                            $("#mobile").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                    } else {
                        window.location.href = "{{ url('/thanks/') }}/" + response.orderId;
                    }
                }
            });
        });

        $("#state").change(function() {
            $.ajax({
                url: '{{ route('frontend.getOrderSummery') }}',
                type: 'post',
                data: {
                    state_id: $(this).val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                    }
                }
            });
        });

        $("#apply-discount").click(function() {
            $.ajax({
                url: '{{ route('frontend.applyDiscount') }}',
                type: 'post',
                data: {
                    code: $("#discount_code").val(),
                    state_id: $("#state").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        $("#discount-response-wrapper")
                            .html("<span class='text-danger'>" + response.message + "</span>");
                    }
                }
            });
        });

        $('body').on('click', "#remove-discount", function() {
            $.ajax({
                url: '{{ route('frontend.removeCoupon') }}',
                type: 'post',
                data: {
                    state_id: $("#state").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response").html('');
                        $("#discount_code").val('');
                    }
                }
            });
        });

        // $("#remove-discount").click(function() {

        // });
    </script>
@endsection
