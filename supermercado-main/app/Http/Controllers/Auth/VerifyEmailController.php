<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Marca o e-mail do usuário autenticado como verificado.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Se o e-mail já estiver verificado, só redireciona
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // Marca o e-mail como verificado
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redireciona para o dashboard com status de verificado
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
