@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Neumático</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('tires.index') }}" class="btn btn-primary">Volver</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="post" name="tireForm" id="tireForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Vehículo</label>
                                    <select name="vehicle" id="vehicle" class="form-control">
                                        <option value="">Seleccione un Vehículo</option>
                                        @if ($vehicles->isNotEmpty())
                                            @foreach ($vehicles as $vehicle)
                                                <option {{ $tire->vehicle_id == $vehicle->id ? 'selected' : '' }}
                                                    value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Suspensión</label>
                                    <select name="suspension" id="suspension" class="form-control">
                                        <option value="">Seleccione un Vehículo</option>
                                        @if ($suspensions->isNotEmpty())
                                            @foreach ($suspensions as $suspension)
                                                <option {{ $tire->suspension_id == $suspension->id ? 'selected' : '' }}
                                                    value="{{ $suspension->id }}">{{ $suspension->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $tire->name }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Etiqueta</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $tire->slug }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $tire->status == 1 ? 'selected' : '' }} value="1">
                                            Activo</option>
                                        <option {{ $tire->status == 0 ? 'selected' : '' }} value="0">
                                            Inactivo</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Mostrar en Inicio</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option {{ $suspension->showHome == 'Yes' ? 'selected' : '' }} value="Yes">Si
                                        </option>
                                        <option {{ $suspension->showHome == 'No' ? 'selected' : '' }} value="No">No
                                        </option>
                                    </select>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('tires.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@section('customJs')
    <script>
        $("#tireForm").submit(function(event) {
            event.preventDefault();

            var element = $("#tireForm");
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('tires.update', $tire->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response["status"] == true) {

                        window.location.href = "{{ route('tires.index') }}";

                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                        $("#vehicle").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                        $("#suspension").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                    } else {
                        var errors = response['errors'];
                        if (errors['name']) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['slug']) {
                            $("#slug").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $("#slug").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['vehicle']) {
                            $("#vehicle").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['vehicle']);
                        } else {
                            $("#vehicle").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['suspension']) {
                            $("#suspension").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['suspension']);
                        } else {
                            $("#suspension").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                    }

                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                }
            })
        });

        $("#name").change(function() {
            element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });
    </script>
@endsection
