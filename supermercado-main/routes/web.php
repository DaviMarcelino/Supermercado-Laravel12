<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ProfileController;

// Rota principal - REDIRECIONA PARA LOGIN
Route::get('/', function () {
    return redirect('/login');
});

// Rotas de autenticação (importadas do auth.php) - DEVE VIR PRIMEIRO
require __DIR__.'/auth.php';

// Rota home alternativa
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('productos.index');
    }
    return redirect('/login');
});

// Dashboard redireciona para produtos
Route::get('/dashboard', function () {
    return redirect()->route('productos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas protegidas por auth (TODAS as rotas que precisam de login) - MOVIDO PARA CIMA
Route::middleware('auth')->group(function () {
    // Rotas de ADMIN para produtos (AGORA NO TOPO DO GRUPO PARA PRIORIZAR SOBRE AS GENÉRICAS)
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    
    // Outras rotas do grupo auth
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas do carrinho
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::get('/carrito/add/{id}', [CarritoController::class, 'add'])->name('carrito.add');
    Route::get('/carrito/remove/{id}', [CarritoController::class, 'remove'])->name('carrito.remove');
    Route::get('/carrito/clear', [CarritoController::class, 'clear'])->name('carrito.clear');
    Route::get('/carrito/cambiar/{id}/{cambio}', [CarritoController::class, 'cambiarCantidad']);
    Route::get('/carrito/contenido', function () {
        $carrito = session('carrito', []);
        return response()->json(['productos' => collect($carrito)->map(function ($item, $id) {
            return array_merge($item, ['id' => $id]);
        })->values()]);
    });

    // Shopping
    Route::get('/shopping/resumen', [ShoppingController::class, 'checkout'])->name('shopping.resumen');
    Route::post('/shopping/boleta', [ShoppingController::class, 'generarBoleta'])->name('shopping.boleta');
    Route::get('/shopping/descargar/{filename}', [ShoppingController::class, 'descargarBoleta'])->name('shopping.descargar');
});

// Rotas PÚBLICAS (MOVIDAS PARA O FINAL, APÓS O GRUPO AUTH, PARA EVITAR CONFLITOS)
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');