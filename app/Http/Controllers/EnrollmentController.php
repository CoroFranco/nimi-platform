<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $courseId)
    {
        // Validar que el curso existe
        $course = Course::findOrFail($courseId);

        // Verificar si el usuario ya está inscrito en el curso
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->first();

        Log::info($existingEnrollment);


        if ($existingEnrollment) {
            Log::info('si existe');
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso.'
            ], 400);
        } else {
            Log::info('no existe');
            // Crear una nueva inscripción
            $enrollment = new Enrollment();
            $enrollment->user_id = Auth::id();
            $enrollment->course_id = $courseId;
            $enrollment->save();
            Log::info($enrollment);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción exitosa.'
            ]);
        }
    }
}
