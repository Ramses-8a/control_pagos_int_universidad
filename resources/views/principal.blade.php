<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuestros servicios</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900 flex flex-col min-h-screen">

    <header class="bg-white shadow-md w-full">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                
                <div>
                    <a href="/" class="text-xl font-bold text-gray-900"></a>
                </div>

                <div class="flex items-center space-x-4">
                    <button id="openModalBtn" 
                            class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition-colors duration-300">
                        Contáctanos
                    </button>

                    <a href="{{ route('login') }}" 
                       class="text-sm font-medium text-gray-400 hover:text-gray-600 hover:underline transition-colors duration-300">
                        Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-16 flex-grow">
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
                <div class="bg-white rounded-xl shadow-md p-10 max-w-lg mx-auto text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay servicios por el momento</h3>
                    <p class="text-center text-gray-500 text-base">
                        Estamos trabajando en ello. Vuelve pronto.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($serviciosDisponibles as $servicio)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            
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
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-white border-t">
        <div class="container mx-auto px-6 py-6">
            <p class="text-gray-600 text-sm text-center">
                &copy; Todos los derechos reservados {{ date('Y') }}
            </p>
        </div>
    </footer>


    <div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg relative">
            
            <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Solicita más información</h2>
                
                <form method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre completo:</label>
                        <input type="text" name="nombre" id="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required maxlength="100">
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700">Correo electrónico:</label>
                        <input type="email" name="correo" id="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required maxlength="255">
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono:</label>
                        <input type="tel" name="telefono" id="telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required maxlength="10">
                    </div>

                    <div>
                        <label for="servicio" class="block text-sm font-medium text-gray-700">Servicio de interés:</label>
                        <select name="servicio_id" id="servicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="" disabled selected>Selecciona un servicio</option>
                            
                            @if(isset($serviciosDisponibles) && !$serviciosDisponibles->isEmpty())
                                @foreach($serviciosDisponibles as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                @endforeach
                            @else
                                <option value="0">Interés general</option>
                            @endif
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Enviar información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </body>
</html>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('contactModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    const openModal = () => {
        if (modal) modal.classList.remove('hidden');
    };

    const closeModal = () => {
        if (modal) modal.classList.add('hidden');
    };

    if (openBtn) {
        openBtn.addEventListener('click', openModal);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    }

});
</script>