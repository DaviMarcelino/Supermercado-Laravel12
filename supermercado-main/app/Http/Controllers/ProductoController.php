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

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function create()
    {
        // Verificação removida temporariamente para testar
        return view('productos.create');
    }

    public function store(Request $request)
    {
        // Verificação removida temporariamente para testar
        
        if (!$request->hasFile('imagen') && $request->has('imagen')) {
            return back()->withErrors(['imagen' => 'A imagem não pôde ser enviada. Verifique que não ultrapassa 2MB.'])
                         ->withInput();
        }
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'imagen.image' => 'O arquivo deve ser uma imagem.',
            'imagen.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagen.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);
        
        if ($request->hasFile('imagen')) {
            $imgInfo = getimagesize($request->file('imagen'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagen' => 'Não foi possível ler a imagem.'])->withInput();
            }
        
            $ancho = $imgInfo[0];
            $alto = $imgInfo[1];
        
            if ($ancho > 500 || $alto > 500) {
                return back()->withErrors(['imagen' => 'A imagem deve ter no máximo 500x500 pixels.'])->withInput();
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

        return redirect()->route('productos.index')->with('success', 'Produto cadastrado com sucesso');
    }

    public function edit($id)
    {
        // Verificação removida temporariamente para testar
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        // Verificação removida temporariamente para testar
        
        if (!$request->hasFile('imagen') && $request->has('imagen')) {
            return back()->withErrors(['imagen' => 'A imagem não pôde ser enviada. Verifique que não ultrapassa 2MB.'])
                         ->withInput();
        }
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'imagen.image' => 'O arquivo deve ser uma imagem.',
            'imagen.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagen.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);
        
        if ($request->hasFile('imagen')) {
            $imgInfo = getimagesize($request->file('imagen'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagen' => 'Não foi possível ler a imagem.'])->withInput();
            }
        
            $ancho = $imgInfo[0];
            $alto = $imgInfo[1];
        
            if ($ancho > 500 || $alto > 500) {
                return back()->withErrors(['imagen' => 'A imagem deve ter no máximo 500x500 pixels.'])->withInput();
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

        return redirect()->route('productos.index')->with('success', 'Produto atualizado com sucesso');
    }

    public function destroy($id)
    {
        // Verificação removida temporariamente para testar
        $producto = Producto::findOrFail($id);
        $producto->delete();
        
        return redirect()->route('productos.index')->with('success', 'Produto excluído com sucesso');
    }
}