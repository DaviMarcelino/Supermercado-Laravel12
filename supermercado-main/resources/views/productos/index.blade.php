{{-- resources/views/productos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestão de Produtos')

@section('content')
<div class="px-4 md:px-0">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Gestão de Produtos</h2>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('productos.create') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition w-auto text-sm sm:text-base">
                    ➕ Adicionar Produto
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-sm sm:text-base">
            ❌ {{ session('error') }}
        </div>
    @endif

    @if($productos->isEmpty())
        <p class="text-center text-gray-500 text-sm sm:text-base">Nenhum produto disponível.</p>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($productos as $producto)
            <div class="relative bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                {{-- Botão Excluir (apenas para admin) --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                              onsubmit="return confirm('Confirma a exclusão deste produto?')"
                              class="absolute top-2 right-2 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Excluir"
                                    class="text-red-500 hover:text-red-700 text-lg font-bold leading-none">
                                ✖
                            </button>
                        </form>
                    @endif
                @endauth

                <div class="p-2">
                    {{-- Imagem --}}
                    @if ($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}"
                             class="w-full h-36 object-contain mx-auto mb-2" />
                    @else
                        <div class="w-full h-36 bg-gray-100 flex items-center justify-center text-gray-400">
                            Sem imagem
                        </div>
                    @endif

                    {{-- Detalhes --}}
                    <div class="text-center">
                        <h3 class="text-sm font-semibold truncate">{{ $producto->nombre }}</h3>
                        <p class="text-xs text-gray-500">Estoque: {{ $producto->stock }}</p>
                        <p class="text-red-600 font-bold text-sm">R$ {{ number_format($producto->precio, 2, ',', '.') }}</p>
                    </div>

                    {{-- Botões inferiores --}}
                    <div class="mt-4 flex flex-col gap-2">
                        @auth
                            <a href="javascript:void(0);" onclick="agregarAlCarrito({{ $producto->id }})"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-xs sm:text-sm transition text-center w-full">
                                🛒 Adicionar ao carrinho
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('productos.edit', $producto->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded text-xs sm:text-sm transition text-center w-full">
                                    ✏️ Editar
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded text-xs sm:text-sm transition text-center w-full">
                                🔐 Fazer login para comprar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection