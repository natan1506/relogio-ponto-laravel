<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Relógio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A project management Bootstrap theme by Medium Rare">
    <link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Gothic+A1" rel="stylesheet">
    {{-- <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css"/> --}}
    <link href="/assets/css/custom.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/assets/css/theme.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/assets/css/fontawesome/css/all.css" rel="stylesheet" type="text/css"/>


    <style>
        .pointer {
            cursor: pointer;
        }
        .navbar.navbar-transparent{
            border-radius: 0.25rem !important;
        }
        .navbar .navbar-nav .nav-item .nav-link{
            color: inherit;
        }
        .navbar.navbar-transparent .navbar-brand{
            color: rgba(41, 44, 44, 1);
        }
        label{
            margin-top: 0.5rem;
        }
        .fa-user-astronaut{
            font-size: 1.6rem;
            transition: 0.3s;
        }
        .fa-user-astronaut:hover{
            transition: 0.3s;
        }
        .dropdown-item .fal{
            font-size: 1rem;
            margin-right: 0.98rem;
        }

        @media(max-width: 1199px){
            .dropdown-menu .dropdown-item{
                font-size: 20px;
                padding-bottom: 20px;
            }
            .nav-link{
                font-size: 20px;
            }
            .dropdown-item .fal{
                font-size: 20px;
                margin-right: 0.98rem;
            }
        }
        .btn-circle.btn-sm {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            font-size: 8px;
            text-align: center;
        }
        .btn-circle.btn-md {
            width: 50px;
            height: 50px;
            padding: 7px 10px;
            border-radius: 25px;
            font-size: 10px;
            text-align: center;
        }
        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            border-radius: 35px;
            font-size: 12px;
            text-align: center;
        }

    </style>
</head>

<body>


    <div class="navbar navbar-expand-lg border-0 bg-white sticky-top shadow rounded"> <!-- Div para manter a nav-bar de fora a fora no navegador -->
        <div class="container">

            <a class="navbar-brand" href="/home">
                <h4 class=" mb-0"><i class="fal fa-clock fa-lg"></i> relógio ponto</h4>
            </a>

            <div class="d-flex align-items-center">

                <a class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </a>

                <div class="d-block d-lg-none ml-2">

                    <div class="dropdown">

                        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fal fa-user-astronaut"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" style="font-size: 0.9rem;">
                            <span class="dropdown-item" style="color: inherit;">
                               @if (auth()->check()) {{ Auth::user()->nome }} @endif
                            </span>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item" style="color: inherit;">sair</a>
                        </div>

                    </div>
                </div>

            </div>

            <div class="collapse navbar-collapse justify-content-between" id="navbar-collapse">

                <ul class="navbar-nav">

                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="nav-link mr-1 {{ (Request::segment(1)) == 'pontos' ? "font-weight-bolder" : "" }}"
                            role="button" href="/pontos">relatórios</a>

                        </div>
                    </li>

                    @can('full', \App\AcessoFull::class)
                        <li class="nav-item">
                            <a  class="nav-link mr-1 {{ (Request::segment(1)) == 'feriados' ? "font-weight-bolder" : "" }}" href="/feriados">feriados</a>
                        </li>

                        <li class="nav-item">
                            <a  class="nav-link mr-1 {{ (Request::segment(1)) == 'ferias-coletiva' ? "font-weight-bolder" : "" }}" href="/ferias-coletiva">coletivas</a>
                        </li>

                        <li class="nav-item">
                            <a  class="nav-link mr-1 {{ (Request::segment(1)) == 'users' ? "font-weight-bolder" : "" }}" href="/users">usuários</a>
                        </li>
                    @endcan
                </ul>

                <div class="d-lg-flex align-items-center">

                    <div class="d-none d-lg-block ml-3">
                        <div class="dropdown">

                            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-user-astronaut"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <span class="dropdown-item" style="color: inherit;">
                                @if (auth()->check()) {{ Auth::user()->nome }} @endif
                                </span>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="dropdown-item" style="color: inherit;">sair</a>
                                <div id="check" class="theme-switch-wrapper">
                                    <label class="theme-switch" for="checkbox">
                                        <input type="checkbox" id="checkbox"/>
                                        <div id="before"></div>
                                        <div id="after"></div>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

  <div class="container pt-3">
    @yield('content')
  </div>
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.mask.js"></script>
    <script type="text/javascript" src="/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.js"></script>

    <!-- Optional Vendor Scripts (Remove the plugin script here and comment initializer script out of index.js if site does not use that feature) -->

    <!-- Autosize - resizes textarea inputs as user types -->
    <script type="text/javascript" src="/assets/js/autosize.min.js"></script>
    <!-- Prism - displays formatted code boxes -->
    <script type="text/javascript" src="/assets/js/prism.js"></script>
    <!-- Shopify Draggable - drag, drop and sort items on page -->
    <script type="text/javascript" src="/assets/js/draggable.bundle.legacy.js"></script>
    <script type="text/javascript" src="/assets/js/swap-animation.js"></script>
    <!-- Dropzone - drag and drop files onto the page for uploading -->
    <script type="text/javascript" src="/assets/js/dropzone.min.js"></script>
    <!-- List.js - filter list elements -->
    <script type="text/javascript" src="/assets/js/list.min.js"></script>
    <!-- Required theme scripts (Do not remove) -->
    <script type="text/javascript" src="/assets/js/theme.js"></script>
    <script src="/js/bootstrap.js" type="text/js"></script>
    <script type="text/javascript" src="/assets/js/custom.js"></script>
    <script src="/assets/js/ponto.js"></script>


    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');

        function switchTheme(e) {
            if (e.target.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
            console.log(localStorage);
        }

        toggleSwitch.addEventListener('change', switchTheme, false);

        const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

        if (currentTheme) {
            document.documentElement.setAttribute('data-theme', currentTheme);
            if (currentTheme === 'dark') {
                toggleSwitch.checked = true;
            }
        }
    </script>

</body>
</html>
