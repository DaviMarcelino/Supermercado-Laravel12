@extends('layouts.app')

@section('title', 'Editar produto')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Editar produto</h1>

    <form action="{{ route('produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
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

        @method('PUT')

        <div class="mb-4">
            <label for="nome" class="block font-medium">Nome:</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="preco" class="block font-medium">Preço:</label>
            <input type="number" name="preco" id="preco" step="0.01" value="{{ old('preco', $produto->preco) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="stock" class="block font-medium">Estoque:</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $produto->stock) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="imagem" class="block font-medium">Imagem do produto:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-blue-300">

            @if ($produto->imagem)
                <div class="w-full h-40 flex items-center justify-center bg-gray-100 rounded overflow-hidden mb-2">
                    <img src="{{ asset($produto->imagem) }}"
                         alt="{{ $produto->nome }}"
                         class="w-full max-w-[500px] max-h-[500px] object-contain mx-auto rounded shadow" />
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('produtos.index') }}" class="text-blue-600 hover:underline">← Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Atualizar
            </button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputimagem = document.getElementById('imagem');

        inputimagem.addEventListener('change', function () {
            const archivo = this.files[0];

            if (archivo && archivo.size > 2 * 1024 * 1024) { // 2 MB
                Swal.fire({
                    icon: 'warning',
                    title: 'Arquivo muito grande',
                    text: 'A imagem não deve ultrapassar 2 MB. Selecione uma imagem mais leve.',
                    confirmButtonColor: '#3085d6'
                });
                this.value = ''; // limpa o campo para evitar o envio
            }
        });
    });
</script>
@endsection