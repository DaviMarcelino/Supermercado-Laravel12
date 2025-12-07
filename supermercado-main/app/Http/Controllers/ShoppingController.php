<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pedido;
use App\Models\DetalhePedido;

class ShoppingController extends Controller
{
    public function checkout()
    {
        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio');
        }

        $total = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        $usuario = auth()->user() ?? (object)[
            'nome' => 'Cliente Convidado',
            'email' => 'cliente@example.com',
        ];

        $pdf = Pdf::loadView('shopping.recibo', compact('carrinho', 'total', 'usuario'));

        $filename = 'recibo_' . now()->format('YmdHis') . '.pdf';
        $path = storage_path("app/public/{$filename}");
        
        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }
        
        $pdf->save($path);

        session()->forget('carrinho');

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    public function gerarBoleta(Request $request)
    {
        $carrinho = session('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio.');
        }

        $total = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        $usuario = auth()->user() ?? (object)[
            'nome' => 'Convidado',
            'email' => 'cliente@example.com',
        ];

        $pedido = Pedido::create([
            'usuario' => $usuario->nome ?? 'Convidado',
            'email' => $usuario->email ?? 'cliente@example.com',
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

        $pdf = Pdf::loadView('shopping.recibo', compact('carrinho', 'total', 'usuario'));
        
        if (!file_exists(storage_path('app/public/recibos'))) {
            mkdir(storage_path('app/public/recibos'), 0755, true);
        }
        
        $filename = 'boleta_' . now()->format('YmdHis') . '.pdf';
        $pdf->save(storage_path("app/public/recibos/{$filename}"));

        session()->forget('carrinho');

        return view('shopping.gracias', compact('filename', 'pedido'));
    }

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