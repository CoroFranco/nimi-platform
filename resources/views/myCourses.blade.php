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
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="border-b">
                            <td class="py-4 px-6 text-sm text-gray-700 font-semibold">{{ $course->title }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ Str::limit($course->description, 50) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ $course->category->name }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">${{ number_format($course->price, 2) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ ucfirst($course->status) }}</td>
                            <td class="py-4 px-6 text-sm">
                                <a href="{{ route('courses.edit', $course->id) }}" class="text-blue-600 hover:text-blue-900">Editar</a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center text-sm text-gray-600">No has creado ningún curso aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-appLayout>
