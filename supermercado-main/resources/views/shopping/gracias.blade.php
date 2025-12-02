@extends('layouts.app')

@section('title', 'Obrigado pela sua compra')

@section('content')
<div class="max-w-xl mx-auto text-center bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">🎉 Obrigado pela sua compra!</h2>
    <p class="mb-4 text-gray-600">Seu pedido foi registrado com sucesso.</p>

    @if(isset($filename))
    <a href="{{ route('descarregar.recibo', $filename) }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
            📄 Baixar Nota Fiscal
        </a>
    @endif

    <a href="{{ route('produtos.index') }}"
       class="mt-4 block text-blue-500 hover:underline">← Voltar aos produtos</a>
</div>
@endsection