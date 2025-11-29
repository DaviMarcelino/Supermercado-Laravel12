<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Producto;



class ProductoController extends Controller
{
    
    
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
{
    if (!$request->hasFile('imagen') && $request->has('imagen')) {
        return back()->withErrors(['imagen' => 'La imagen no pudo subirse. Verifica que no supere los 2MB.'])
                     ->withInput();
    }
    
    $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'imagen.image' => 'El archivo debe ser una imagen.',
        'imagen.mimes' => 'El formato de imagen debe ser jpg, jpeg, png o webp.',
        'imagen.max' => 'La imagen no debe pesar más de 2MB.',
    ]);
    
    if ($request->hasFile('imagen')) {
        $imgInfo = getimagesize($request->file('imagen'));
        if ($imgInfo === false) {
            return back()->withErrors(['imagen' => 'No se pudo leer la imagen.'])->withInput();
        }
    
        $ancho = $imgInfo[0];
        $alto = $imgInfo[1];
    
        if ($ancho > 500 || $alto > 500) {
            return back()->withErrors(['imagen' => 'La imagen debe medir máximo 500x500 píxeles.'])->withInput();
        }
    }
    
    $producto = new Producto();
    $producto->nombre = $request->nombre;
    $producto->precio = $request->precio;
    $producto->stock = $request->stock;

    if ($request->hasFile('imagen')) {
        $archivo = $request->file('imagen');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $archivo->move(public_path('imagenes'), $nombreArchivo);
        $producto->imagen = 'imagenes/' . $nombreArchivo;
    }

    $producto->save();

    return redirect()->route('productos.index')->with('success', 'Producto registrado correctamente');
}



    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
{
    if (!$request->hasFile('imagen') && $request->has('imagen')) {
        return back()->withErrors(['imagen' => 'La imagen no pudo subirse. Verifica que no supere los 2MB.'])
                     ->withInput();
    }
    
    $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'imagen.image' => 'El archivo debe ser una imagen.',
        'imagen.mimes' => 'El formato de imagen debe ser jpg, jpeg, png o webp.',
        'imagen.max' => 'La imagen no debe pesar más de 2MB.',
    ]);
    
    if ($request->hasFile('imagen')) {
        $imgInfo = getimagesize($request->file('imagen'));
        if ($imgInfo === false) {
            return back()->withErrors(['imagen' => 'No se pudo leer la imagen.'])->withInput();
        }
    
        $ancho = $imgInfo[0];
        $alto = $imgInfo[1];
    
        if ($ancho > 500 || $alto > 500) {
            return back()->withErrors(['imagen' => 'La imagen debe medir máximo 500x500 píxeles.'])->withInput();
        }
    }
    

    $producto = Producto::findOrFail($id);
    $producto->nombre = $request->nombre;
    $producto->precio = $request->precio;
    $producto->stock = $request->stock;

    if ($request->hasFile('imagen')) {
        if ($producto->imagen && file_exists(public_path($producto->imagen))) {
            unlink(public_path($producto->imagen));
        }

        $archivo = $request->file('imagen');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $archivo->move(public_path('imagenes'), $nombreArchivo);
        $producto->imagen = 'imagenes/' . $nombreArchivo;
    }

    $producto->save();

    return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
}




    public function show(string $id)
    {
            //
    }




    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
        
}
