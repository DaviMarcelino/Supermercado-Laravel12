<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Exibe a tela de confirmação de senha.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirma a senha do usuário.
     */
    public function store(Request $request): RedirectResponse
    {
        // Verifica se a senha informada corresponde ao usuário autenticado
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'), // Mensagem de senha incorreta
            ]);
        }

        // Armazena na sessão o momento em que a senha foi confirmada
        $request->session()->put('auth.password_confirmed_at', time());

        // Redireciona para onde o usuário pretendia ir
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
