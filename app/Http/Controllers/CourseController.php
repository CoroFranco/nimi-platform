<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $courses = Course::where('status', 'published')->paginate(12);
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('myCourses', compact('categories'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'level' => 'required|in:beginner,intermediate,advanced',
        'status' => 'required|in:draft,published',
        'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'lessons' => 'required|array|min:1',
        'lessons.*.title' => 'required|max:255',
        'lessons.*.type' => 'required|in:video,text,quiz',
        'lessons.*.content' => 'required',
        'lessons.*.duration' => 'required_if:lessons.*.type,video|nullable|integer',
    ]);

    $course = new Course($validatedData);
    $course->instructor_id = Auth::id();

    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
        $course->thumbnail_path = $thumbnailPath;
    }

    $course->save();

    foreach ($request->lessons as $index => $lessonData) {
        $lesson = $course->lessons()->create([
            'title' => $lessonData['title'],
            'type' => $lessonData['type'],
            'content' => $lessonData['content'],
            'less_order' => $index + 1,
            'duration' => $lessonData['type'] === 'video' ? $lessonData['duration'] : null,
        ]);
    }

    return redirect()->route('courses.show', $course)->with('success', 'Curso creado exitosamente');
}

    public function show(Course $course)
{
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