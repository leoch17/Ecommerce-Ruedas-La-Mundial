@extends('frontend.layouts.app')

@section('content')
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel"
            data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">

                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/passenger_car.jpg') }}" />
                        <source media="(min-width: 700px)" srcset="{{ asset('front-assets/images/passenger_car.jpg') }}" />
                        <img style="width:100%; height:100%" src="{{ asset('front-assets/images/passenger_car.jpg') }}"
                            alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Passenger Car</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo
                                stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('frontend.shop') }}">Comprar
                                Ahora</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">

                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/SUV-CUV.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/SUV-CUV.jpg') }}" />
                        <image style="width:100%; height:100%" src="{{ asset('front-assets/images/SUV-CUV.jpg') }}" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">SUV / 4x4</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo
                                stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('frontend.shop') }}">Comprar
                                Ahora</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">

                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/dynapro_at2.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/dynapro_at2.jpg') }}" />
                        <img style="width:100%; height:100%" src="{{ asset('front-assets/images/dynapro_at2.jpg') }}" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">DYNAPRO AT2</h1>
                            <p class="mx-md-5 px-5">Perfectly balanced tire for both on and off road</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('frontend.shop') }}">Comprar
                                Ahora</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>
    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Producto de Calidad</h5>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Envío Gratis</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">14 días de Devolución</h2>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Soporte 24/7</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Categorías</h2>
            </div>
            <div class="row pb-3">

                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <div class="col-lg-3">
                            <div class="cat-card">
                                <div class="left">
                                    @if ($category->image != '')
                                        <img src="{{ asset('uploads/category/' . $category->image) }}" alt=""
                                            class="img-fluid">
                                    @endif
                                </div>
                                <div class="right">
                                    <div class="cat-data">
                                        <a class="dropdown-item nav-link"
                                            href="{{ route('frontend.shop', [$category->slug]) }}">
                                            <h2>{{ $category->name }}</h2>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Productos Destacados</h2>
            </div>
            <div class="row pb-3">
                @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $product)
                        @php
                            $productImage = $product->products_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">

                                    <a href="{{ route('frontend.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('/uploads/product/small/' . $productImage->image) }}">
                                        @else
                                            <img src="{{ asset('/admin-assets/img/default-150x150.png') }}">
                                        @endif

                                    </a>

                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0)"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Agotado
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link"
                                        href="{{ route('frontend.product', $product->slug) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">

                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span
                                                class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Últimos Productos</h2>
            </div>
            <div class="row pb-3">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->products_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">

                                    <a href="{{ route('frontend.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('/uploads/product/small/' . $productImage->image) }}">
                                        @else
                                            <img src="{{ asset('/admin-assets/img/default-150x150.png') }}">
                                        @endif

                                    </a>

                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0)"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Agotado
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link"
                                        href="{{ route('frontend.product', $product->slug) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">

                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span
                                                class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        function addToCart(id) {

            $.ajax({
                url: '{{ route('frontend.addToCart') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('frontend.cart') }}";
                    } else {
                        alert(response.message);
                    }
                }
            })
        }
    </script>
@endsection
