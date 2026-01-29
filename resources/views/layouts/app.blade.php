<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Estilo rápido para el sidebar */
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar .nav-link { color: #c2c7d0; margin-bottom: 5px; }
        #sidebar .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        #sidebar .nav-link.active { background-color: #007bff; color: white; }
        #content { width: 100%; padding: 20px; }
        .sidebar-heading { padding: 1.5rem 1rem; font-size: 1.2rem; background: #212529; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-bottom">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fa-solid fa-hospital-user me-2"></i> {{ config('app.name', 'Laravel') }}
                </a>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle font-weight-bold" href="#" role="button" data-bs-toggle="dropdown" v-pre>
                                    <i class="fa-solid fa-circle-user me-1"></i> {{ Auth::user()->nombre ?? Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div id="wrapper">
            @auth
            <nav id="sidebar" class="bg-dark">
                <div class="sidebar-heading border-bottom">Menú Principal</div>
                <div class="p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fa-solid fa-gauge-high me-2"></i> Panel de Control
                            </a>
                        </li>
                        
                        <li class="nav-item mt-2">
                            <a class="nav-link {{ request()->is('personas*') ? 'active' : '' }}" href="{{ route('personas.index') }}">
                                <i class="fa-solid fa-users-medical me-2 text-success"></i> Gestión de Usuarios
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <a class="nav-link {{ request()->is('turnos*') ? 'active' : '' }}" href="{{ route('turnos.create') }}">
                                <i class="fa-solid fa-calendar-check me-2 text-primary"></i> Asignar Turno
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <a class="nav-link {{ request()->is('servicio*') ? 'active' : '' }}" href="{{ route('servicio.index') }}">
                                <i class="fa-solid fa-hospital me-2 text-info"></i> Servicios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('personas.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users text-success"></i>
                            <p>Gestión de Usuarios</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            @endauth

            <div id="content">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>
</html>