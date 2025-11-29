<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Mail;
use App\Models\DetallePedido;
use App\Services\CarritoService;

class CarritoController extends Controller
{
    protected CarritoService $carrito;

    public function __construct(CarritoService $carrito)
    {
        $this->carrito = $carrito;
    }

    // Mostrar el carrito
    public function index()
{
    $carrito = $this->carrito->obtener();

    if (request()->ajax()) {
        return view('shopping.contenido-carrito', compact('carrito'));
    }
    
    
    // Peticiones normales redirigen a productos
    return redirect()->route('productos.index');
}

    

    



    // Agregar producto al carrito
    public function add($id)
    {
        try {
            $carrito = $this->carrito->agregar($id);
            $total = count($carrito); // ✅ solo cuenta productos únicos

            return response()->json(['message' => 'Agregado', 'total' => $total]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    public function cambiarCantidad($id, $cambio)
{
    $carrito = session()->get('carrito', []);
    if (isset($carrito[$id])) {
        $carrito[$id]['cantidad'] += (int)$cambio;
        if ($carrito[$id]['cantidad'] <= 0) {
            unset($carrito[$id]);
        }
        session()->put('carrito', $carrito);
    }
    return response()->json(['success' => true]);
}

    // Quitar producto del carrito
    public function remove($id)
    {
        $this->carrito->eliminar($id);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado');
    }

    // Vaciar carrito
    public function clear()
{
    session()->forget('carrito');

    if (request()->ajax()) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => true]); // <- por si acaso todo es JS
}


    // Confirmar pedido
    public function checkout()
    {
        $carrito = $this->carrito->obtener();

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío');
        }

        $total = collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']);

        // Crear pedido
        $pedido = Pedido::create(['total' => $total]);

        // Crear detalles
        foreach ($carrito as $id => $item) {
            DetallePedido::create([
                'pedido_id'   => $pedido->id,
                'producto_id' => $id,
                'cantidad'    => $item['cantidad'],
                'precio'      => $item['precio']
            ]);
        }

        // Vaciar carrito
        $this->carrito->vaciar();

        return redirect()->route('productos.index')->with('success', 'Pedido generado correctamente');
    }
    public function confirmar(Request $request)
{
    $carrito = session('carrito', []);
    $email = $request->input('email');

    // Generar PDF o boleta aquí (opcional)
    // ...

    // Enviar correo con boleta
    Mail::to($email)->send(new \App\Mail\BoletaElectronica($carrito));

    // Guardar el pedido si es necesario
    // ...

    session()->forget('carrito');

    return redirect()->route('productos.index')->with('success', 'Boleta enviada con éxito');
}
}
