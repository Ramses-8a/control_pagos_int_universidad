<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('css/AppBlade.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="main-container">
        <div class="sidebar">
            <ul class="sidebar-nav">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li><a href="#"><i class="fas fa-users"></i> Clientes</a></li>
                <li class="{{ request()->routeIs('proyectos.*') ? 'active' : '' }}">
                <a href="{{ route('proyectos.index') }}"><i class="fas fa-project-diagram"></i> Proyectos</a>
                </li>
                <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reportes</a>
                </li>
                <li><a href="#"><i class="fas fa-user-tie"></i> Empleados</a></li>
                <li><a href="#"><i class="fas fa-book"></i> Catálogo</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Configuración</a></li>

                <li class="profile-item" x-data="{ open: false }">
                    <a href="#" @click="open = !open" class="profile-link">
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-user"></i>
                            <span style="margin-left: 8px;">{{ Auth::user()->name }}</span>
                        </div>
                        <svg style="height: 1rem; width: 1rem; fill: currentColor; transition: transform 0.2s;" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <ul x-show="open" class="profile-submenu" style="display: none;" x-transition>
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle"></i> {{ __('Profile') }}
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
                </ul>
        </div>
        
        <div class="content">
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>