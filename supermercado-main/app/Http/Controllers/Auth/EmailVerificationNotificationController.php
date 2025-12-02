<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Envia uma nova notificação de verificação de e-mail.
     */
    public function store(Request $request): RedirectResponse
    {
        // Se o usuário já verificou o e-mail, redireciona diretamente ao dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Envia o link de verificação novamente
        $request->user()->sendEmailVerificationNotification();

        // Retorna para a página anterior com mensagem de status
        return back()->with('status', 'link-de-verificacao-enviado');
    }
}
