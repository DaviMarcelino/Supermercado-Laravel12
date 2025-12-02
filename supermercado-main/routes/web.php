<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ProfileController;

// Rotas de autenticação
require __DIR__.'/auth.php';

// Rota principal - redireciona conforme login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('produtos.index') : redirect('/login');
});

// Dashboard (redireciona para produtos)
Route::get('/dashboard', function () {
    return redirect()->route('produtos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas PÚBLICAS de produtos - apenas visualizar
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::get('/produtos/{id}', [ProdutoController::class, 'show'])->name('produtos.show');

// Rotas que precisam de login
Route::middleware('auth')->group(function () {
    // Rotas de administração de produtos
    Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carrinho
    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::post('/carrinho/add/{id}', [CarrinhoController::class, 'add'])->name('carrinho.add');
    Route::get('/carrinho/remove/{id}', [CarrinhoController::class, 'remove'])->name('carrinho.remove');
    Route::get('/carrinho/clear', [CarrinhoController::class, 'clear'])->name('carrinho.clear');
    Route::get('/carrinho/alterar/{id}/{alteracao}', [CarrinhoController::class, 'alterarQuantidade']);
    
    // Rota para obter conteúdo do carrinho via AJAX
    Route::get('/carrinho/conteudo', function () {
        $carrinho = session('carrinho', []);
        return response()->json([
            'produtos' => collect($carrinho)->map(fn($item, $id) => array_merge($item, ['id' => $id]))->values(),
            'total' => count($carrinho)
        ]);
    });

    // Shopping
    Route::get('/shopping/resumo', [ShoppingController::class, 'checkout'])->name('shopping.resumo');
    Route::post('/shopping/recibo', [ShoppingController::class, 'gerarBoleta'])->name('shopping.recibo');
    Route::get('/shopping/baixar/{filename}', [ShoppingController::class, 'baixarBoleta'])->name('shopping.baixar');
});