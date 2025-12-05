<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Storage;

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
        // Validação
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagem.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);

        // Validação de dimensões da imagem
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

        // Criar produto
        $produto = new Produto();
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;

        // Upload da imagem
        if ($request->hasFile('imagem')) {
            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $caminho = $arquivo->storeAs('imagens', $nomeArquivo, 'public');
            $produto->imagem = 'storage/' . $caminho;
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
        // Validação
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png ou webp.',
            'imagem.max' => 'A imagem não deve pesar mais de 2MB.',
        ]);

        // Validação de dimensões da imagem
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
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;

        // Upload da nova imagem (se fornecida)
        if ($request->hasFile('imagem')) {
            // Remover imagem antiga se existir
            if ($produto->imagem && Storage::disk('public')->exists(str_replace('storage/', '', $produto->imagem))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $produto->imagem));
            }

            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $caminho = $arquivo->storeAs('imagens', $nomeArquivo, 'public');
            $produto->imagem = 'storage/' . $caminho;
        }

        $produto->save();

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        
        // Remover imagem se existir
        if ($produto->imagem && Storage::disk('public')->exists(str_replace('storage/', '', $produto->imagem))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $produto->imagem));
        }
        
        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso');
    }
}