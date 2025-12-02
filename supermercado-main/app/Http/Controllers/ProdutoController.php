<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return view('produtos.index', compact('produtos'));
    }

    public function show($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produtos.show', compact('produto'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        if (!$request->hasFile('imagem') && $request->has('imagem')) {
            return back()->withErrors(['imagem' => 'A imagem não pôde ser enviada. Verifique que não ultrapassa 2MB.'])
                         ->withInput();
        }

        // CORREÇÃO: Validar campo "estoque" em vez de "stock"
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0', // CORREÇÃO: estoque em vez de stock
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagem.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);

        if ($request->hasFile('imagem')) {
            $imgInfo = getimagesize($request->file('imagem'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagem' => 'Não foi possível ler a imagem.'])->withInput();
            }

            $largura = $imgInfo[0];
            $altura = $imgInfo[1];

            if ($largura > 500 || $altura > 500) {
                return back()->withErrors(['imagem' => 'A imagem deve ter no máximo 500x500 pixels.'])->withInput();
            }
        }

        $produto = new Produto();
        $produto->nome = $request->nome;
        $produto->preco = $request->preco;
        $produto->estoque = $request->estoque; // CORREÇÃO: estoque em vez de stock

        if ($request->hasFile('imagem')) {
            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $arquivo->move(public_path('imagens'), $nomeArquivo);
            $produto->imagem = 'imagens/' . $nomeArquivo;
        }

        $produto->save();

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso');
    }

    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        if (!$request->hasFile('imagem') && $request->has('imagem')) {
            return back()->withErrors(['imagem' => 'A imagem não pôde ser enviada. Verifique que não ultrapassa 2MB.'])
                         ->withInput();
        }

        // CORREÇÃO: Validar campo "estoque" em vez de "stock"
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0', // CORREÇÃO: estoque em vez de stock
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ],
        [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagem.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);

        if ($request->hasFile('imagem')) {
            $imgInfo = getimagesize($request->file('imagem'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagem' => 'Não foi possível ler a imagem.'])->withInput();
            }

            $largura = $imgInfo[0];
            $altura = $imgInfo[1];

            if ($largura > 500 || $altura > 500) {
                return back()->withErrors(['imagem' => 'A imagem deve ter no máximo 500x500 pixels.'])->withInput();
            }
        }

        $produto = Produto::findOrFail($id);
        $produto->nome = $request->nome;
        $produto->preco = $request->preco;
        $produto->estoque = $request->estoque; // CORREÇÃO: estoque em vez de stock

        if ($request->hasFile('imagem')) {
            if ($produto->imagem && file_exists(public_path($produto->imagem))) {
                unlink(public_path($produto->imagem));
            }

            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $arquivo->move(public_path('imagens'), $nomeArquivo);
            $produto->imagem = 'imagens/' . $nomeArquivo;
        }

        $produto->save();

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso');
    }
}