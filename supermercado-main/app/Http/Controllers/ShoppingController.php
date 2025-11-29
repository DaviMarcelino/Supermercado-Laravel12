<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\Response;

class ShoppingController extends Controller
{
    public function checkout()
{
    $carrito = session()->get('carrito', []);
    
    if (empty($carrito)) {
        return redirect()->route('shopping.index')->with('error', 'El carrito está vacío');
    }

    // Calcular total
    $subtotal = collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']);
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    // Simulamos datos de usuario
    $usuario = auth()->user() ?? (object)[
        'nombre' => 'Cliente Invitado',
        'email' => 'cliente@example.com',
    ];

    $pdf = Pdf::loadView('shopping.boleta', compact('carrito', 'subtotal', 'igv', 'total', 'usuario'));
    
    // Guardar PDF temporalmente
    $filename = 'boleta_' . now()->format('YmdHis') . '.pdf';
    $path = storage_path("app/public/{$filename}");
    $pdf->save($path);

    // Borrar carrito
    session()->forget('carrito');

    // Retornar descarga directa
    return response()->download($path)->deleteFileAfterSend(true);
}
public function generarBoleta()
{
    $carrito = session('carrito', []);

    if (empty($carrito)) {
        return redirect()->route('shopping.index')->with('error', 'El carrito está vacío.');
    }

    $subtotal = collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']);
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    $usuario = auth()->user() ?? (object)[
        'nombre' => 'Invitado',
        'email' => 'cliente@example.com',
    ];

    // Guardar en base de datos
    $pedido = Pedido::create([
        'usuario' => $usuario->nombre ?? 'Invitado', // o auth()->user()->name
        'email' => $usuario->email ?? 'cliente@example.com',
        'subtotal' => $subtotal,
        'igv' => $igv,
        'total' => $total,
    ]);
    

    foreach ($carrito as $id => $item) {
        DetallePedido::create([
            'pedido_id' => $pedido->id,
            'producto_id' => $id,
            'cantidad' => $item['cantidad'],
            'precio' => $item['precio']
        ]);
    }

    // Generar PDF
    $pdf = Pdf::loadView('shopping.boleta', compact('carrito', 'subtotal', 'igv', 'total', 'usuario'));
    $filename = 'boleta_' . now()->format('YmdHis') . '.pdf';
    $pdf->save(storage_path("app/public/boletas/{$filename}"));

    // Vaciar carrito
    session()->forget('carrito');

    return view('shopping.gracias', compact('filename'));
}


public function descargarBoleta($filename)
{
    $path = storage_path("app/public/boletas/{$filename}");

    if (!file_exists($path)) {
        abort(404, 'Boleta no encontrada.');
    }

    return response()->download($path, $filename, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
    ]);
}

}
