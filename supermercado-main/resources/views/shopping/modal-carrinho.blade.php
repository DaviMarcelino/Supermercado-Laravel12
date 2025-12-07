<div id="modal-carrinho" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
    <div class="bg-white w-full max-w-lg p-6 rounded shadow-lg relative max-h-[90vh] overflow-y-auto">
        <button class="absolute top-2 right-3 text-red-500 text-xl" onclick="fecharCarrinho()">✖</button>

        <h2 class="text-lg font-bold mb-4">🛒 Seu Carrinho</h2>

        <!-- Spinner -->
        <div id="loading-spinner" class="text-center py-6 hidden">
            <svg class="animate-spin h-6 w-6 text-green-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <p class="text-sm text-gray-500 mt-2">Carregando carrinho...</p>
        </div>

        <div id="conteudo-carrinho"></div>

        <!-- Botões -->
        <div class="mt-6 flex justify-end gap-3">
            <button onclick="confirmarEsvaziarCarrinho()"
                    class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium shadow-md transition">
                🗑️ Esvaziar carrinho
            </button>

            <button onclick="fecharCarrinho()"
                    class="px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium shadow-md transition">
                ❌ Fechar
            </button>

            <a href="{{ route('shopping.resumo') }}"
               class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white font-medium shadow-md transition">
                💳 Pagar
            </a>
        </div>
    </div>
</div>
