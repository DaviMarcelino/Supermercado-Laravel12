@extends('layouts.app')

@section('title', 'Resumen de compra')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md text-gray-800 space-y-4">
    <h2 class="text-2xl font-semibold text-blue-600">Resumen de tu compra</h2>

    @php
        $subtotal = collect($carrito)->reduce(fn($s, $item) => $s + $item['precio'] * $item['cantidad'], 0);
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;
    @endphp

    <div class="divide-y">
        @foreach($carrito as $item)
            <div class="py-2 flex justify-between">
                <div>
                    <p class="font-semibold">{{ $item['nombre'] }}</p>
                    <p class="text-sm text-gray-500">S/ {{ number_format($item['precio'], 2) }} x {{ $item['cantidad'] }}</p>
                </div>
                <p class="text-sm font-medium">S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
            </div>
        @endforeach
    </div>

    <div class="border-t pt-4 text-right space-y-1 text-sm">
        <p>Subtotal: <span class="font-semibold">S/ {{ number_format($subtotal, 2) }}</span></p>
        <p>IGV (18%): <span class="font-semibold">S/ {{ number_format($igv, 2) }}</span></p>
        <p class="text-lg font-bold text-blue-700">Total: S/ {{ number_format($total, 2) }}</p>
    </div>

    <form action="{{ route('carrito.confirmar') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" required class="w-full border rounded px-3 py-2" placeholder="Correo electrónico">
        
        <div class="flex justify-end gap-4">
            <button type="submit"
                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">
                Confirmar y enviar boleta
            </button>
            <a href="#" onclick="window.print()" 
               class="bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600">
                🖨️ Imprimir
            </a>
        </div>
    </form>
</div>
@endsection
