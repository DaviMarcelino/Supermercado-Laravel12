@extends('layouts.app')

@section('title', 'Cadastrar produto')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Cadastrar novo produto</h1>

        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="nome" class="block font-medium">Nome:</label>
                <input type="text" name="nome" id="nome" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="descricao" class="block font-medium">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"></textarea>
            </div>

            <div class="mb-4">
                <label for="preco" class="block font-medium">Preço:</label>
                <input type="number" name="preco" id="preco" step="0.01" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="estoque" class="block font-medium">Estoque:</label>
                <input type="number" name="estoque" id="estoque" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label for="imagem" class="block font-medium">Imagem:</label>
                <input type="file" name="imagem" id="imagem" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-blue-300">
                <p class="text-sm text-gray-500 mt-1">Formatos: JPG, JPEG, PNG, WEBP (máx. 2MB, 500x500px)</p>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('produtos.index') }}" class="text-blue-600 hover:underline">← Cancelar</a>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Salvar
                </button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputImagem = document.getElementById('imagem');

            inputImagem.addEventListener('change', function () {
                const arquivo = this.files[0];

                if (arquivo && arquivo.size > 2 * 1024 * 1024) { // 2MB
                    alert('A imagem não deve ultrapassar 2 MB. Selecione uma imagem mais leve.');
                    this.value = '';
                }
            });
        });
    </script>
@endsection