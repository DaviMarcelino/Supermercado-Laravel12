@extends('layouts.app')

@section('title', 'Resumo da compra')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md text-gray-800 space-y-4">
    <h2 class="text-2xl font-semibold text-blue-600">Resumo da sua compra</h2>

    @php
        $total = collect($carrinho)->reduce(fn($s, $item) => $s + $item['preco'] * $item['quantidade'], 0);
    @endphp

    <div class="divide-y">
        @foreach($carrinho as $item)
            <div class="py-2 flex justify-between">
                <div>
                    <p class="font-semibold">{{ $item['nome'] }}</p>
                    <p class="text-sm text-gray-500">R$ {{ number_format($item['preco'], 2, ',', '.') }} x {{ $item['quantidade'] }}</p>
                </div>
                <p class="text-sm font-medium">R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    <div class="border-t pt-4 text-right space-y-1 text-sm">
        <p class="text-lg font-bold text-blue-700">Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
    </div>

    <form action="{{ route('carrinho.confirmar') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" required class="w-full border rounded px-3 py-2" placeholder="E-mail">
        
        <div class="flex justify-end gap-4">
            <button type="submit"
                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">
                Confirmar e enviar nota
            </button>
            <a href="#" onclick="window.print()" 
               class="bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600">
                🖨️ Imprimir
            </a>
        </div>
    </form>
</div>
@endsection