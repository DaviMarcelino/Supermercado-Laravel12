@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Editar producto</h1>

    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
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



        @method('PUT')

        <div class="mb-4">
            <label for="nombre" class="block font-medium">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="precio" class="block font-medium">Precio:</label>
            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $producto->precio) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="stock" class="block font-medium">Stock:</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="imagen" class="block font-medium">Imagen del producto:</label>
            <input type="file" name="imagen" id="imagen" accept="image/*"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-blue-300">

                @if ($producto->imagen)
    <div class="w-full h-40 flex items-center justify-center bg-gray-100 rounded overflow-hidden mb-2">
    <img src="{{ asset($producto->imagen) }}"
     alt="{{ $producto->nombre }}"
     class="w-full max-w-[500px] max-h-[500px] object-contain mx-auto rounded shadow" />

    </div>
@endif

        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('productos.index') }}" class="text-blue-600 hover:underline">← Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Actualizar
            </button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputImagen = document.getElementById('imagen');

        inputImagen.addEventListener('change', function () {
            const archivo = this.files[0];

            if (archivo && archivo.size > 2 * 1024 * 1024) { // 2 MB
                Swal.fire({
    icon: 'warning',
    title: 'Archivo demasiado grande',
    text: 'La imagen no debe superar los 2 MB. Selecciona una imagen más liviana.',
    confirmButtonColor: '#3085d6'
});

            }
        });
    });
</script>

@endsection
