<x-appLayout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Mis Cursos</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($courses as $course)
                        <tr>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $course->title }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500">{{ Str::limit($course->description, 50) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500">{{ $course->category->name }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500">${{ number_format($course->price, 2) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500">{{ ucfirst($course->status) }}</td>
                            <td>
                                @if($course->modules->isNotEmpty() && $course->modules->first()->lessons->isNotEmpty())
                                @php
                                    $lesson = $course->modules->first()->lessons->first();
                                @endphp
                                <a href="{{ route('courses.lesson', [$course->id, $lesson->id]) }}" class="text-indigo-600 hover:text-indigo-900">Ver Curso</a>
                            @else
                                <span class="text-gray-500">Sin lecciones</span>
                            @endif
                            </td>
                            
                            <td class="py-4 px-6 text-sm text-gray-500 space-x-2">
                                <a href="{{ route('courses.edit', $course->id) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                <button onclick="confirmDelete({{ $course->id }})" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-sm text-gray-500 text-center">No has creado ningún curso aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(courseId) {
            if (confirm('¿Estás seguro de que quieres eliminar este curso?')) {
                fetch(`/courses/${courseId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ha ocurrido un error al intentar eliminar el curso.');
                });
            }
        }
    </script>
</x-appLayout>
