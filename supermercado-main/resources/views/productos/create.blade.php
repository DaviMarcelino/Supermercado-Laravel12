@extends('layouts.app')

@section('title', 'Registrar producto')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Registrar nuevo producto</h1>

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">


            @csrf
            @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-300">
        <strong>⚠️ Errores encontrados:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


            <div>
                <label for="nombre" class="block font-medium">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="precio" class="block font-medium">Precio:</label>
                <input type="number" name="precio" id="precio" step="0.01" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="stock" class="block font-medium">Estoque:</label>
                <input type="number" name="stock" id="stock" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="imagen" class="block font-medium">Imagem:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('productos.index') }}" class="text-blue-600 hover:underline">← Cancelar</a>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputImagen = document.getElementById('imagen');

        inputImagen.addEventListener('change', function () {
            const archivo = this.files[0];

            if (archivo && archivo.size > 2 * 1024 * 1024) { // 2MB
                alert('La imagen no debe superar los 2 MB. Selecciona una imagen más liviana.');
                this.value = ''; // limpia el campo para evitar el envío
            }
        });
    });
</script>
    @endsection
