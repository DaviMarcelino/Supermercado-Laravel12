<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Supermercado')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body 
    x-data="{ carritoAbierto: false, menuAbierto: false }"
    x-bind:class="{ 'overflow-hidden': menuAbierto }" 
    class="bg-gray-100 text-gray-800"
>
<!-- Fundo borrado ao abrir o sidebar -->
<div 
    x-show="menuAbierto"
    x-transition.opacity
    class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-md z-40"
     class="fixed inset-y-0 left-0 w-4/5 md:w-64 bg-white shadow-2xl z-50 p-6 space-y-4 flex flex-col rounded-r-2xl"
    @click="menuAbierto = false"
    style="display: none;"
></div>


<header class="bg-gradient-to-r from-sky-400 to-blue-600 text-white py-3 shadow-md">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <!-- Parte esquerda: Botão + Supermercado -->
        <div class="flex items-center gap-4">
            <button @click="menuAbierto = true" class="text-2xl focus:outline-none">
                ☰
            </button>
            <span class="text-xl font-bold flex items-center gap-2">🛒 Supermercado</span>
        </div>

        <!-- Parte central: Buscador -->
        <div class="hidden md:block flex-1 mx-6">
            <input type="text" placeholder="Buscar produtos"
                class="px-3 py-1 w-full rounded-full text-black focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <!-- Parte direita: Links -->
        <nav class="flex items-center gap-6 text-sm">
            <a href="{{ route('productos.index') }}" class="hover:underline flex items-center gap-1">📦 Produtos</a>
            <a href="#" @click="carritoAbierto = true; abrirCarrito()" class="hover:underline flex items-center gap-1 cursor-pointer">
                🛒 Carrinho <span id="contador-carrito" class="text-xs">{{ $carritoTotal ?? 0 }}</span>
            </a>
            <a href="{{ route('login') }}" class="hover:underline flex items-center gap-1">👤 Minha conta</a>
        </nav>
    </div>
</header>


<main class="container mx-auto px-6 py-6">
    @yield('content')
</main>

<footer class="bg-blue-700 text-white text-center py-4">
    &copy; {{ date('Y') }} Supermercado | Todos os direitos reservados
</footer>

@include('shopping.modal-carrito') {{-- se você tem o modal separado, inclua aqui --}}

<!-- Spinner global de carregamento -->
<div id="spinner-global" class="hidden fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-full shadow-lg">
        <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    </div>
</div>
<!-- Modal de confirmação para esvaziar carrinho -->
<div id="modal-confirmacao" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm text-center space-y-4 transform transition-transform duration-300 scale-95">
        <p class="text-lg font-semibold text-gray-800">Tem certeza que deseja esvaziar o carrinho?</p>
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
<!-- Sidebar deslizante -->
<div 
    x-show="menuAbierto" 
    @click.outside="menuAbierto = false"
    x-transition:enter="transition transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full scale-95 opacity-0"
    x-transition:enter-end="translate-x-0 scale-100 opacity-100"
    x-transition:leave="transition transform ease-in duration-300"
    x-transition:leave-start="translate-x-0 scale-100 opacity-100"
    x-transition:leave-end="-translate-x-full scale-95 opacity-0"
    class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl z-50 p-6 space-y-4 flex flex-col rounded-r-2xl"
    style="display: none;"
>



    <button @click="menuAbierto = false" class="text-right text-gray-500 hover:text-gray-800">
        ✖
    </button>
    <a href="#" class="text-blue-700 font-semibold hover:underline text-lg">📈 Relatórios</a>
    <a href="#" class="text-blue-700 font-semibold hover:underline text-lg">💵 Minhas Vendas</a>
    <a href="{{ route('productos.index') }}" class="text-blue-700 font-semibold hover:underline text-lg">📦 Produtos</a>
</div>


</body>
</html>