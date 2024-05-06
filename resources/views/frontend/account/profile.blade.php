@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">Mi Perfil</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('frontend.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Información Personal</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Nombre</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Ingrese su Nombre" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Correo Electrónico</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Ingrese su Correo Electrónico" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Telefóno</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="Ingrese su Telefóno" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Actualizar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Dirección de Facturación</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Nombre</label>
                                        <input value="{{ !empty($address) ? $address->first_name : '' }}" type="text"
                                            name="first_name" id="first_name" placeholder="Ingrese su Nombre"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Apellido</label>
                                        <input value="{{ !empty($address) ? $address->last_name : '' }}" type="text"
                                            name="last_name" id="last_name" placeholder="Ingrese su Apellido"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">Correo Electrónico</label>
                                        <input value="{{ !empty($address) ? $address->email : '' }}" type="text"
                                            name="email" id="email" placeholder="Ingrese su Correo Electrónico"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Célular</label>
                                        <input value="{{ !empty($address) ? $address->mobile : '' }}" type="text"
                                            name="mobile" id="mobile" placeholder="Ingrese su Célular"
                                            class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone">Estado</label>
                                        <select name="state_id" id="state_id" class="form-control">
                                            <option value="">Selecciona un Estado</option>
                                            @if ($states->isNotEmpty())
                                                @foreach ($states as $state)
                                                    <option
                                                        {{ !empty($address) && $address->state_id == $state->id ? 'selected' : '' }}
                                                        value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone">Dirección</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ !empty($address) ? $address->address : '' }}</textarea>
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone">Domicilio</label>
                                        <input value="{{ !empty($address) ? $address->apartment : '' }}" type="text"
                                            name="apartment" id="apartment" placeholder="Departamento / Casa"
                                            class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Ciudad</label>
                                        <input value="{{ !empty($address) ? $address->city : '' }}" type="text"
                                            name="city" id="city" placeholder="Ingrese su Ciudad"
                                            class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Código ZIP</label>
                                        <input value="{{ !empty($address) ? $address->zip : '' }}" type="text"
                                            name="zip" id="zip" placeholder="Ingrese su Código ZIP"
                                            class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Actualizar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#profileForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#profileForm #name")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        $("#profileForm #email")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        $("#profileForm #phone")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        window.location.href = '{{ route('account.profile') }}';

                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $("#profileForm #name").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $("#profileForm #email").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #email")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.phone) {
                            $("#profileForm #phone").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.phone)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #phone")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });

        $("#addressForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#name")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        $("#email")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        $("#phone")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        window.location.href = '{{ route('account.profile') }}';

                    } else {
                        var errors = response.errors;
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.first_name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#first_name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.last_name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#last_name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $("#addressForm #email").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #email")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.mobile)
                                .addClass('invalid-feedback');
                        } else {
                            $("#mobile")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.state_id) {
                            $("#state_id").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.state_id)
                                .addClass('invalid-feedback');
                        } else {
                            $("#state_id")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.address)
                                .addClass('invalid-feedback');
                        } else {
                            $("#address")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.apartment) {
                            $("#apartment").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.apartment)
                                .addClass('invalid-feedback');
                        } else {
                            $("#apartment")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.city) {
                            $("#city").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.city)
                                .addClass('invalid-feedback');
                        } else {
                            $("#city")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.zip) {
                            $("#zip").addClass('is-invalid')
                                .siblings('p')
                                .html(errors.zip)
                                .addClass('invalid-feedback');
                        } else {
                            $("#zip")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });
    </script>
@endsection
