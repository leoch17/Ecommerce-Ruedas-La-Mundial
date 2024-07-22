@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Crear Artículo</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('articles.index') }}" class="btn btn-primary">Volver</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="articleForm" id="articleForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Vehículo</label>
                                    <select name="vehicle" id="vehicle" class="form-control">
                                        <option value="">Seleccione un vehículo</option>
                                        @if ($vehicles->isNotEmpty())
                                            @foreach ($vehicles as $vehicle)
                                                <option {{ $article->vehicle_id == $vehicle->id ? 'selected' : '' }}
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
                                        <option value="">Seleccione un suspensión</option>
                                        @if ($suspensions->isNotEmpty())
                                            @foreach ($suspensions as $suspension)
                                                <option {{ $article->suspension_id == $suspension->id ? 'selected' : '' }}
                                                    value="{{ $suspension->id }}">{{ $suspension->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Neumático</label>
                                    <select name="tire" id="tire" class="form-control">
                                        <option value="">Seleccione un neumático</option>
                                        @if ($tires->isNotEmpty())
                                            @foreach ($tires as $tire)
                                                <option {{ $article->tire_id == $tire->id ? 'selected' : '' }}
                                                    value="{{ $tire->id }}">{{ $tire->name }}</option>
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
                                        placeholder="Nombre" value="{{ $article->name }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Etiqueta</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Etiqueta" value="{{ $article->slug }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image">Imagen</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Suelte los archivos aquí o haga clic para cargarlos.<br><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="product-gallery">
                                    @if ($articleImages->isNotEmpty())
                                        @foreach ($articleImages as $image)
                                            <div class="col-md-3" id="image-row-{{ $image->id }}">
                                                <div class="card">
                                                    <input type="hidden" name="image_array" value="{{ $image->id }}">
                                                    <img src="{{ asset('/uploads/article/small/' . $image->image) }}"
                                                        class="card-img-top" alt="">
                                                    <div class="card-body">
                                                        <a href="javascript:void(0)"
                                                            onclick="deleteImage({{ $image->id }})"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $vehicle->status == 1 ? 'selected' : '' }} value="1">Activo
                                        </option>
                                        <option {{ $vehicle->status == 0 ? 'selected' : '' }} value="0">Inactivo
                                        </option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Mostrar en Inicio</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="Yes">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@section('customJs')
    <script>
        $("#articleForm").submit(function(event) {
            event.preventDefault();
            var formArray = $(this).serializeArray();
            $("button[type = 'submit']").prop('disabled', true);

            $.ajax({
                url: '{{ route('articles.update', $article->id) }}',
                type: 'put',
                data: formArray,
                dataType: 'json',
                success: function(response) {
                    $("button[type = 'submit']").prop('disabled', false);

                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                        window.location.href = "{{ route('articles.index') }}";
                    } else {
                        var errors = response['errors'];

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                        });

                    }
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        })

        // $("#articleForm").submit(function(event) {
        //     event.preventDefault();

        //     var element = $("#articleForm");
        //     $("button[type=submit]").prop('disabled', true);

        //     $.ajax({
        //         url: '{{ route('articles.store') }}',
        //         type: 'post',
        //         data: element.serializeArray(),
        //         dataType: 'json',
        //         success: function(response) {
        //             $("button[type=submit]").prop('disabled', false);

        //             if (response["status"] == true) {

        //                 window.location.href = "{{ route('articles.index') }}";

        //                 $("#name").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback')
        //                     .html("");

        //                 $("#slug").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback')
        //                     .html("");

        //                 $("#article").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback')
        //                     .html("");

        //             } else {
        //                 var errors = response['errors'];
        //                 if (errors['name']) {
        //                     $("#name").addClass('is-invalid')
        //                         .siblings('p')
        //                         .addClass('invalid-feedback')
        //                         .html(errors['name']);
        //                 } else {
        //                     $("#name").removeClass('is-invalid')
        //                         .siblings('p')
        //                         .removeClass('invalid-feedback')
        //                         .html("");
        //                 }

        //                 if (errors['slug']) {
        //                     $("#slug").addClass('is-invalid')
        //                         .siblings('p')
        //                         .addClass('invalid-feedback')
        //                         .html(errors['slug']);
        //                 } else {
        //                     $("#slug").removeClass('is-invalid')
        //                         .siblings('p')
        //                         .removeClass('invalid-feedback')
        //                         .html("");
        //                 }

        //                 if (errors['article']) {
        //                     $("#article").addClass('is-invalid')
        //                         .siblings('p')
        //                         .addClass('invalid-feedback')
        //                         .html(errors['article']);
        //                 } else {
        //                     $("#article").removeClass('is-invalid')
        //                         .siblings('p')
        //                         .removeClass('invalid-feedback')
        //                         .html("");
        //                 }

        //             }

        //         },
        //         error: function(jqXHR, exception) {
        //             console.log("Something went wrong");
        //         }
        //     })
        // });

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

        $("#vehicle").change(function(event) {
            var vehicle_id = $(this).val();
            $.ajax({
                url: '{{ route('article-suspensions.index') }}',
                type: 'get',
                data: {
                    vehicle_id: vehicle_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#suspension").find("option").not(":first").remove();
                    $.each(response["suspensions"], function(key, item) {
                        $("#suspension").append(
                            `<option value='${item.id}'>${item.name}</option>`);
                    });
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        })

        $("#suspension").change(function(event) {
            var suspension_id = $(this).val();
            $.ajax({
                url: '{{ route('article-tires.index') }}',
                type: 'get',
                data: {
                    suspension_id: suspension_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#tire").find("option").not(":first").remove();
                    $.each(response["tires"], function(key, item) {
                        $("#tire").append(
                            `<option value='${item.id}'>${item.name}</option>`);
                    });
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        })

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url: "{{ route('article-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params: {
                'article_id': '{{ $article->id }}'
            },
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(file, response) {

                var html =
                    `<div class="col-md-3" id="image-row-${response.image_id}">
                        <div class="card">
                            <input type="hidden" name="image_array" value="${response.image_id}">
                            <img src="${response.ImagePath}" class="card-img-top" alt="">
                            <div class="card-body">
                                <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>`;

                $("#product-gallery").append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteImage(id) {
            $("#image-row-" + id).remove();
            if (confirm("¿Estas seguro que quieres eliminar la imagen?")) {
                $.ajax({
                    url: '{{ route('article-images.destroy') }}',
                    type: 'delete',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == true) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                })
            }
        }
    </script>
@endsection
