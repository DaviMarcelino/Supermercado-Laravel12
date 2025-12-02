<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Facades\Session;

class CarrinhoService
{
    /**
     * Obter o carrinho atual da sessão
     */
    public function obter(): array
    {
        return Session::get('carrinho', []);
    }

    /**
     * Adicionar um produto ao carrinho pelo ID
     */
    public function adicionar(int $id): array
    {
        $produto = Produto::find($id);

        if (!$produto) {
            throw new \Exception('Produto não encontrado');
        }

        $carrinho = $this->obter();

        if (isset($carrinho[$id])) {
            $carrinho[$id]['quantidade']++;
        } else {
            $carrinho[$id] = [
                'nome'       => $produto->nome,
                'preco'      => $produto->preco,
                'quantidade' => 1
            ];
        }

        Session::put('carrinho', $carrinho);

        return $carrinho;
    }

    /**
     * Remover um produto do carrinho pelo ID
     */
    public function remover(int $id): void
    {
        $carrinho = $this->obter();

        if (isset($carrinho[$id])) {
            unset($carrinho[$id]);
            Session::put('carrinho', $carrinho);
        }
    }

    /**
     * Esvaziar completamente o carrinho
     */
    public function esvaziar(): void
    {
        Session::forget('carrinho');
    }

    /**
     * Obter o total de itens no carrinho
     */
    public function contar(): int
    {
        return collect($this->obter())->sum('quantidade');
    }

    /**
     * Calcular o valor total do carrinho
     */
    public function total(): float
    {
        return collect($this->obter())->reduce(function ($acumulado, $item) {
            return $acumulado + ($item['preco'] * $item['quantidade']);
        }, 0.0);
    }
}
