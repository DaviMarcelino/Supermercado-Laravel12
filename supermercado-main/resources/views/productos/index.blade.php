@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
<div class="px-4 md:px-0"> {{-- Padding lateral en móvil --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Gestión de Productos</h2>
        <a href="{{ route('productos.create') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition w-auto text-sm sm:text-base">
            ➕ Agregar Producto
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($productos->isEmpty())
        <p class="text-center text-gray-500 text-sm sm:text-base">No hay productos disponibles.</p>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($productos as $producto)
            <div class="relative bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                {{-- Botón Eliminar (esquina superior derecha) --}}
                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                      onsubmit="return confirm('¿Confirma eliminar este producto?')"
                      class="absolute top-2 right-2 z-10">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Eliminar"
                            class="text-red-500 hover:text-red-700 text-lg font-bold leading-none">
                        ✖
                    </button>
                </form>

                <div class="p-2">
                    {{-- Imagen --}}
                    @if ($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}"
                             class="w-full h-36 object-contain mx-auto mb-2" />
                    @else
                        <div class="w-full h-36 bg-gray-100 flex items-center justify-center text-gray-400">
                            Sin imagen
                        </div>
                    @endif

                    {{-- Detalles --}}
                    <div class="text-center">
                        <h3 class="text-sm font-semibold truncate">{{ $producto->nombre }}</h3>
                        <p class="text-xs text-gray-500">Stock: {{ $producto->stock }}</p>
                        <p class="text-red-600 font-bold text-sm">S/ {{ number_format($producto->precio, 2) }}</p>
                    </div>

                    {{-- Botones inferiores --}}
                    <div class="mt-4 flex flex-col gap-2">
                        <a href="javascript:void(0);" onclick="agregarAlCarrito({{ $producto->id }})"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-xs sm:text-sm transition text-center w-full">
                            🛒 Agregar al carrito
                        </a>

                        <a href="{{ route('productos.edit', $producto->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded text-xs sm:text-sm transition text-center w-full">
                            ✏️ Editar
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
