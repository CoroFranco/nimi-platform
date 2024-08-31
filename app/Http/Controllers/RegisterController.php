<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|same:reapetPassword',
                'reapetPassword' => 'required|string|min:8',
            ], [
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.same' => 'Las contraseñas no coinciden.',
            ]);

            // Crear un nuevo usuario y guardarlo en la base de datos
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'bio' => $request->input('bio', ''),
                'profile_photo_path' => $request->input('profile_photo_path', ''),
                'role' => 'user', 
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cuenta creada correctamente. Por favor, inicia sesión.'
                ]);
            }

            return redirect('welcome')->with('success', 'Cuenta creada correctamente. Por favor, inicia sesión.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error al registrar el usuario: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['general' => ['Hubo un problema al registrar el usuario. Por favor, inténtalo de nuevo.']]
                ], 500);
            }
            return back()->with('error', 'Hubo un problema al registrar el usuario. Por favor, inténtalo de nuevo.')->withInput();
        }
    }
}
