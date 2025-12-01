@if (!empty($carrito))
    @foreach ($carrito as $id => $item)
        <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-4 mb-3 border border-blue-100">
            <img src="{{ asset(App\Models\Producto::find($id)?->imagen ?? 'images/default-product.png') }}"
                 alt="Produto"
                 class="w-16 h-16 object-contain rounded border border-gray-200">

            <div class="flex-1 mx-4">
                <p class="font-semibold text-blue-700">{{ $item['nombre'] }}</p>
                <p class="text-sm text-gray-500">R$ {{ number_format($item['precio'], 2, ',', '.') }} x {{ $item['cantidad'] }}</p>

                <div class="flex items-center gap-2 mt-2">
                    <button onclick="cambiarCantidad(event, {{ $id }}, -1)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 text-xl font-bold">−</button>

                    <span class="text-sm font-medium w-6 text-center">{{ $item['cantidad'] }}</span>

                    <button onclick="cambiarCantidad(event, {{ $id }}, 1)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 text-xl font-bold">+</button>
                </div>
            </div>

            <button onclick="eliminarDelCarrito({{ $id }})"
                    class="text-gray-400 hover:text-red-600 text-xl" title="Remover">
                🗑️
            </button>
        </div>
    @endforeach

    @php
        $subtotal = collect($carrito)->reduce(fn($s, $item) => $s + $item['precio'] * $item['cantidad'], 0);
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;
    @endphp

    <div class="bg-blue-50 rounded-md p-4 mt-4 text-sm text-gray-700 space-y-1 border border-blue-200">
        <p>
            Você tem <span class="font-semibold text-blue-600">{{ count($carrito) }}</span> produto{{ count($carrito) > 1 ? 's' : '' }} e
            <span class="font-semibold text-blue-600">{{ collect($carrito)->sum('cantidad') }}</span> unidade{{ collect($carrito)->sum('cantidad') > 1 ? 's' : '' }} no total.
        </p>
        <p>Subtotal: <span class="font-semibold">R$ {{ number_format($subtotal, 2, ',', '.') }}</span></p>
        <p>Impostos (18%): <span class="font-semibold">R$ {{ number_format($igv, 2, ',', '.') }}</span></p>
        <p class="text-lg font-bold text-blue-800">Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
    </div>
    
@else
    <p class="text-center text-gray-500 py-6">Seu carrinho está vazio.</p>
@endif