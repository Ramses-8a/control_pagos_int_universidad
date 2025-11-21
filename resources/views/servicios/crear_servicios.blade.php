<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-6">Crear nuevo servicio</h1>

                    @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                        <p class="font-bold">¡Ups! Hubo algunos problemas con tus datos:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('servicios.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre del servicio:</label>
                            <input type="text" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block font-medium text-sm text-gray-700">Descripción del servicio:</label>
                            <input type="text" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="costo" class="block font-medium text-sm text-gray-700">Costo <span class="text-gray-400 text-xs">(Opcional)</span>:</label>
                            <input type="number"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                id="costo" name="costo" step="0.01" min="0" value="{{ old('costo') }}"
                                placeholder="0.00"
                                oninput="checkLength(this, 'warning-costo')">
                            <p id="warning-costo" class="hidden text-red-600 text-sm mt-1">
                                ¿Estás seguro que es el costo correcto?
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="precio" class="block font-medium text-sm text-gray-700">Precio <span class="text-gray-400 text-xs">(Opcional)</span>:</label>
                            <input type="number"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                id="precio" name="precio" step="0.01" min="0" value="{{ old('precio') }}"
                                placeholder="0.00"
                                oninput="checkLength(this, 'warning-precio')">
                            <p id="warning-precio" class="hidden text-red-600 text-sm mt-1">
                                ¿Estás seguro que es el precio correcto?
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="fk_tipo_servicio" class="block font-medium text-sm text-gray-700">Tipo de servicio:</label>
                            <select class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                id="fk_tipo_servicio" name="fk_tipo_servicio" required>
                                <option value="" disabled selected>Seleccione un tipo...</option>
                                @foreach ($tipos_servicio as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('fk_tipo_servicio') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                                Guardar
                            </button>
                            <a href="{{ route('servicios.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function checkLength(input, warningId) {
        const warningElement = document.getElementById(warningId);
        // Verifica si hay valor antes de checar la longitud para evitar errores visuales
        if (input.value && input.value.length >= 6) {
            warningElement.classList.remove('hidden');
        } else {
            warningElement.classList.add('hidden');
        }
    }
</script>