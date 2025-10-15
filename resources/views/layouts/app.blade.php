<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('css/AppBlade.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ open: false }">
        <div class="min-h-screen bg-gray-100 flex">
            <div class="sidebar">
                <ul>
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('clientes') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('proyectos') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-project-diagram"></i> Proyectos
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('empleados') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-user-tie"></i> Empleados
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('reportes') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('catalogo') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-book"></i> Catalogo
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('configuracion') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </li>
                    <li class="relative" x-data="{ open: false }">
                        <a href="#" @click="open = ! open" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user"></i>
                                <span class="ml-2">{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        <div x-show="open" @click.away="open = false" class="dropdown-menu absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
