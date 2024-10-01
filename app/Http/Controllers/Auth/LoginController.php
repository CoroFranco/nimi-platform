<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Verificar si el email existe
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Si el email no existe, mostrar un mensaje de error para el email
        if ($request->expectsJson()) {
            return response()->json([
                'errors' => ['email' => ['Correo no registrado.']]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'El correo electrónico no está registrado en nuestro sistema.',
        ])->onlyInput('email');
    }

    // Si el email existe, intentar autenticar
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('home')
            ]);
        }

        return redirect()->intended('home');
    }

    // Si la contraseña es incorrecta
    if ($request->expectsJson()) {
        return response()->json([
            'errors' => ['password' => ['Contraseña incorrecta.']]
        ], 422);
    }

    return back()->withErrors([
        'password' => 'La contraseña proporcionada es incorrecta.',
    ])->onlyInput('email');
}
}