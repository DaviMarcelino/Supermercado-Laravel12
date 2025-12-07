<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ProfileController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return auth()->check() ? redirect()->route('produtos.index') : redirect('/login');
});

Route::get('/dashboard', function () {
    return redirect()->route('produtos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');

Route::get('/produtos/create', [ProdutoController::class, 'create'])
    ->name('produtos.create')
    ->middleware('auth');

Route::get('/produtos/{id}', [ProdutoController::class, 'show'])->name('produtos.show');

Route::middleware('auth')->group(function () {
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::post('/carrinho/add/{id}', [CarrinhoController::class, 'add'])->name('carrinho.add');
    Route::get('/carrinho/remove/{id}', [CarrinhoController::class, 'remove'])->name('carrinho.remove');
    Route::get('/carrinho/clear', [CarrinhoController::class, 'clear'])->name('carrinho.clear');
    Route::get('/carrinho/alterar/{id}/{alteracao}', [CarrinhoController::class, 'alterarQuantidade']);
    
    Route::get('/carrinho/conteudo', function () {
        $carrinho = session('carrinho', []);
        return response()->json([
            'produtos' => collect($carrinho)->map(fn($item, $id) => array_merge($item, ['id' => $id]))->values(),
            'total' => count($carrinho)
        ]);
    });

    Route::get('/shopping/resumo', [ShoppingController::class, 'checkout'])->name('shopping.resumo');
    Route::post('/shopping/recibo', [ShoppingController::class, 'gerarBoleta'])->name('shopping.recibo');
    Route::get('/shopping/baixar/{filename}', [ShoppingController::class, 'baixarBoleta'])->name('shopping.baixar');
});