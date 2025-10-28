<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuestros servicios</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">

    <main class="py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Conoce los servicios disponibles
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Explora nuestra oferta de servicios diseñados para ti.
                </p>
            </div>
            @if ($serviciosDisponibles->isEmpty())
            <p class="text-center text-gray-500 text-lg">
                No hay servicios disponibles por el momento. Vuelve pronto.
            </p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($serviciosDisponibles as $servicio)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl">
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex-grow">
                            <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">
                                {{ $servicio->tipoServicio->nombre }}
                            </h3>
                            <h2 class="text-2xl font-bold text-gray-900 mt-2 mb-3">
                                {{ $servicio->nombre }}
                            </h2>
                            <p class="text-gray-700 text-base mb-4">
                                {{ $servicio->descripcion }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <span class="text-3xl font-extrabold text-gray-900">
                                ${{ number_format($servicio->precio, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </main>

    <footer class="bg-white border-t mt-16">
        <div class="container mx-auto px-6 py-8">
            <p class="text-center text-gray-900 text-sm mb-2">
                &copy; Todos los derechos reservados {{ date('Y') }}
            </p>
            <div class="text-right text-sm">
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-gray-600 hover:underline transition-colors duration-300">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </footer>
</body>

</html>