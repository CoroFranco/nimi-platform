<x-applayout>
    <main class="flex-1 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row items-center mb-16">
                <div class="lg:w-1/2 lg:pr-12 mb-8 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        Descubre. Aprende.<br>
                        <span class="text-emerald-600">Crea.</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">Nimi: El marketplace de conocimientos donde cada pasión encuentra su lugar.</p>
                    <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#" class="inline-block bg-emerald-600 text-white font-semibold py-3 px-8 rounded-md hover:bg-emerald-700 transition duration-300">
                            Explorar Cursos
                        </a>
                        <a href="#" class="inline-block bg-gray-200 text-gray-800 font-semibold py-3 px-8 rounded-md hover:bg-gray-300 transition duration-300">
                            Enseñar en Nimi
                        </a>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="grid grid-cols-2 gap-4">
                        <img src="https://via.placeholder.com/300x200" alt="Curso 1" class="rounded-lg shadow-md">
                        <img src="https://via.placeholder.com/300x200" alt="Curso 2" class="rounded-lg shadow-md mt-8">
                        <img src="https://via.placeholder.com/300x200" alt="Curso 3" class="rounded-lg shadow-md">
                        <img src="https://via.placeholder.com/300x200" alt="Curso 4" class="rounded-lg shadow-md mt-8">
                    </div>
                </div>
            </div>

            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Explora Mundos de Conocimiento</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['Arte', 'Tecnología', 'Cocina', 'Música', 'Negocios', 'Idiomas', 'Fotografía', 'Bienestar'] as $category)
                        <a href="#" class="bg-gray-100 rounded-lg p-6 text-center hover:bg-emerald-100 transition duration-300">
                            <span class="block text-xl font-semibold text-gray-800">{{ $category }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-8 mb-16">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Comparte tu Pasión</h2>
                    <p class="text-gray-600 mb-6">En Nimi, creemos que todos tienen algo valioso que enseñar. ¿Listo para compartir tu conocimiento?</p>
                    <a href="#" class="inline-block bg-emerald-600 text-white font-semibold py-3 px-8 rounded-md hover:bg-emerald-700 transition duration-300">
                        Crea tu Primer Curso
                    </a>
                </div>
            </div>

            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Cursos Destacados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach(range(1, 3) as $index)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-xl">
                            <img src="https://via.placeholder.com/400x225" alt="Curso {{ $index }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Curso Inspirador {{ $index }}</h3>
                                <p class="text-gray-600 mb-4">Descubre habilidades únicas de creadores apasionados.</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-emerald-600 font-semibold">$24.99</span>
                                    <a href="#" class="text-gray-600 hover:text-emerald-600">Más información →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Por qué Crear en Nimi</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Alcanza a estudiantes de todo el mundo</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Herramientas fáciles de usar para crear contenido</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Gana dinero compartiendo tu conocimiento</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Únete a una comunidad de creadores apasionados</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-emerald-100 rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-emerald-800 mb-4">Comienza tu Viaje como Creador</h3>
                    <p class="text-emerald-700 mb-6">Únete a miles de creadores que ya están compartiendo su pasión y ganando con Nimi.</p>
                    <a href="#" class="inline-block bg-emerald-600 text-white font-semibold py-3 px-8 rounded-md hover:bg-emerald-700 transition duration-300">
                        Empieza Ahora
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-appLayout>