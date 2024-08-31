<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\delete;

class ProfileController extends Controller
{


    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::id();

        // Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'bio' => 'nullable|string|max:1000', // Asegúrate de que 'bio' sea opcional y limitada
        ]);



        // Encontrar el usuario por ID
        $user = User::findOrFail($id);

        // Actualizar los datos
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->bio = $validatedData['bio'];

        // Guardar los cambios en la base de datos
        $user->save();

        // Retornar una respuesta, por ejemplo, redirigir al perfil del usuario
        return redirect()->route('profile', ['id' => $user->id])->with('success', 'Usuario actualizado exitosamente.');
    }

    public function updatePassword(Request $request)
    {
        $id = Auth::id();

        // Validar los datos de entrada
        $validatedData = $request->validate([
            'password' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ], [
            'newPassword.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);


        $user = User::findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['error' => 'La contraseña actual no es correcta.']);
        }

        // Actualizar los datos
        $user->password = Hash::make($validatedData['newPassword']);

        // Guardar los cambios en la base de datos
        $user->save();

        // Retornar una respuesta, por ejemplo, redirigir al perfil del usuario
        return redirect()->route('profile', ['id' => $user->id])->with('success', 'Contraseña actualizada exitosamente.');
    }

    public function deleteAccount(Request $request)
{
    $id = Auth::id();

    if (!$id) {
        return redirect()->route('login')->with('error', 'Debes estar autenticado para eliminar tu cuenta.');
    }

    $user = User::find($id);

    if ($user) {
        $user->delete();
        Auth::logout(); // Cerrar la sesión antes de eliminar la cuenta
        return redirect()->route('welcome')->with('success', 'Cuenta eliminada exitosamente.');
    } else {
        return redirect()->route('welcome')->with('error', 'Usuario no encontrado.');
    }
}
}
