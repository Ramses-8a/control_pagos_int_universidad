<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-6">Editar tipo de servicio: {{ $tipoServicio->nombre }}</h1>

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

                    <form action="{{ route('tipo_servicios.update', $tipoServicio) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre del tipo de servicio:</label>
                            <input type="text" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $tipoServicio->nombre) }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="estatus" class="block font-medium text-sm text-gray-700">Estatus</label>
                            <select class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    id="estatus" name="estatus" required>
                                <option value="activo" {{ old('estatus', $tipoServicio->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estatus', $tipoServicio->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                                Actualizar
                            </button>
                            <a href="{{ route('tipo_servicios.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>