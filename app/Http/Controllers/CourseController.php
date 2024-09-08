<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class CourseController extends Controller
{
    use AuthorizesRequests;


    public function store(Request $request)
    {

        try {
            Log::info('Iniciando la validación de datos');
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'level' => 'required|in:beginner,intermediate,advanced',
                'status' => 'required|in:draft,published,archived',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'modules' => 'required|array|min:1',
                'modules.*.title' => 'required|max:255',
                'modules.*.description' => 'nullable|string',
                'modules.*.lessons' => 'required|array|min:1',
                'modules.*.lessons.*.title' => 'required|max:255',
                'modules.*.lessons.*.type' => 'required|in:video,text,quiz',
                'modules.*.lessons.*.content' => 'required_unless:modules.*.lessons.*.type,quiz',
                'modules.*.lessons.*.duration' => 'required_if:modules.*.lessons.*.type,video|nullable|integer',
                'modules.*.lessons.*.description' => 'nullable|string',
                'modules.*.lessons.*.quiz' => 'required_if:modules.*.lessons.*.type,quiz|array',
                'modules.*.lessons.*.quiz.*.question' => 'required_if:modules.*.lessons.*.type,quiz|string',
                'modules.*.lessons.*.quiz.*.answers' => 'required_if:modules.*.lessons.*.type,quiz|string',
                'modules.*.lessons.*.quiz.*.correct' => 'required_if:modules.*.lessons.*.type,quiz|string',
            ]);

            $course = new Course($validatedData);
            $course->instructor_id = Auth::id();

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
                $course->thumbnail_path = $thumbnailPath;
            }

            $course->save();

            foreach ($request->modules as $moduleIndex => $moduleData) {
                $module = $course->modules()->create([
                    'title' => $moduleData['title'],
                    'description' => $moduleData['description'] ?? null,
                    'mod_order' => $moduleIndex + 1,
                ]);

                foreach ($moduleData['lessons'] as $lessonIndex => $lessonData) {
                    $lesson = $module->lessons()->create([
                        'title' => $lessonData['title'],
                        'type' => $lessonData['type'],
                        'content' => $lessonData['type'] !== 'quiz' ? $lessonData['content'] : null,
                        'less_order' => $lessonIndex + 1,
                        'duration' => $lessonData['type'] === 'video' ? $lessonData['duration'] : null,
                        'description' => $lessonData['description'] ?? null,
                    ]);

                    if ($lessonData['type'] === 'quiz' && isset($lessonData['quiz'])) {
                        foreach ($lessonData['quiz'] as $questionData) {
                            $lesson->quizQuestions()->create([
                                'question' => $questionData['question'],
                                'answers' => $questionData['answers'],
                                'correct_answer' => $questionData['correct'],
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Curso creado exitosamente',
                'redirect' => route('courses.lesson', [$course, 'lesson' => $lesson->id])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación: ', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Errores de validación'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ha ocurrido un error al crear el curso. Por favor, completa todos los datos e inténtalo de nuevo.'
            ], 500);
        }
    }

    public function show(Course $course)
    {
        $course->load('modules.lessons');
        return view('show', compact('course'));
    }


    public function update(Request $request, Course $course)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'level' => 'required|in:beginner,intermediate,advanced',
        'status' => 'required|in:draft,published,archived',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'modules' => 'required|array|min:1',
        'modules.*.title' => 'required|string|max:255',
        'modules.*.description' => 'nullable|string',
        'modules.*.lessons' => 'required|array|min:1',
        'modules.*.lessons.*.title' => 'required|string|max:255',
        'modules.*.lessons.*.type' => 'required|in:video,text,quiz',
        'modules.*.lessons.*.description' => 'nullable|string',
        'modules.*.lessons.*.content' => 'required_if:modules.*.lessons.*.type,video,text',
        'modules.*.lessons.*.duration' => 'required_if:modules.*.lessons.*.type,video|nullable|integer|min:1',
        'modules.*.lessons.*.quiz' => 'required_if:modules.*.lessons.*.type,quiz|array',
        'modules.*.lessons.*.quiz.*.question' => 'required_if:modules.*.lessons.*.type,quiz|string',
        'modules.*.lessons.*.quiz.*.answers' => 'required_if:modules.*.lessons.*.type,quiz|string',
        'modules.*.lessons.*.quiz.*.correct' => 'required_if:modules.*.lessons.*.type,quiz|string',
    ]);

    DB::beginTransaction();

    try {
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'level' => $request->level,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $course->update(['thumbnail' => $thumbnailPath]);
        }

        // Update or create modules and lessons
        $existingModuleIds = $course->modules->pluck('id')->toArray();
        $updatedModuleIds = [];

        foreach ($request->modules as $moduleData) {
            $module = isset($moduleData['id'])
                ? Module::find($moduleData['id'])
                : new Module();

            $module->fill([
                'title' => $moduleData['title'],
                'description' => $moduleData['description'] ?? null,
                'course_id' => $course->id,
            ]);

            $module->save();
            $updatedModuleIds[] = $module->id;

            $existingLessonIds = $module->lessons->pluck('id')->toArray();
            $updatedLessonIds = [];

            foreach ($moduleData['lessons'] as $lessonData) {
                $lesson = isset($lessonData['id'])
                    ? Lesson::find($lessonData['id'])
                    : new Lesson();

                $lesson->fill([
                    'title' => $lessonData['title'],
                    'type' => $lessonData['type'],
                    'description' => $lessonData['description'] ?? null,
                    'content' => $lessonData['content'] ?? null,
                    'duration' => $lessonData['duration'] ?? null,
                    'module_id' => $module->id,
                ]);

                $lesson->save();
                $updatedLessonIds[] = $lesson->id;

                if ($lesson->type === 'quiz') {
                    $this->updateOrCreateQuizQuestions($lesson, $lessonData['quiz'] ?? []);
                }
            }

            // Delete lessons that are no longer in the updated data
            Lesson::whereIn('id', array_diff($existingLessonIds, $updatedLessonIds))->delete();
        }

        // Delete modules that are no longer in the updated data
        Module::whereIn('id', array_diff($existingModuleIds, $updatedModuleIds))->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Curso actualizado exitosamente.',
            'redirect' => route('courses.edit', $course),
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el curso.',
            'errors' => ['general' => [$e->getMessage()]],
        ], 500);
    }
}

private function updateOrCreateQuizQuestions($lesson, $quizData)
{
    $existingQuestionIds = $lesson->quizQuestions->pluck('id')->toArray();
    $updatedQuestionIds = [];

    foreach ($quizData as $questionData) {
        $question = isset($questionData['id'])
            ? QuizQuestion::find($questionData['id'])
            : new QuizQuestion();

        $question->fill([
            'lesson_id' => $lesson->id,
            'question' => $questionData['question'],
            'answers' => $questionData['answers'],
            'correct_answer' => $questionData['correct'],
        ]);

        $question->save();
        $updatedQuestionIds[] = $question->id;
    }

    // Delete questions that are no longer in the updated data
    QuizQuestion::whereIn('id', array_diff($existingQuestionIds, $updatedQuestionIds))->delete();
}



public function destroy(Course $course)
{
    if ($course->enrollments()->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'No se puede eliminar el curso porque tiene inscripciones asociadas.'
        ], 400);
    }

    DB::beginTransaction();

    try {
        // Delete all related quiz questions, lesson progress, and lessons
        $course->modules->each(function ($module) {
            $module->lessons->each(function ($lesson) {
                $lesson->quizQuestions()->delete();
                $lesson->lessonProgress()->delete();
            });
            $module->lessons()->delete();
        });

        // Delete all related modules
        $course->modules()->delete();

        // Delete the course itself
        $course->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Curso y todos sus datos relacionados han sido eliminados exitosamente.'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el curso: ' . $e->getMessage()
        ], 500);
    }
}

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        $course->load('modules.lessons');
        return view('edit', compact('course', 'categories'));
    }
}
