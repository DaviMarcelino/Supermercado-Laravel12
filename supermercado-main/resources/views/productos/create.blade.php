@extends('layouts.app')

@section('title', 'Cadastrar produto')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Cadastrar novo produto</h1>

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-300">
                    <strong>⚠️ Erros encontrados:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label for="nombre" class="block font-medium">Nome:</label>
                <input type="text" name="nombre" id="nombre" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="precio" class="block font-medium">Preço:</label>
                <input type="number" name="precio" id="precio" step="0.01" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="stock" class="block font-medium">Estoque:</label>
                <input type="number" name="stock" id="stock" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="imagen" class="block font-medium">Imagem:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('productos.index') }}" class="text-blue-600 hover:underline">← Cancelar</a>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Salvar
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
                    alert('A imagem não deve ultrapassar 2 MB. Selecione uma imagem mais leve.');
                    this.value = ''; // limpa o campo para evitar o envio
                }
            });
        });
    </script>
@endsection