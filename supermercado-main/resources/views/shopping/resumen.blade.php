@extends('layouts.app')

@section('title', 'Resumen de la Compra')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">🧾 Resumen de tu Compra</h2>

        @if (!empty($carrito))
            <table class="w-full text-left border-t border-gray-200">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="p-2">Imagen</th>
                        <th class="p-2">Producto</th>
                        <th class="p-2 text-center">Cantidad</th>
                        <th class="p-2 text-right">Precio</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($carrito as $id => $item)
                        <tr>
                            <td class="p-2">
                                <img src="{{ asset(App\Models\Producto::find($id)?->imagen ?? 'images/default-product.png') }}"
                                     class="w-16 h-16 object-contain rounded border">
                            </td>
                            <td class="p-2">{{ $item['nombre'] }}</td>
                            <td class="p-2 text-center">{{ $item['cantidad'] }}</td>
                            <td class="p-2 text-right">S/ {{ number_format($item['precio'], 2) }}</td>
                            <td class="p-2 text-right">S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                $subtotal = collect($carrito)->reduce(fn($s, $item) => $s + $item['precio'] * $item['cantidad'], 0);
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;
            @endphp

            <div class="text-right mt-4 space-y-1">
                <p class="text-gray-600">Subtotal: <span class="font-semibold">S/ {{ number_format($subtotal, 2) }}</span></p>
                <p class="text-gray-600">IGV (18%): <span class="font-semibold">S/ {{ number_format($igv, 2) }}</span></p>
                <p class="text-xl font-bold text-blue-700">Total: S/ {{ number_format($total, 2) }}</p>
            </div>

            <div class="mt-6 text-right">
            <a href="{{ route('shopping.boleta') }}"
   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition shadow">
    Generar Boleta
</a>

            </div>
        @else
            <p class="text-center text-gray-500">Tu carrito está vacío.</p>
        @endif
    </div>
@endsection