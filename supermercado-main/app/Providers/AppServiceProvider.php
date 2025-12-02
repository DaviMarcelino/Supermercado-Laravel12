<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar quaisquer serviços da aplicação.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializar quaisquer serviços da aplicação.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $carrinho = session('carrinho', []);
            $totalProdutosUnicos = count($carrinho); // 👈 Aqui está a alteração traduzida
            $view->with('totalCarrinho', $totalProdutosUnicos);
        });
    }
}
