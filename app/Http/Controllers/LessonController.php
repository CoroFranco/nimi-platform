<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LessonController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|max:255',
            'type' => 'required|in:video,text,quiz',
            'content' => 'required',
            'duration' => 'required_if:type,video|nullable|integer',
        ]);

        $course = Course::findOrFail($validatedData['course_id']);
        $this->authorize('update', $course);

        $lesson = new Lesson($validatedData);
        $lesson->less_order = $course->lessons()->count() + 1;
        $lesson->save();

        return redirect()->route('courses.edit', $course)->with('success', 'Lección agregada exitosamente');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:video,text,quiz',
            'content' => 'required',
            'duration' => 'required_if:type,video|nullable|integer',
        ]);

        $lesson->update($validatedData);

        return redirect()->route('courses.edit', $lesson->course)->with('success', 'Lección actualizada exitosamente');
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $course = $lesson->course;
        $lesson->delete();

        // Reordenar las lecciones restantes
        $course->lessons()->where('less_order', '>', $lesson->less_order)->decrement('less_order');

        return redirect()->route('courses.edit', $course)->with('success', 'Lección eliminada exitosamente');
    }
}