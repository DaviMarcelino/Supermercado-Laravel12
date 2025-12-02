<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Exibe o aviso de verificação de e-mail.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Se o e-mail já estiver verificado, redireciona ao dashboard
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : view('auth.verify-email'); // Senão, mostra a tela pedindo verificação
    }
}
