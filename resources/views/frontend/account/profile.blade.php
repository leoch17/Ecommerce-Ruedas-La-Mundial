@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Mi Cuenta</a></li>
                    <li class="breadcrumb-item">Configuración</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Información Personal</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" id="name" placeholder="Ingrese su Nombre"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="text" name="email" id="email"
                                        placeholder="Ingrese su Correo Electrónico" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Telefóno</label>
                                    <input type="text" name="phone" id="phone" placeholder="Ingrese su Telefóno"
                                        class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="phone">Dirección</label>
                                    <textarea name="address" id="address" class="form-control" cols="30" rows="5"
                                        placeholder="Ingrese su Dirección"></textarea>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-dark">Actualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
