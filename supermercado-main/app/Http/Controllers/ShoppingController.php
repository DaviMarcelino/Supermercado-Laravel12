<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pedido;
use App\Models\DetalhePedido;

class ShoppingController extends Controller
{
    // Finalizar compra e gerar PDF para download direto
    public function checkout()
    {
        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio');
        }

        // Calcular total
        $subtotal = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;

        // Obter usuário autenticado
        $usuario = auth()->user() ?? (object)[
            'nome' => 'Cliente Convidado',
            'email' => 'cliente@example.com',
        ];

        // CORREÇÃO: Alterado de 'recibo.shopping' para 'shopping.recibo'
        $pdf = Pdf::loadView('shopping.recibo', compact('carrinho', 'subtotal', 'igv', 'total', 'usuario'));

        // Salvar PDF temporariamente
        $filename = 'recibo_' . now()->format('YmdHis') . '.pdf';
        $path = storage_path("app/public/{$filename}");
        
        // Garantir que o diretório existe
        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }
        
        $pdf->save($path);

        // Limpar carrinho
        session()->forget('carrinho');

        // Retornar download direto
        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    // Gerar boleta, salvar no banco e mostrar tela de agradecimento
    public function gerarBoleta(Request $request)
    {
        $carrinho = session('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio.');
        }

        $subtotal = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;

        $usuario = auth()->user() ?? (object)[
            'nome' => 'Convidado',
            'email' => 'cliente@example.com',
        ];

        // Salvar no banco de dados
        $pedido = Pedido::create([
            'usuario' => $usuario->nome ?? 'Convidado',
            'email' => $usuario->email ?? 'cliente@example.com',
            'subtotal' => $subtotal,
            'imposto' => $igv,
            'total' => $total,
        ]);

        foreach ($carrinho as $id => $item) {
            DetalhePedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $id,
                'quantidade' => $item['quantidade'],
                'preco' => $item['preco']
            ]);
        }

        // CORREÇÃO: Alterado de 'recibo.shopping' para 'shopping.recibo'
        $pdf = Pdf::loadView('shopping.recibo', compact('carrinho', 'subtotal', 'igv', 'total', 'usuario'));
        
        // Garantir que o diretório existe
        if (!file_exists(storage_path('app/public/recibos'))) {
            mkdir(storage_path('app/public/recibos'), 0755, true);
        }
        
        $filename = 'boleta_' . now()->format('YmdHis') . '.pdf';
        $pdf->save(storage_path("app/public/recibos/{$filename}"));

        // Limpar carrinho
        session()->forget('carrinho');

        return view('shopping.gracias', compact('filename', 'pedido'));
    }

    // Baixar boleta existente
    public function baixarBoleta($filename)
    {
        $path = storage_path("app/public/recibos/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'Recibo não encontrado.');
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}