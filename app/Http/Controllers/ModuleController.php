<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Module;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function destroy(Course $course, Module $module)
{
    if ($module->course_id !== $course->id) {
        return response()->json(['message' => 'Módulo no encontrado'], 404);
    }

    $module->delete();
    return response()->json(['message' => 'Módulo eliminado exitosamente']);
}
}
