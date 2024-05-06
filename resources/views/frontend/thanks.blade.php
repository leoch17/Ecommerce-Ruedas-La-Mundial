@extends('frontend.layouts.app')

@section('content')
    <section class="container">
        <div class="col-md-12 text-center py-5" style="margin-top: 10%; margin-bottom: 10%">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            <h1>¡Gracias por su Compra!</h1>
            <p>Su número de pedido es: {{ $id }}</p>
        </div>
    </section>
@endsection
