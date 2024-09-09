<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\delete;

class ProfileController extends Controller
{


    public function profile()
{
    $user = Auth::user();
    $coursesCreated = Course::where('instructor_id', $user->id)->get()->count();

    // Obtener los enrollments del usuario
    $enrollments = Enrollment::where('user_id', $user->id)->get();
    Log::info($enrollments);

    // Obtener los IDs de los cursos asociados a los enrollments
    $courseIds = $enrollments->pluck('course_id');

    // Obtener los cursos correspondientes y cargar sus módulos y lecciones en una sola consulta
    $courses = Course::whereIn('id', $courseIds)
                     ->with(['modules.lessons' => function($query) use ($user) {
                         // Cargar las lecciones y también verificar si están completadas por el usuario
                         $query->with(['lessonProgress' => function($subQuery) use ($user) {
                             $subQuery->where('user_id', $user->id)
                                      ->where('status', 'completed');
                         }]);
                     }])
                     ->get();

    // Inicializar arrays para almacenar las lecciones por curso, su conteo, las completadas y el progreso
    $lessonsByCourse = [];
    $lessonsCountByCourse = [];
    $completedLessonsCountByCourse = [];
    $courseProgress = [];

    // Iterar sobre cada curso para procesar las lecciones
    foreach ($courses as $course) {
        // Obtener todas las lecciones de los módulos del curso y ordenarlas por 'less_order'
        $lessons = $course->modules->flatMap->lessons->sortBy('less_order');

        // Almacenar las lecciones en el array, usando el ID del curso como clave
        $lessonsByCourse[$course->id] = $lessons;

        // Contar las lecciones y almacenarlas en el array
        $lessonsCountByCourse[$course->id] = $lessons->count();

        // Contar cuántas lecciones están completadas
        $completedLessonsCountByCourse[$course->id] = $lessons->filter(function($lesson) {
            return $lesson->lessonProgress->isNotEmpty(); // Verifica si hay progreso completado
        })->count();

        // Calcular el progreso del curso en porcentaje
        $totalLessons = $lessonsCountByCourse[$course->id];
        $completedLessons = $completedLessonsCountByCourse[$course->id];
        $courseProgress[$course->id] = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
    }

    Log::info($courses);

    return view('profile', compact(
        'enrollments',
        'courses',
        'lessonsByCourse',
        'lessonsCountByCourse',
        'completedLessonsCountByCourse',
        'courseProgress',
        'coursesCreated'
    ));
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
