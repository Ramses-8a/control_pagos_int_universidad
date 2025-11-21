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

<body class="font-sans antialiased bg-gray-100 text-gray-900 flex flex-col min-h-screen relative">

    <div class="fixed top-5 right-5 z-[60] w-full max-w-xs space-y-3 pointer-events-none">
        @if (session('success'))
            <div id="success-alert" class="pointer-events-auto flex items-center p-4 text-sm text-green-800 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-xl transition-all duration-500 transform translate-x-0 opacity-100">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-bold">¡Enviado correctamente!</p>
                    <p class="text-xs mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div id="error-alert" class="pointer-events-auto p-4 text-sm text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-xl">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-bold">Corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside ml-7 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button onclick="this.parentElement.remove()" class="text-xs text-red-600 font-semibold underline mt-3 ml-7 hover:text-red-800">
                    Entendido, cerrar
                </button>
            </div>
        @endif
    </div>
    <header class="bg-white shadow-md w-full">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div><a href="/" class="text-xl font-bold text-gray-900"></a></div>
                <div class="flex items-center space-x-4">
                    <button id="openModalBtn" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition-colors duration-300">
                        Contáctanos
                    </button>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-gray-600 hover:underline transition-colors duration-300">
                        Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-16 flex-grow">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Conoce los servicios disponibles</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explora nuestra oferta de servicios diseñados para ti.</p>
            </div>

            @if ($serviciosDisponibles->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-10 max-w-lg mx-auto text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay servicios por el momento</h3>
                    <p class="text-center text-gray-500 text-base">Estamos trabajando en ello. Vuelve pronto.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($serviciosDisponibles as $servicio)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex-grow">
                                    <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">{{ $servicio->tipoServicio->nombre }}</h3>
                                    <h2 class="text-2xl font-bold text-gray-900 mt-2 mb-3">{{ $servicio->nombre }}</h2>
                                    <p class="text-gray-700 text-base mb-4">{{ $servicio->descripcion }}</p>
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
            <p class="text-gray-600 text-sm text-center">&copy; Todos los derechos reservados {{ date('Y') }}</p>
        </div>
    </footer>

    <div id="contactModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4 hidden backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative transform transition-all scale-95" id="modalContent">
            <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors p-1 bg-gray-100 rounded-full hover:bg-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Solicita más información</h2>
                <form method="POST" action="{{ route('contacto.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" required maxlength="100" placeholder="Ej. Juan Pérez">
                    </div>
                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico <span class="text-gray-400 text-xs">(Opcional si pones teléfono)</span></label>
                        <input type="email" name="correo" id="correo" value="{{ old('correo') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" required maxlength="255" placeholder="juan@ejemplo.com">
                    </div>
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-gray-400 text-xs">(Opcional si pones correo)</span></label>
                        <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" required maxlength="10" placeholder="10 dígitos">
                    </div>
                    <div>
                        <label for="servicio" class="block text-sm font-medium text-gray-700 mb-1">Servicio de interés</label>
                        <select name="servicio_id" id="servicio" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" required>
                            <option value="" disabled selected>Selecciona una opción</option>
                            @if(isset($serviciosDisponibles) && !$serviciosDisponibles->isEmpty())
                                @foreach($serviciosDisponibles as $servicio)
                                    <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }}</option>
                                @endforeach
                            @else
                                <option value="0" {{ old('servicio_id') == '0' ? 'selected' : '' }}>Interés general</option>
                            @endif
                        </select>
                    </div>
                    <div class="pt-3">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:scale-[1.02]">
                            Enviar información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        const emailInput = document.getElementById('correo');
            const phoneInput = document.getElementById('telefono');

            const toggleContactRequirement = () => {
                if (emailInput.value.trim() !== "") {
                    phoneInput.removeAttribute('required');
                } else {
                    phoneInput.setAttribute('required', 'required');
                }

                if (phoneInput.value.trim() !== "") {
                    emailInput.removeAttribute('required');
                } else {
                    emailInput.setAttribute('required', 'required');
                }
            };

            // Escuchar cuando el usuario escribe
            if(emailInput && phoneInput) {
                emailInput.addEventListener('input', toggleContactRequirement);
                phoneInput.addEventListener('input', toggleContactRequirement);
            }

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('contactModal');
            const modalContent = document.getElementById('modalContent');
            const openBtn = document.getElementById('openModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');

            // Funciones del Modal con pequeña animación
            const openModal = () => {
                if (modal) {
                    modal.classList.remove('hidden');
                    // Timeout pequeño para que la transición de escala funcione
                    setTimeout(() => modalContent.classList.remove('scale-95'), 10);
                }
            };
            const closeModal = () => {
                if (modal) {
                    modalContent.classList.add('scale-95');
                    setTimeout(() => modal.classList.add('hidden'), 150); // Espera a que termine la animación
                }
            };

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) closeModal();
                });
            }

            @if ($errors->any())
                openModal();
            @endif

            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.remove('translate-x-0', 'opacity-100');
                    successAlert.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => successAlert.remove(), 500);
                }, 3000); 
            }
        });
    </script>
</body>
</html>