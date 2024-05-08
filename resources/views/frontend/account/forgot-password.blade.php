@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">Recuperación de Contraseña</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="login-form">
                <form action="{{ route('frontend.processForgotPassword') }}" method="post">
                    @csrf
                    <h4 class="modal-title">Recuperación de Contraseña</h4>
                    <div class="form-group">
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Correo Electrónico" name="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Enviar">
                </form>
                <div class="text-center small">Volver a <a href="{{ route('account.login') }}">Inicio Sesión</a></div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
@endsection
