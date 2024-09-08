<x-appLayout>
    <div class="flex-grow p-6 md:overflow-y-auto md:p-12 bg-gray-100">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 text-gray-800">Editar Curso: {{ $course->title }}</h1>
            
            <form id="course-form" action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div id="alert-container" class="mb-4"></div>

                <!-- Información General del Curso -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Información General del Curso</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título del Curso</label>
                            <input type="text" name="title" id="title" value="{{ $course->title }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="category_id" id="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Curso</label>
                        <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">{{ $course->description }}</textarea>
                    </div>
                </div>
                
                <!-- Detalles del Curso -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Detalles del Curso</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio (USD)</label>
                            <input type="number" name="price" id="price" value="{{ $course->price }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                        </div>
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                            <select name="level" id="level" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Principiante</option>
                                <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                                <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                <option value="draft" {{ $course->status == 'draft' ? 'selected' : '' }}>Borrador</option>
                                <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Publicado</option>
                                <option value="archived" {{ $course->status == 'archived' ? 'selected' : '' }}>Archivado</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Imagen de Portada -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Imagen de Portada</h2>
                    <div class="mt-2">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Selecciona una nueva imagen (opcional)</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                    </div>
                    <div id="thumbnail-preview" class="mt-4 {{ $course->thumbnail ? '' : 'hidden' }}">
                        <img id="thumbnail-image" src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '#' }}" alt="Vista previa de la imagen" class="max-w-full h-auto rounded-lg shadow-md">
                    </div>
                </div>
                
                <!-- Módulos y Lecciones del Curso -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Módulos y Lecciones del Curso</h2>
                    <div id="modules-container" class="space-y-6">
                        @foreach($course->modules as $moduleIndex => $module)
                            <div id="module-{{ $moduleIndex + 1 }}" class="module bg-gray-100 rounded-lg p-6 mb-4 relative">
                                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="removeModule({{ $moduleIndex + 1 }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <h3 class="text-xl font-semibold mb-4 text-gray-700">Módulo {{ $moduleIndex + 1 }}</h3>
                                <input type="hidden" name="modules[{{ $moduleIndex }}][id]" value="{{ $module->id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Título del Módulo</label>
                                    <input type="text" name="modules[{{ $moduleIndex }}][title]" value="{{ $module->title }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del Módulo</label>
                                    <textarea name="modules[{{ $moduleIndex }}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">{{ $module->description }}</textarea>
                                </div>
                                <div class="mt-4">
                                    <h4 class="text-lg font-medium mb-2 text-gray-700">Lecciones</h4>
                                    <div id="lessons-container-{{ $moduleIndex + 1 }}" class="space-y-4">
                                        @foreach($module->lessons as $lessonIndex => $lesson)
                                            <div class="lesson bg-white rounded-lg p-4 relative">
                                                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="removeLesson(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <h5 class="text-lg font-medium mb-2 text-gray-700">Lección {{ $lessonIndex + 1 }}</h5>
                                                <input type="hidden" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][id]" value="{{ $lesson->id }}">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Lección</label>
                                                    <input type="text" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][title]" value="{{ $lesson->title }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                                </div>
                                                <div class="mt-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contenido</label>
                                                    <select name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][type]" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]" onchange="showContentField(this, {{ $moduleIndex }}, {{ $lessonIndex }})">
                                                        <option value="video" {{ $lesson->type == 'video' ? 'selected' : '' }}>Video</option>
                                                        <option value="text" {{ $lesson->type == 'text' ? 'selected' : '' }}>Texto</option>
                                                        <option value="quiz" {{ $lesson->type == 'quiz' ? 'selected' : '' }}>Cuestionario</option>
                                                    </select>
                                                </div>
                                                <div class="mt-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                                    <textarea name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">{{ $lesson->description }}</textarea>
                                                </div>
                                                <div class="mt-2 content-field" id="content-field-{{ $moduleIndex }}-{{ $lessonIndex }}">
                                                    @if($lesson->type == 'video')
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">URL del Video</label>
                                                        <input type="url" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][content]" value="{{ $lesson->content }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Duración (en minutos)</label>
                                                        <input type="number" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][duration]" value="{{ $lesson->duration }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                                                    @elseif($lesson->type == 'text')
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido de Texto</label>
                                                        <textarea name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][content]" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">{{ $lesson->content }}</textarea>
                                                    @elseif($lesson->type == 'quiz')
                                                        <div id="quiz-questions-{{ $moduleIndex }}-{{ $lessonIndex }}">
                                                            @foreach($lesson->quizQuestions as $questionIndex => $question)
                                                                <div class="quiz-question bg-gray-50 p-4 rounded-md mt-4">
                                                                    <h6 class="font-semibold mb-2">Pregunta {{ $questionIndex + 1 }}</h6>
                                                                    <input type="text" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][quiz][{{ $questionIndex }}][question]" value="{{ $question->question }}" placeholder="Pregunta" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                                                                    <textarea name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][quiz][{{ $questionIndex }}][answers]" rows="4" placeholder="Opciones (una por línea)" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">{{ $question->answers }}</textarea>
                                                                    <input type="text" name="modules[{{ $moduleIndex }}][lessons][{{ $lessonIndex }}][quiz][{{ $questionIndex }}][correct]" value="{{ $question->correct_answer }}" placeholder="Respuesta correcta" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="mt-2 px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="addQuizQuestion({{ $moduleIndex }}, {{ $lessonIndex }})">
                                                            Agregar Pregunta
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="mt-4 px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="addLesson({{ $moduleIndex + 1 }})">
                                        Agregar Lección
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-module" class="mt-6 px-4 py-2 bg-[var(--highlight-color)] text-white rounded-md hover:bg-[var(--hover-color)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--highlight-color)]">
                        Agregar Módulo
                    </button>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[var(--highlight-color)] text-white font-semibold rounded-md hover:bg-[var(--hover-color)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--highlight-color)]">
                        Actualizar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let moduleCount = {{ $course->modules->count() }};

        document.getElementById('add-module').addEventListener('click', addModule);
        document.getElementById('thumbnail').addEventListener('change', previewThumbnail);
        document.getElementById('course-form').addEventListener('submit', function(event) {
            event.preventDefault();
            submitForm();
        });

        function addModule() {
            moduleCount++;
            const moduleHtml = `
                <div id="module-${moduleCount}" class="module bg-gray-100 rounded-lg p-6 mb-4 relative">
                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="removeModule(${moduleCount})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Módulo ${moduleCount}</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título del Módulo</label>
                        <input type="text" name="modules[${moduleCount - 1}][title]" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del Módulo</label>
                        <textarea name="modules[${moduleCount - 1}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]"></textarea>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-lg font-medium mb-2 text-gray-700">Lecciones</h4>
                        <div id="lessons-container-${moduleCount}" class="space-y-4">
                            <!-- Las lecciones se agregarán dinámicamente aquí -->
                        </div>
                        <button type="button" class="mt-4 px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="addLesson(${moduleCount})">
                            Agregar Lección
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('modules-container').insertAdjacentHTML('beforeend', moduleHtml);
        }

        function removeModule(moduleId) {
            document.getElementById(`module-${moduleId}`).remove();
        }

        function addLesson(moduleId) {
            const lessonCount = document.querySelectorAll(`#lessons-container-${moduleId} .lesson`).length + 1;
            const lessonHtml = `
                <div class="lesson bg-white rounded-lg p-4 relative">
                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="removeLesson(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h5 class="text-lg font-medium mb-2 text-gray-700">Lección ${lessonCount}</h5>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Lección</label>
                        <input type="text" name="modules[${moduleId - 1}][lessons][${lessonCount - 1}][title]" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                    </div>
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contenido</label>
                        <select name="modules[${moduleId - 1}][lessons][${lessonCount - 1}][type]" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]" onchange="showContentField(this, ${moduleId - 1}, ${lessonCount - 1})">
                            <option value="video">Video</option>
                            <option value="text">Texto</option>
                            <option value="quiz">Cuestionario</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="modules[${moduleId - 1}][lessons][${lessonCount - 1}][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]"></textarea>
                    </div>
                    <div class="mt-2 content-field" id="content-field-${moduleId - 1}-${lessonCount - 1}">
                        <!-- El campo de contenido se mostrará aquí según el tipo seleccionado -->
                    </div>
                </div>
            `;
            document.getElementById(`lessons-container-${moduleId}`).insertAdjacentHTML('beforeend', lessonHtml);
            showContentField(document.querySelector(`#lessons-container-${moduleId} .lesson:last-child select`), moduleId - 1, lessonCount - 1);
        }

        function removeLesson(button) {
            button.closest('.lesson').remove();
        }

        function showContentField(select, moduleId, lessonId) {
            const contentField = document.getElementById(`content-field-${moduleId}-${lessonId}`);
            const lessonType = select.value;
            let fieldHtml = '';

            switch (lessonType) {
                case 'video':
                    fieldHtml = `
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL del Video</label>
                        <input type="url" name="modules[${moduleId}][lessons][${lessonId}][content]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Duración (en minutos)</label>
                        <input type="number" name="modules[${moduleId}][lessons][${lessonId}][duration]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]">
                    `;
                    break;
                case 'text':
                    fieldHtml = `
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido de Texto</label>
                        <textarea name="modules[${moduleId}][lessons][${lessonId}][content]" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--highlight-color)] focus:border-[var(--highlight-color)]"></textarea>
                    `;
                    break;
                case 'quiz':
                    fieldHtml = `
                        <div id="quiz-questions-${moduleId}-${lessonId}">
                            <!-- Las preguntas del cuestionario se agregarán aquí -->
                        </div>
                        <button type="button" class="mt-2 px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="addQuizQuestion(${moduleId}, ${lessonId})">
                            Agregar Pregunta
                        </button>
                    `;
                    break;
            }

            contentField.innerHTML = fieldHtml;
            if (lessonType === 'quiz') {
                addQuizQuestion(moduleId, lessonId);
            }
        }

        function addQuizQuestion(moduleId, lessonId) {
            const questionCount = document.querySelectorAll(`#quiz-questions-${moduleId}-${lessonId} .quiz-question`).length + 1;
            const questionHtml = `
                <div class="quiz-question bg-gray-50 p-4 rounded-md mt-4">
                    <h6 class="font-semibold mb-2">Pregunta ${questionCount}</h6>
                    <input type="text" name="modules[${moduleId}][lessons][${lessonId}][quiz][${questionCount - 1}][question]" placeholder="Pregunta" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                    <textarea name="modules[${moduleId}][lessons][${lessonId}][quiz][${questionCount - 1}][answers]" rows="4" placeholder="Opciones (una por línea)" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2"></textarea>
                    <input type="text" name="modules[${moduleId}][lessons][${lessonId}][quiz][${questionCount - 1}][correct]" placeholder="Respuesta correcta" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            `;
            document.getElementById(`quiz-questions-${moduleId}-${lessonId}`).insertAdjacentHTML('beforeend', questionHtml);
        }

        function previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumbnail-image').src = e.target.result;
                    document.getElementById('thumbnail-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function submitForm() {
            const form = document.getElementById('course-form');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server error');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showAlert('error', 'Por favor, corrige los errores en el formulario.');
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Ha ocurrido un error. Por favor, inténtalo de nuevo.');
            });
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            alertContainer.innerHTML = `
                <div class="${alertClass} px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">${type === 'success' ? '¡Éxito!' : '¡Error!'}</strong>
                    <span class="block sm:inline">${message}</span>
                </div>
            `;

            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function displayErrors(errors) {
            if (!errors || typeof errors !== 'object') {
                console.error('Invalid errors object:', errors);
                return;
            }

            // Eliminar mensajes de error anteriores
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

            // Mostrar nuevos mensajes de error
            for (const [key, messages] of Object.entries(errors)) {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    field.classList.add('border-red-500');
                    const errorMessage = document.createElement('p');
                    errorMessage.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
                    errorMessage.textContent = Array.isArray(messages) ? messages[0] : messages;
                    field.parentNode.insertBefore(errorMessage, field.nextSibling);
                }
            }

            // Desplazarse al primer error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    </script>
</x-appLayout>