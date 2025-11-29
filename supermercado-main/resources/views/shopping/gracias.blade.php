@extends('layouts.app')

@section('title', 'Gracias por tu compra')

@section('content')
<div class="max-w-xl mx-auto text-center bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">🎉 ¡Gracias por tu compra!</h2>
    <p class="mb-4 text-gray-600">Tu pedido ha sido registrado correctamente.</p>

    @if(isset($filename))
    <a href="{{ route('boleta.descargar', $filename) }}"

           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
            📄 Descargar Boleta
        </a>
    @endif

    <a href="{{ route('productos.index') }}"
       class="mt-4 block text-blue-500 hover:underline">← Volver a productos</a>
</div>
@endsection
