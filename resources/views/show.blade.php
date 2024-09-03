<x-appLayout>
    <div class="max-w-[600px] w-[90%] mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-6 text-center">{{ $course->title }}</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center" role="alert">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="mb-4">
                    <strong class="block text-gray-700 text-lg font-semibold mb-2">Descripción:</strong>
                    <p class="text-gray-600 break-words">{{ $course->description }}</p>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700 text-lg font-semibold mb-2">Categoría:</strong>
                    <p class="text-gray-600">{{ $course->category->name }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <strong class="block text-gray-700 text-lg font-semibold mb-2">Precio:</strong>
                        <p class="text-gray-600">${{ number_format($course->price, 2) }}</p>
                    </div>
                    <div>
                        <strong class="block text-gray-700 text-lg font-semibold mb-2">Nivel:</strong>
                        <p class="text-gray-600">{{ ucfirst($course->level) }}</p>
                    </div>
                    <div>
                        <strong class="block text-gray-700 text-lg font-semibold mb-2">Estado:</strong>
                        <p class="text-gray-600">{{ ucfirst($course->status) }}</p>
                    </div>
                </div>
                @if($course->thumbnail_path)
                    <div class="mb-6">
                        <strong class="block text-gray-700 text-lg font-semibold mb-2">Imagen del curso:</strong>
                        <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-auto object-cover rounded-md">
                    </div>
                @endif
            </div>
        </div>

        <h2 class="text-3xl font-bold mb-6 text-center">Lecciones</h2>
        <div class="space-y-6">
            @foreach($course->lessons as $lesson)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-3">{{ $lesson->title }}</h3>
                        <p class="text-gray-600 mb-2"><strong>Tipo:</strong> {{ ucfirst($lesson->type) }}</p>
                        @if($lesson->type === 'video')
                            <p class="text-gray-600 mb-2"><strong>Duración:</strong> {{ $lesson->duration }} minutos</p>
                        @endif
                        <p class="text-gray-600 break-words"><strong>Contenido:</strong> {{ Str::limit($lesson->content, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-appLayout>
