<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Mail;
use App\Models\DetalhePedido;
use App\Services\CarrinhoService;

class CarrinhoController extends Controller
{
    protected CarrinhoService $carrinho;

    public function __construct(CarrinhoService $carrinho)
    {
        $this->carrinho = $carrinho;
    }

    // Mostrar o carrinho
    public function index()
    {
        $carrinho = $this->carrinho->obter();

        if (request()->ajax()) {
            return view('shopping.conteudo-carrinho', compact('carrinho'));
        }
        
        return redirect()->route('produtos.index');
    }

    // Adicionar produto ao carrinho
    public function add($id)
    {
        try {
            $carrinho = $this->carrinho->adicionar($id); // CORREÇÃO: Método correto é "adicionar"
            $total = count($carrinho);

            return response()->json([
                'message' => 'Adicionado com sucesso', 
                'total' => $total,
                'carrinho' => $carrinho
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // Alterar quantidade
    public function alterarQuantidade($id, $alteracao)
    {
        $carrinho = session()->get('carrinho', []);
        
        if (isset($carrinho[$id])) {
            $carrinho[$id]['quantidade'] += (int)$alteracao;

            if ($carrinho[$id]['quantidade'] <= 0) {
                unset($carrinho[$id]);
            }

            session()->put('carrinho', $carrinho);
        }

        return response()->json(['success' => true, 'carrinho' => $carrinho]);
    }

    // Remover item do carrinho
    public function remove($id)
    {
        $this->carrinho->remover($id);
        $carrinho = $this->carrinho->obter();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'carrinho' => $carrinho,
                'total' => count($carrinho)
            ]);
        }

        return redirect()->route('carrinho.index')->with('success', 'Produto removido');
    }

    // Esvaziar carrinho
    public function clear()
    {
        $this->carrinho->esvaziar();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'total' => 0,
                'message' => 'Carrinho esvaziado com sucesso'
            ]);
        }

        return redirect()->route('carrinho.index')->with('success', 'Carrinho esvaziado');
    }

    // Finalizar pedido
    public function checkout()
    {
        $carrinho = $this->carrinho->obter();

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio');
        }

        $total = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        // Criar pedido
        $pedido = Pedido::create(['total' => $total]);

        // Criar detalhes do pedido
        foreach ($carrinho as $id => $item) {
            DetalhePedido::create([
                'pedido_id'   => $pedido->id,
                'produto_id'  => $id,
                'quantidade'  => $item['quantidade'],
                'preco'       => $item['preco']
            ]);
        }

        // Esvaziar carrinho
        $this->carrinho->esvaziar();

        return redirect()->route('produtos.index')->with('success', 'Pedido realizado com sucesso');
    }

    // Enviar recibo por e-mail
    public function confirmar(Request $request)
    {
        $carrinho = session('carrinho', []);
        $email = $request->input('email');

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio');
        }

        // CORREÇÃO: Verificar se a classe de email existe
        if (class_exists(\App\Mail\ReciboEletronico::class)) {
            Mail::to($email)->send(new \App\Mail\ReciboEletronico($carrinho));
        }

        session()->forget('carrinho');

        return redirect()->route('produtos.index')->with('success', 'Recibo enviado com sucesso');
    }
}