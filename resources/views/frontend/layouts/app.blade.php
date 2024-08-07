<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ruedas La Mundial | Tienda En Linea Oficial</title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

    <meta name="HandheldFriendly" content="True" />
    <meta name="pinterest" content="nopin" />

    <meta property="og:locale" content="en_AU" />
    <meta property="og:type" content="website" />
    <meta property="fb:admins" content="" />
    <meta property="fb:app_id" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:image:alt" content="" />

    <meta name="twitter:title" content="" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:card" content="summary_large_image" />


    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/custom.css') }}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap"
        rel="stylesheet">

    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('front-assets/images/logo-rlm.png') }}" />

    <meta name="csrf_token" content="{{ csrf_token() }}">
</head>

<body data-instant-intensity="mousedown">

    {{-- Versión Web --}}

    <div class="bg-light top-header">
        <div class="container">
            <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
                <div class="col-lg-4 logo">
                    <a href="{{ route('frontend.home') }}" class="text-decoration-none">
                        <img src="{{ asset('front-assets/images/logo-ruedas-la-mundial.png') }}" style="width: 70%">
                    </a>
                </div>
                <div class="account col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
                    @if (Auth::check())
                        <a href="{{ route('account.profile') }}" class="nav-link text-dark">Mi Cuenta</a>
                    @else
                        <a href="{{ route('account.login') }}" class="nav-link text-dark">
                            Iniciar Sesión
                        </a>
                    @endif
                    <form action="{{ route('frontend.shop') }}" method="get">
                        <div class="input-group">
                            <input value="{{ Request::get('search') }}" type="text" placeholder="Buscar Productos"
                                class="form-control" name="search" id="search">
                            <button type="submit" class="input-group-text">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Versión Web --}}

    {{-- Versión Movil --}}

    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-xl" id="navbar">
                <a href="{{ route('frontend.home') }}" class="text-decoration-none mobile-logo" style="width: 40%">
                    <img src="{{ asset('front-assets/images/logo-ruedas-la-mundial-blanco.png') }}"
                        style="margin-top: -5%; width: 80%">
                </a>
                <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="navbar-toggler-icon fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-1 mb-lg-0">

                        @if (getCategories()->isNotEmpty())
                            @foreach (getCategories() as $category)
                                <li class="nav-item dropdown">
                                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ $category->name }}
                                    </button>
                                    @if ($category->sub_category->isNotEmpty())
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            @foreach ($category->sub_category as $subCategory)
                                                <li>
                                                    <a class="dropdown-item nav-link"
                                                        href="{{ route('frontend.shop', [$category->slug, $subCategory->slug]) }}">
                                                        {{ $subCategory->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            <li class="nav-item dropdown">
                                <a href="{{ route('frontend.shop-special') }}" class="btn btn-dark"
                                    aria-expanded="false">
                                    Sección 4X4
                                </a>
                            </li>
                        @endif



                    </ul>
                </div>

                @if (Auth::check())
                    <div class="right-nav" style="padding-right: 75px">
                        <a href="{{ route('account.profile') }}" class="nav-link text-dark"><i
                                class="fa fa-user-circle text-primary"></i></a>
                    </div>
                @else
                    <div class="right-nav" style="padding-right: 75px">
                        <a href="{{ route('account.login') }}" class="nav-link text-dark">
                            <i class="fas fa-user text-primary"></i>
                        </a>
                    </div>
                @endif

                <div class="right-nav py-1" style="padding-right: 60px">
                    <a href="{{ route('frontend.shop') }}" class="d-flex pt-2">
                        <i class="fas fa-shopping-bag text-primary"></i>
                    </a>
                </div>
                <div class="right-nav py-1" style="padding-right: 30px">
                    <a href="{{ route('frontend.cart') }}" class="d-flex pt-2">
                        <i class="fas fa-shopping-cart text-primary"></i>
                    </a>
                </div>

            </nav>
        </div>
    </header>

    {{-- Versión Movil --}}

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark mt-5">
        <div class="container pb-5 pt-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Póngase en Contacto</h3>
                        <a href="mailto:mercadeo@glmundial.com"><i class="fas fa-envelope"></i>
                            mercadeo@glmundial.com</a>
                        <a href="https://maps.app.goo.gl/pKecwNQnCsQHQDAp9" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial Cecilio Acosta</a>
                        <a href="https://maps.app.goo.gl/Joqq2ap21zkqSb4BA" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial 72</a>
                        <a href="https://maps.app.goo.gl/Q52aZYrUVFa6ZcAL7" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial Delicias</a>
                        <a href="https://maps.app.goo.gl/jKCc5mG2Q4y6XWUd8" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial Sabaneta</a>
                        <a href="https://maps.app.goo.gl/rXqPkJquzg8r6yNz8" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial Sur</a>
                        <a href="https://maps.app.goo.gl/KKY5x4huwHMkpwtQ8" target="_blank"><i
                                class="fas fa-map"></i> Ruedas La Mundial Veritas</a>
                        <a href="https://api.whatsapp.com/send/?phone=584246265544" target="_blank"><i
                                class="fab fa-whatsapp"></i>
                            +58 424-6265544</a>
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Enlaces Importantes</h3>
                        <ul>
                            @if (staticPages()->isNotEmpty())
                                @foreach (staticPages() as $page)
                                    <li><a href="{{ route('frontend.page', $page->slug) }}"
                                            title="{{ $page->name }}">
                                            {{ $page->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            {{-- 
                            <li><a href="contact-us.php" title="Contact Us">Contáctanos</a></li>
                            <li><a href="#" title="Privacy">Confidencialidad</a></li>
                            <li><a href="#" title="Privacy">Términos y Condiciones</a></li>
                            <li><a href="#" title="Privacy">Política de Devoluciones</a></li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Mi Cuenta</h3>
                        <ul>
                            <li><a href="{{ route('account.register') }}" title="Sell"><i class="fas fa-user"></i>
                                    Iniciar Sesión</a></li>
                            <li><a href="{{ route('account.register') }}" title="Advertise"><i
                                        class="fas fa-user-plus"></i> Registrarse</a></li>
                            <li><a href="{{ route('account.orders') }}" title="Contact Us"><i
                                        class="fas fa-list"></i> Mis Pedidos</a></li>
                            <li><a href="{{ route('account.wishlist') }}" title="Contact Us"><i
                                        class="fas fa-heart sidebar"></i> Lista de Deseos</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="copy-right text-center">
                            <p>© Derechos de autor 2024 Grupo La Mundial. Todos los derechos reservados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Completado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>

    <script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/custom.js') }}"></script>

    <script>
        window.onscroll = function() {
            myFunction()
        };

        var navbar = document.getElementById("navbar");
        var sticky = navbar.offsetTop;

        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

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
            });
        }

        function addToWishList(id) {
            $.ajax({
                url: '{{ route('frontend.addToWishlist') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#wishlistModal .modal-body").html(response.message);
                        $("#wishlistModal").modal('show');

                    } else {
                        window.location.href = "{{ route('account.login') }}";
                        //alert(response.message);
                    }
                }
            });
        }
    </script>
    @yield('customJs')
</body>

</html>
