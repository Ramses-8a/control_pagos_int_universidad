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
    <div class="main-container" x-data="{ sidebarOpen: true }">
        
        <div class="sidebar" :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'">
            <!-- Botón de hamburguesa movido al inicio del sidebar --><div class="sidebar-header">
                <button @click="sidebarOpen = !sidebarOpen" class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="sidebar-nav">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> <span>Inicio</span></a>
                </li>
                {{-- Proyectos Dropdown --}}
                <li class="dropdown-item {{ request()->routeIs('proyectos.*') ? 'active' : '' }}" x-data="{ open: false }">
                    <a href="#" @click="open = !open" class="dropdown-link">
                        <i class="fas fa-project-diagram"></i>
                        <span>Proyectos</span>
                        <svg class="dropdown-arrow" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul x-show="open" class="submenu" style="display: none;" x-transition>
                        <li>
                            <a href="{{ route('proyectos.index') }}">
                                <i class="fas fa-list-alt"></i> <span>Ver Proyectos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('proyectos.create') }}">
                                <i class="fas fa-plus"></i> <span>Crear Proyecto</span>
                            </a>
                        </li>
                    </ul>

                <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> <span>Reportes</span></a>
                </li>

                {{-- Empleados Dropdown --}}
                <li class="dropdown-item" x-data="{ open: false }">
                    <!-- Eliminamos el <span> extra del título aquí, el nombre ya está en <a> --><a href="#" @click="open = !open" class="dropdown-link">
                        <i class="fas fa-user-tie"></i>
                        <span>Empleados</span> 
                        <svg class="dropdown-arrow" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul x-show="open" class="submenu" style="display: none;" x-transition>
                        <li>
                            <a href="{{ route('empleados.create') }}">
                                <i class="fas fa-user-plus"></i> <span>Registrar empleado</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('empleados.lista') }}">
                                <i class="fas fa-users"></i> <span>Lista de empleados</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('puestos.crear') }}">
                                <i class="fas fa-briefcase"></i> <span>Registrar puesto</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('puestos.lista') }}">
                                <i class="fas fa-list"></i> <span>Lista de puestos</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown-item" x-data="{ open: false }">
        <!-- Eliminamos el <span> extra del título aquí, el nombre ya está en <a> --><a href="#" @click="open = !open" class="dropdown-link">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Nómina</span> 
                        <svg class="dropdown-arrow" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                        <ul x-show="open" class="profile-submenu" style="display: none;" x-transition>
                            <li>
                                <a href="{{ route('pagos.create') }}">
                                    <i class="fas fa-user-plus"></i> Registrar pago
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pagos.lista') }}">
                                    <i class="fas fa-users"></i> Lista de pagos
                                </a>
                            </li>
                         
                           
                          
                        </ul>
                    </li>

                {{-- Tareas Dropdown --}}
                <li class="dropdown-item" x-data="{ open: false }">
                    <a href="#" @click="open = !open" class="dropdown-link">
                        <i class="fas fa-tasks"></i> <!-- Ícono de tareas, más apropiado --><span>Tareas</span>
                        <svg class="dropdown-arrow" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul x-show="open" class="submenu" style="display: none;" x-transition>
                        <li>
                            <a href="{{ route('tableros.index') }}">
                                <i class="fas fa-columns"></i> <span>Tablero Kanban</span> <!-- Ícono de tablero --></a>
                        </li>
                    </ul>
                </li>
                
                <li class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                    <a href="{{ route('servicios.index') }}"><i class="fas fa-project-diagram"></i> <span>Servicios</span></a>
                </li>
                
                {{-- Admin Dropdown --}}
                <li class="dropdown-item" x-data="{ open: false }">
                    <a href="#" @click="open = !open" class="dropdown-link">
                        <i class="fas fa-user-shield"></i> <!-- Ícono de admin --><span>Admin</span>
                        <svg class="dropdown-arrow" x-bind:style="{ 'transform': open ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul x-show="open" class="submenu" style="display: none;" x-transition>
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle"></i> <span>{{ __('Perfil') }}</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> <span>{{ __('Salir') }}</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="content" :class="sidebarOpen ? 'content-open' : 'content-closed'">
            {{-- Eliminamos el header principal, el botón de hamburguesa ahora está en el sidebar --}}
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>

