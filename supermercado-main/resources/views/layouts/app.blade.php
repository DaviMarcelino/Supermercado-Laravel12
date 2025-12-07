<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Supermercado')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ carrinhoAberto: false, menuAberto: false }"
    x-bind:class="{ 'overflow-hidden': menuAberto }" 
    class="bg-green-50 text-gray-800"
>
<div 
    x-show="menuAberto"
    x-transition.opacity
    class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-md z-40"
    @click="menuAberto = false"
    style="display: none;"
></div>
<header class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 shadow-md">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <button @click="menuAberto = true" class="text-2xl focus:outline-none">☰</button>
            <span class="text-xl font-bold flex items-center gap-2">🛒 Supermercado</span>
        </div>
        <nav class="flex items-center gap-6 text-sm">
            <a href="{{ route('produtos.index') }}" class="hover:underline flex items-center gap-1">
                📦 Produtos
            </a>
            <a href="#" onclick="abrirCarrinho()" class="hover:underline flex items-center gap-1 cursor-pointer">
                🛒 Carrinho
            </a>
            @auth
            <button @click="menuAberto = true" class="flex items-center gap-1 hover:underline focus:outline-none">
                <span>👤</span>
                <span>{{ auth()->user()->name ?? 'Usuário' }}</span>
            </button>
            @else
            <a href="{{ route('login') }}" class="hover:underline flex items-center gap-1">👤 Entrar</a>
            @endauth
        </nav>
    </div>
</header>
<main class="container mx-auto px-6 py-6">
    @yield('content')
</main>
<footer class="bg-green-700 text-white text-center py-4">
    &copy; {{ date('Y') }} Supermercado | Todos os direitos reservados
</footer>
@include('shopping.modal-carrinho')
<div id="spinner-global" class="hidden fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center">
    <div class="bg-green-50 p-4 rounded-full shadow-lg">
        <svg class="animate-spin h-10 w-10 text-green-500"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" 
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" 
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    </div>
</div>
<div id="modal-confirmacao" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-green-50 p-6 rounded-lg shadow-lg w-full max-w-sm text-center space-y-4 
                transform transition-transform duration-300 scale-95">
        <p class="text-lg font-semibold text-gray-800">
            Tem certeza que deseja esvaziar o carrinho?
        </p>
        <div class="flex justify-center gap-4">
            <button id="confirmar-esvaziar-btn"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">
                Sim, esvaziar
            </button>
            <button onclick="fecharModalConfirmacao()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold">
                Cancelar
            </button>
        </div>
    </div>
</div>
<div 
    x-show="menuAberto" 
    @click.outside="menuAberto = false"
    x-transition:enter="transition transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full scale-95 opacity-0"
    x-transition:enter-end="translate-x-0 scale-100 opacity-100"
    x-transition:leave="transition transform ease-in duration-300"
    x-transition:leave-start="translate-x-0 scale-100 opacity-100"
    x-transition:leave-end="-translate-x-full scale-95 opacity-0"
    class="fixed inset-y-0 left-0 w-64 bg-green-50 shadow-2xl z-50 p-6 space-y-4 flex flex-col rounded-r-2xl"
    style="display: none;"
>
    <button @click="menuAberto = false" 
            class="text-right text-gray-500 hover:text-gray-800 mb-4">✖ Fechar</button>
    @auth
    <div class="mb-4 p-3 bg-green-100 rounded-lg">
        <p class="text-lg font-semibold text-green-800">{{ auth()->user()->name }}</p>
        <p class="text-sm text-green-700">{{ auth()->user()->email }}</p>
    </div>
    @endauth
    <div class="space-y-2">
        <a href="{{ route('produtos.index') }}" 
           @click="menuAberto = false"
           class="block text-green-700 hover:bg-green-100 hover:text-green-800 
                  font-semibold p-3 rounded-lg text-lg flex items-center gap-3">
            <span class="text-xl">📦</span> Produtos
        </a>
        <a href="#" onclick="abrirCarrinho(); menuAberto = false;" 
           class="block text-green-700 hover:bg-green-100 hover:text-green-800 
                  font-semibold p-3 rounded-lg text-lg flex items-center gap-3 cursor-pointer">
            <span class="text-xl">🛒</span> Carrinho
        </a>
    </div>
    @auth
    <div class="mt-6 pt-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                @click="menuAberto = false"
                class="w-full text-left text-red-600 hover:bg-red-50 hover:text-red-700 
                       font-semibold p-3 rounded-lg text-lg flex items-center gap-3">
                <span class="text-xl">🚪</span> Sair
            </button>
        </form>
    </div>
    @else
    <div class="mt-6 pt-4 border-t border-gray-200">
        <a href="{{ route('login') }}"
           @click="menuAberto = false"
           class="block text-green-700 hover:bg-green-100 hover:text-green-800 
                  font-semibold p-3 rounded-lg text-lg flex items-center gap-3">
            <span class="text-xl">👤</span> Entrar
        </a>
    </div>
    @endauth
</div>
</body>
</html>