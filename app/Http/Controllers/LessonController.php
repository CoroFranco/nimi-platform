<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LessonController extends Controller
{
    use AuthorizesRequests;

    public function show(Course $course, Lesson $lesson)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        $course->load('modules.lessons');

        $allLessons = $course->modules->flatMap->lessons->sortBy('less_order');
        $currentIndex = $allLessons->search($lesson);
        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        $completedLessonIds = LessonProgress::where('user_id', Auth::id())
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

        // Mark the lesson as completed
        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'completed']
        );

        // Update the course progress
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('status', 'completed')
            ->count();

        $progress = ($completedLessons / $totalLessons) * 100;

        // Update or create the enrollment record with the new progress
        Enrollment::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['progress' => $progress]
        );

        // Check if the course is completed
        if ($progress == 100) {
            // You can add additional logic here for course completion (e.g., issuing a certificate)
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
            if (
                isset($validatedData['answers'][$question->id]) &&
                $validatedData['answers'][$question->id] === $question->correct_answer
            ) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $quizQuestions->count()) * 100;

        if ($score >= 70) {  // Assuming 70% is the passing score
            LessonProgress::updateOrCreate(
                ['user_id' => Auth::id(), 'lesson_id' => $lesson->id],
                ['status' => 'completed']
            );
        }

        return redirect()->back()->with('quizResults', [
            'score' => $score,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $quizQuestions->count(),
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
}
