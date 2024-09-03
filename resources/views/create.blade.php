<x-applayout>
    <div class="w-full min-h-screen bg-gray-100">
        <div class="py-16">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8 bg-white border-b border-gray-200">
                        <h1 class="text-5xl font-bold text-gray-900 mb-8">Crear Nuevo Curso</h1>
                        
                        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                            @csrf
                            <!-- Información General del Curso -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label for="title" class="block text-xl font-medium text-gray-700 mb-3">Título del Curso</label>
                                    <input type="text" name="title" id="title" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label for="category" class="block text-xl font-medium text-gray-700 mb-3">Categoría</label>
                                    <select name="category_id" id="category" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-xl font-medium text-gray-700 mb-3">Descripción del Curso</label>
                                <textarea name="description" id="description" rows="6" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required></textarea>
                            </div>

                            <!-- Precio, Nivel y Estado -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <label for="price" class="block text-xl font-medium text-gray-700 mb-3">Precio (en USD)</label>
                                    <input type="number" name="price" id="price" min="0" step="0.01" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label for="level" class="block text-xl font-medium text-gray-700 mb-3">Nivel</label>
                                    <select name="level" id="level" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                        <option value="beginner">Principiante</option>
                                        <option value="intermediate">Intermedio</option>
                                        <option value="advanced">Avanzado</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-xl font-medium text-gray-700 mb-3">Estado</label>
                                    <select name="status" id="status" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                        <option value="draft">Borrador</option>
                                        <option value="published">Publicado</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Imagen de Portada -->
                            <div>
                                <label for="thumbnail" class="block text-xl font-medium text-gray-700 mb-3">Imagen de Portada</label>
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>

                            <!-- Contenido del Curso -->
                            <div class="bg-emerald-50 rounded-lg p-8">
                                <h2 class="text-3xl font-semibold text-emerald-800 mb-6">Contenido del Curso</h2>
                                <div id="lessons" class="space-y-8">
                                    <!-- Las lecciones se agregarán dinámicamente aquí -->
                                </div>
                                <button type="button" id="addLesson" class="mt-6 px-6 py-3 bg-emerald-600 text-white text-xl rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-300">
                                    Agregar Nueva Lección
                                </button>
                            </div>

                            <!-- Botón para Crear el Curso -->
                            <div class="flex justify-end">
                                <button type="submit" class="px-8 py-4 bg-emerald-600 text-white text-xl font-semibold rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-300">
                                    Crear Curso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lessonCount = 0;

        document.getElementById('addLesson').addEventListener('click', function() {
            lessonCount++;
            const lessonHtml = `
                <div class="lesson bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-2xl font-semibold text-emerald-800 mb-4">Lección ${lessonCount}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">Título de la Lección</label>
                            <input type="text" name="lessons[${lessonCount}][title]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">Tipo de Contenido</label>
                            <select name="lessons[${lessonCount}][type]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" onchange="showContentField(this)">
                                <option value="video">Video</option>
                                <option value="text">Texto</option>
                                <option value="quiz">Cuestionario</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 content-field hidden">
                        <!-- El campo de contenido se mostrará aquí según el tipo seleccionado -->
                    </div>
                </div>
            `;
            document.getElementById('lessons').insertAdjacentHTML('beforeend', lessonHtml);
        });

        function showContentField(select) {
            const contentField = select.closest('.lesson').querySelector('.content-field');
            const lessonType = select.value;
            let fieldHtml = '';

            switch (lessonType) {
                case 'video':
                    fieldHtml = `
                        <label class="block text-lg font-medium text-gray-700 mb-2">Subir Video</label>
                        <input type="file" name="lessons[${lessonCount}][content]" accept="video/*" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <label class="block text-lg font-medium text-gray-700 mt-4 mb-2">Duración (en minutos)</label>
                        <input type="number" name="lessons[${lessonCount}][duration]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    `;
                    break;
                case 'text':
                    fieldHtml = `
                        <label class="block text-lg font-medium text-gray-700 mb-2">Contenido de Texto</label>
                        <textarea name="lessons[${lessonCount}][content]" rows="6" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2                         focus:border-emerald-500" required></textarea>
                    `;
                    break;
                case 'quiz':
                    fieldHtml = `
                        <label class="block text-lg font-medium text-gray-700 mb-2">Cuestionario</label>
                        <div class="space-y-4">
                            <div class="question bg-gray-100 p-4 rounded-md shadow-inner">
                                <label class="block text-md font-medium text-gray-700 mb-2">Pregunta</label>
                                <input type="text" name="lessons[${lessonCount}][quiz][questions][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <label class="block text-md font-medium text-gray-700 mt-4 mb-2">Respuestas (separadas por comas)</label>
                                <input type="text" name="lessons[${lessonCount}][quiz][answers][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <label class="block text-md font-medium text-gray-700 mt-4 mb-2">Respuesta Correcta</label>
                                <input type="text" name="lessons[${lessonCount}][quiz][correct][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>
                        </div>
                        <button type="button" class="mt-4 px-6 py-2 bg-emerald-500 text-white text-md rounded-md hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-300" onclick="addQuestion(this)">Agregar Otra Pregunta</button>
                    `;
                    break;
            }

            contentField.innerHTML = fieldHtml;
            contentField.classList.remove('hidden');
        }

        function addQuestion(button) {
            const questionHtml = `
                <div class="question bg-gray-100 p-4 rounded-md shadow-inner mt-4">
                    <label class="block text-md font-medium text-gray-700 mb-2">Pregunta</label>
                    <input type="text" name="lessons[${lessonCount}][quiz][questions][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    <label class="block text-md font-medium text-gray-700 mt-4 mb-2">Respuestas (separadas por comas)</label>
                    <input type="text" name="lessons[${lessonCount}][quiz][answers][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    <label class="block text-md font-medium text-gray-700 mt-4 mb-2">Respuesta Correcta</label>
                    <input type="text" name="lessons[${lessonCount}][quiz][correct][]" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                </div>
            `;
            button.closest('.lesson').querySelector('.content-field').insertAdjacentHTML('beforeend', questionHtml);
        }
    </script>
</x-applayout>

