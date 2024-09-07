<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

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


    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $course->update($validatedData);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $course->thumbnail_path = $thumbnailPath;
            $course->save();
        }

        return redirect()->route('show', $course)->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado exitosamente');
    }
}
