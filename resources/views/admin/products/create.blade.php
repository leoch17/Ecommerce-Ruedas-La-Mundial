@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Crear Producto</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Volver</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="productForm" id="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Título</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Título">
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Etiqueta</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Etiqueta">
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Descripción</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Suelte los archivos aquí o haga clic para cargarlos.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="product-gallery">

                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Precios</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Precio</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Precio">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Precio en Comparación</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Precio en Comparación">
                                            <p class="text-muted mt-3">
                                                Para mostrar un precio reducido, mueva el precio original del producto en
                                                Comparar a precio. Introduzca un valor inferior en Precio.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventario</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">UME (Unidad de Mantenimiento de Existencias)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="ume">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Código de Barras</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Código de Barras">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes" checked>
                                                <label for="track_qty" class="custom-control-label">Cantidad
                                                    Disponible</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Cantidad">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Estado de Producto</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Categoría del Producto</h2>
                                <div class="mb-3">
                                    <label for="category">Categoría</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Seleccione una Categoría</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub Categoría</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Seleccione una Sub Categoría</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Marca del Producto</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Seleccione una Marca</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Producto Destacado</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Si</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Crear</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#title").change(function() {
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

        $("#productForm").submit(function(event) {
            event.preventDefault();
            var formArray = $(this).serializeArray();
            $("button[type = 'submit']").prop('disabled', true);

            $.ajax({
                url: '{{ route('products.store') }}',
                type: 'post',
                data: formArray,
                dataType: 'json',
                success: function(response) {
                    $("button[type = 'submit']").prop('disabled', false);

                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                        window.location.href = "{{ route('products.index') }}";
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

        $("#category").change(function(event) {
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route('product-subcategories.index') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"], function(key, item) {
                        $("#sub_category").append(
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
            url: "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: 'image',
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
        }
    </script>
@endsection
