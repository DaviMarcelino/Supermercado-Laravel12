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
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp|max:10240', // 10MB e sem WEBP
        ], [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png, gif ou bmp.',
            'imagem.max' => 'A imagem não deve pesar mais de 10MB.',
        ]);

        // Validação de dimensões da imagem (aumentado para 4000x4000 pixels)
        if ($request->hasFile('imagem')) {
            $imgInfo = getimagesize($request->file('imagem'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagem' => 'Não foi possível ler a imagem.'])->withInput();
            }

            $largura = $imgInfo[0];
            $altura = $imgInfo[1];
            $tamanhoMB = $request->file('imagem')->getSize() / 1048576; // Convertendo bytes para MB

            // Limite de 4000x4000 pixels
            if ($largura > 4000 || $altura > 4000) {
                return back()->withErrors(['imagem' => 'A imagem deve ter no máximo 4000x4000 pixels.'])->withInput();
            }

            // Verificação adicional do tamanho do arquivo (já validado pelo Laravel, mas garantindo)
            if ($tamanhoMB > 10) {
                return back()->withErrors(['imagem' => 'A imagem não deve pesar mais de 10MB.'])->withInput();
            }
        }

        // Criar produto
        $produto = new Produto();
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;

        // Upload da imagem - SALVAR NO CAMINHO ESPECÍFICO
        if ($request->hasFile('imagem')) {
            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            
            // Caminho específico onde a imagem será salva
            $caminhoDestino = 'C:\Users\lesam\Documentos\Supermercado-Laravel12\supermercado-main\public\imagens';
            
            // Garantir que o diretório existe
            if (!file_exists($caminhoDestino)) {
                mkdir($caminhoDestino, 0755, true);
            }
            
            // Mover o arquivo para o diretório específico
            $arquivo->move($caminhoDestino, $nomeArquivo);
            
            // Salvar o caminho relativo no banco de dados
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
        // Validação
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp|max:10240', // 10MB e sem WEBP
        ], [
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'O formato da imagem deve ser jpg, jpeg, png, gif ou bmp.',
            'imagem.max' => 'A imagem não deve pesar mais de 10MB.',
        ]);

        // Validação de dimensões da imagem (aumentado para 4000x4000 pixels)
        if ($request->hasFile('imagem')) {
            $imgInfo = getimagesize($request->file('imagem'));
            if ($imgInfo === false) {
                return back()->withErrors(['imagem' => 'Não foi possível ler a imagem.'])->withInput();
            }

            $largura = $imgInfo[0];
            $altura = $imgInfo[1];
            $tamanhoMB = $request->file('imagem')->getSize() / 1048576;

            if ($largura > 4000 || $altura > 4000) {
                return back()->withErrors(['imagem' => 'A imagem deve ter no máximo 4000x4000 pixels.'])->withInput();
            }

            if ($tamanhoMB > 10) {
                return back()->withErrors(['imagem' => 'A imagem não deve pesar mais de 10MB.'])->withInput();
            }
        }

        $produto = Produto::findOrFail($id);
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;

        // Upload da nova imagem (se fornecida)
        if ($request->hasFile('imagem')) {
            // Caminho específico onde as imagens são salvas
            $caminhoDestino = 'C:\Users\lesam\Documentos\Supermercado-Laravel12\supermercado-main\public\imagens';
            
            // Remover imagem antiga se existir
            if ($produto->imagem && file_exists(public_path($produto->imagem))) {
                unlink(public_path($produto->imagem));
            }

            $arquivo = $request->file('imagem');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            
            // Garantir que o diretório existe
            if (!file_exists($caminhoDestino)) {
                mkdir($caminhoDestino, 0755, true);
            }
            
            // Mover o arquivo para o diretório específico
            $arquivo->move($caminhoDestino, $nomeArquivo);
            
            // Salvar o caminho relativo no banco de dados
            $produto->imagem = 'imagens/' . $nomeArquivo;
        }

        $produto->save();

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        
        // Remover imagem se existir (do caminho específico)
        if ($produto->imagem && file_exists(public_path($produto->imagem))) {
            unlink(public_path($produto->imagem));
        }
        
        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso');
    }
}