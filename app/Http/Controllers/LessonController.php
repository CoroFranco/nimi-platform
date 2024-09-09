<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Comment;
use App\Models\Module;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class LessonController extends Controller
{
    use AuthorizesRequests;

    public function show(Course $course, Lesson $lesson)
{   
    $user = Auth::user();
    $isInstructor = $course->instructor_id === $user->id;
    $enrollment = null;

    // Permitir acceso al instructor sin necesidad de inscripción
    if (!$isInstructor) {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }
    }

    $course->load('modules.lessons');

    $allLessons = $course->modules->flatMap->lessons->sortBy('less_order');
    $currentIndex = $allLessons->search($lesson);
    $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
    $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

    $completedLessonIds = LessonProgress::where('user_id', Auth::id())
    ->whereHas('lesson.module.course', function ($query) use ($course) {
        $query->where('id', $course->id);
    })
    ->where('status', 'completed')
    ->pluck('lesson_id')
    ->toArray();

    $totalLessons = $allLessons->count();
    $completedLessons = count($completedLessonIds);
    $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

    $comments = $lesson->comments()->with('user')->orderBy('created_at', 'desc')->get();

    return view('courses', compact(
        'course',
        'lesson',
        'enrollment',
        'isInstructor',
        'progress',
        'completedLessons',
        'totalLessons',
        'previousLesson',
        'nextLesson',
        'completedLessonIds',
        'comments'
    ));
}

public function completeLesson(Request $request, Course $course, Lesson $lesson)
{
    $this->authorize('view', $course);

    $user = Auth::user();

    // Verifica que la lección pertenezca al curso actual
    if (!$course->modules()->whereHas('lessons', function ($query) use ($lesson) {
        $query->where('id', $lesson->id);
    })->exists()) {
        return redirect()->back()->with('error', 'This lesson does not belong to the course.');
    }

    // Marca la lección como completada
    LessonProgress::updateOrCreate(
        ['user_id' => $user->id, 'lesson_id' => $lesson->id],
        ['status' => 'completed']
    );

    // Calcula el progreso del curso
    $totalLessons = $course->modules->flatMap->lessons->count();
    $completedLessons = LessonProgress::where('user_id', $user->id)
        ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
        ->where('status', 'completed')
        ->count();

    $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

    // Actualiza o crea el registro de inscripción con el nuevo progreso
    Enrollment::updateOrCreate(
        ['user_id' => $user->id, 'course_id' => $course->id],
        ['progress' => $progress]
    );

    // Comprueba si el curso está completado
    if ($progress == 100) {
        // Aquí puedes agregar lógica adicional para la finalización del curso (por ejemplo, emisión de un certificado)
    }

    return redirect()->back()->with('success', 'Lesson marked as completed!');
}

    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('view', $course);

        $validatedData = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        $quizQuestions = $lesson->quizQuestions;
        $correctAnswers = 0;

        
        

        foreach ($quizQuestions as $question) {
            Log::info('Respuesta correcta: ' . $question->correct_answer);
            Log::info('Respuesta del usuario: ' . $validatedData['answers'][$question->id]);
        
            if (trim($validatedData['answers'][$question->id]) === trim($question->correct_answer)) {
                Log::info('¡Respuesta correcta!');
                $correctAnswers++;
            } else {
                Log::info('Respuesta incorrecta');
            }
        }

        

        $score = ($correctAnswers / $quizQuestions->count()) * 100;

        if ($score >= 70) {  // si 70% es que pasa
            LessonProgress::updateOrCreate(
                ['user_id' => Auth::id(), 'lesson_id' => $lesson->id],
                ['status' => 'completed']
            );
        }

        return response()->json([
            'success' => true,
            'quizResults' => [
                'score' => $score,
                'correctAnswers' => $correctAnswers,
                'totalQuestions' => $quizQuestions->count(),
            ]
        ]);
    }


    // METODOS PARA LOS COMENTARIOS

    public function addComment(Request $request, Course $course, Lesson $lesson)
{
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $comment = new Comment([
        'content' => $request->content,
        'user_id' => Auth::id(),
        'commentable_id' => $lesson->id,
        'commentable_type' => Lesson::class,
    ]);

    $lesson->comments()->save($comment);

    if ($request->expectsJson()) {
        return response()->json(['success' => true, 'comment' => $comment->load('user')]);
    }

    return back();
}

public function deleteComment(Request $request, Course $course, Lesson $lesson, Comment $comment)
{
    $this->authorize('delete', $comment);

    $comment->delete();


    return response()->json([
        'success' => true,
        'message' => 'Comentario Eliminado',
        'redirect' => route('courses.lesson', [$course, 'lesson' => $lesson->id])
    ]);

}

public function getComments(Course $course, Lesson $lesson, Request $request)
{
    $comments = $lesson->comments()->with('user')->paginate(10);

    return response()->json([
        'comments' => $comments->items(),
        'hasMore' => $comments->hasMorePages(),
    ]);
}



    public function generateCertificate(Course $course)
    {
        $this->authorize('view', $course);

        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        if ($enrollment->progress < 100) {
            return redirect()->back()->with('error', 'You need to complete the course to get the certificate.');
        }

        // Generate and save certificate logic here
        // ...

        return redirect()->back()->with('success', 'Certificate generated successfully!');
    }

    public function destroy(Course $course, Module $module, Lesson $lesson)
{
    if ($lesson->module_id !== $module->id || $module->course_id !== $course->id) {
        return response()->json(['message' => 'Lección no encontrada'], 404);
    }

    $lesson->delete();
    return response()->json(['message' => 'Lección eliminada exitosamente']);
}
}
