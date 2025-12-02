<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Manipula a requisição antes de chegar ao controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado e se é administrador
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()
                ->route('produtos.index')
                ->with('error', 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
