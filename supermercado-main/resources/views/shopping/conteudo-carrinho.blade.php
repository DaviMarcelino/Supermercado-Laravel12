@if (!empty($carrinho))
    @foreach ($carrinho as $id => $item)
        <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-4 mb-3 border border-green-100">
            <img src="{{ asset(App\Models\Produto::find($id)?->imagem ?? 'images/default-product.png') }}"
                 alt="Produto"
                 class="w-16 h-16 object-contain rounded border border-gray-200">

            <div class="flex-1 mx-4">
                <p class="font-semibold text-green-700">{{ $item['nome'] }}</p>
                <p class="text-sm text-gray-500">R$ {{ number_format($item['preco'], 2, ',', '.') }} x {{ $item['quantidade'] }}</p>

                <div class="flex items-center gap-2 mt-2">
                    <button onclick="alterarQuantidade(event, {{ $id }}, -1)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-700 hover:bg-green-200 text-xl font-bold">−</button>

                    <span class="text-sm font-medium w-6 text-center">{{ $item['quantidade'] }}</span>

                    <button onclick="alterarQuantidade(event, {{ $id }}, 1)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-700 hover:bg-green-200 text-xl font-bold">+</button>
                </div>
            </div>

            <button onclick="removerDoCarrinho({{ $id }})"
                    class="text-gray-400 hover:text-red-600 text-xl" title="Remover">
                🗑️
            </button>
        </div>
    @endforeach

    @php
        $total = collect($carrinho)->reduce(fn($s, $item) => $s + $item['preco'] * $item['quantidade'], 0);
    @endphp

    <div class="bg-green-50 rounded-md p-4 mt-4 text-sm text-gray-700 space-y-1 border border-green-200">
        <p>
            Você tem <span class="font-semibold text-green-600">{{ count($carrinho) }}</span> produto{{ count($carrinho) > 1 ? 's' : '' }} e
            <span class="font-semibold text-green-600">{{ collect($carrinho)->sum('quantidade') }}</span> unidade{{ collect($carrinho)->sum('quantidade') > 1 ? 's' : '' }} no total.
        </p>
        <p class="text-lg font-bold text-green-800">Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
    </div>
    
@else
    <p class="text-center text-gray-500 py-6">Seu carrinho está vazio.</p>
@endif
