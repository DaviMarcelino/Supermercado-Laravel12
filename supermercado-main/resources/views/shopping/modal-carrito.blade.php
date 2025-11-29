<div id="modal-carrito" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
<div class="bg-white w-full max-w-lg p-6 rounded shadow-lg relative max-h-[90vh] overflow-y-auto">

        <button class="absolute top-2 right-3 text-red-500 text-xl" onclick="cerrarCarrito()">✖</button>
        <h2 class="text-lg font-bold mb-4">🛒 Tu Carrito</h2>

        <!-- Spinner mientras carga el carrito -->
        <div id="loading-spinner" class="text-center py-6 hidden">
            <svg class="animate-spin h-6 w-6 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <p class="text-sm text-gray-500 mt-2">Cargando carrito...</p>
        </div>

        <!-- Contenido del carrito -->
        <div id="contenido-carrito">
            @if (!empty($carrito))
                @foreach ($carrito as $id => $item)
                    <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-4 mb-3 border border-blue-100">
                        <img src="{{ asset(App\Models\Producto::find($id)?->imagen ?? 'images/default-product.png') }}"
                             alt="Producto"
                             class="w-16 h-16 object-contain rounded border border-gray-200">
                        <div class="flex-1 mx-4">
                            <p class="font-semibold text-blue-700">{{ $item['nombre'] }}</p>
                            <p class="text-sm text-gray-500">S/ {{ number_format($item['precio'], 2) }} x {{ $item['cantidad'] }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button onclick="cambiarCantidad(event, {{ $id }}, -1)"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 text-xl font-bold">−</button>
                                <span class="text-sm font-medium w-6 text-center">{{ $item['cantidad'] }}</span>
                                <button onclick="cambiarCantidad(event, {{ $id }}, 1)"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 text-xl font-bold">+</button>
                            </div>
                        </div>
                        <button onclick="eliminarDelCarrito({{ $id }})"
                                class="text-gray-400 hover:text-red-600 text-xl" title="Eliminar">
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
                        Tienes <span class="font-semibold text-blue-600">{{ count($carrito) }}</span> producto{{ count($carrito) > 1 ? 's' : '' }} y
                        <span class="font-semibold text-blue-600">{{ collect($carrito)->sum('cantidad') }}</span> unidad{{ collect($carrito)->sum('cantidad') > 1 ? 'es' : '' }} en total.
                    </p>
                    <p>Subtotal: <span class="font-semibold">S/ {{ number_format($subtotal, 2) }}</span></p>
                    <p>IGV (18%): <span class="font-semibold">S/ {{ number_format($igv, 2) }}</span></p>
                    <p class="text-lg font-bold text-blue-800">Total: S/ {{ number_format($total, 2) }}</p>
                </div>
            @else
                <p class="text-center text-gray-500 py-6">Tu carrito está vacío.</p>
            @endif
        </div>

        <!-- Botones finales -->
        <div class="mt-6 flex justify-end gap-3">
            <button onclick="confirmarVaciarCarrito()"
                    class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium shadow-md transition">
                🗑️ Vaciar carrito
            </button>

            <button onclick="cerrarCarrito()"
                    class="px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium shadow-md transition">
                ❌ Cerrar
            </button>

            <a href="{{ route('shopping.resumen') }}"
               class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium shadow-md transition">
                💳 Ir a pagar
            </a>
        </div>
    </div>
</div>
