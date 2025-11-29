<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect('/productos');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('productos', ProductoController::class);
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::get('/carrito/add/{id}', [CarritoController::class, 'add'])->name('carrito.add');
Route::get('/carrito/remove/{id}', [CarritoController::class, 'remove'])->name('carrito.remove');
Route::get('/carrito/clear', [CarritoController::class, 'clear'])->name('carrito.clear');
Route::get('/carrito/cambiar/{id}/{cambio}', [CarritoController::class, 'cambiarCantidad']);
Route::get('/carrito/clear', [CarritoController::class, 'clear'])->name('carrito.clear');
Route::get('/carrito/contenido', function () {
    $carrito = session('carrito', []);
    return response()->json(['productos' => collect($carrito)->map(function ($item, $id) {
        return array_merge($item, ['id' => $id]);
    })->values()]);
});

Route::get('/shopping/resumen', [ShoppingController::class, 'checkout'])->name('shopping.resumen');
Route::post('/shopping/boleta', [ShoppingController::class, 'generarBoleta'])->name('shopping.boleta');
Route::get('/shopping/descargar/{filename}', [ShoppingController::class, 'descargarBoleta'])->name('shopping.descargar');

require __DIR__.'/auth.php';
