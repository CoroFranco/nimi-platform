<x-appLayout>
    <div class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6 bg-gray-800 text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
                    <p class="text-gray-300">{{ $course->description }}</p>
                    <div class="mt-4 flex items-center">
                        <img src="{{ $course->instructor->avatar }}" alt="{{ $course->instructor->name }}" class="w-10 h-10 rounded-full mr-4">
                        <span>Instructor: {{ $course->instructor->name }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar with course content -->
                    <div class="w-full md:w-1/4 bg-gray-100 p-6">
                        <h2 class="text-xl font-semibold mb-4">Course Content</h2>
                        <div class="space-y-4">
                            @foreach ($course->modules as $module)
                                <div>
                                    <h3 class="font-medium text-lg mb-2">{{ $module->title }}</h3>
                                    <ul class="space-y-2">
                                        @foreach ($module->lessons as $lesson)
                                            <li>
                                                <a href="{{ route('courses.lesson', ['course' => $course->id, 'lesson' => $lesson->id]) }}" 
                                                   class="flex items-center p-2 rounded {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-200' }}">
                                                    @switch($lesson->type)
                                                        @case('video')
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @break
                                                        @case('text')
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            @break
                                                        @case('quiz')
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @break
                                                    @endswitch
                                                    <span class="flex-1">{{ $lesson->title }}</span>
                                                    <span class="text-sm text-gray-500">{{ $lesson->duration }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Main content area -->
                    <div class="w-full md:w-3/4 p-6">
                        @if($currentLesson)
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h2 class="text-2xl font-bold">{{ $currentLesson->title }}</h2>
                                    <span class="text-sm text-gray-500">{{ $currentLesson->duration }}</span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%;"></div>
                                </div>
                                <div class="text-right mt-1 text-sm text-gray-600">
                                    {{ $completedLessons }} of {{ $totalLessons }} lessons completed
                                </div>
                            </div>
                            
                            <!-- Lesson content -->
                            <div class="mb-8">
                                @switch($currentLesson->type)
                                    @case('video')
                                        <div class="aspect-w-16 aspect-h-9 mb-4">
                                            <iframe src="{{ $currentLesson->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        @break
                                    @case('text')
                                        <div class="prose max-w-none">
                                            {!! $currentLesson->content !!}
                                        </div>
                                        @break
                                    @case('quiz')
                                        <form action="{{ route('courses.submit-quiz', ['course' => $course->id, 'lesson' => $currentLesson->id]) }}" method="POST">
                                            @csrf
                                            @foreach ($currentLesson->quizQuestions as $question)
                                                <div class="mb-4">
                                                    <p class="font-semibold mb-2">{{ $question->question }}</p>
                                                    @foreach (explode("\n", $question->answers) as $option)
                                                        <label class="block mb-2">
                                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="mr-2">
                                                            {{ $option }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Quiz</button>
                                        </form>
                                        @break
                                @endswitch
                            </div>
                            
                            <!-- Navigation buttons -->
                            <div class="flex justify-between">
                                @if ($previousLesson)
                                    <a href="{{ route('courses.lesson', ['course' => $course->id, 'lesson' => $previousLesson->id]) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        Previous Lesson
                                    </a>
                                @endif
                                @if ($nextLesson)
                                    <a href="{{ route('courses.lesson', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center ml-auto">
                                        Next Lesson
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @else
                                    <form action="{{ route('courses.complete', ['course' => $course->id]) }}" method="POST" class="ml-auto">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Complete Course</button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12">
                                <h2 class="text-2xl font-bold mb-4">No lessons available</h2>
                                <p class="text-gray-600">This course doesn't have any lessons yet. Check back later!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-appLayout>
