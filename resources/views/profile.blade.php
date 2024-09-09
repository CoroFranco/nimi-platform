<x-appLayout>

    <main class="flex-grow p-6 md:p-8 bg-[var(--bg-color)]">
        <div class="container">
            <!-- Encabezado del perfil -->
            <div class="bg-gradient-to-r from-[var(--main-color)] to-[var(--hover-color)] rounded-lg shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row  items-center">
                    <div class="relative mb-4 md:mb-0 md:mr-20 ">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Auth::user()->profile_photo_path }}" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover border-4 border-white">
                        @else
                            <div class="w-32 h-32 rounded-full bg-white flex items-center justify-center text-[var(--hover-color)] text-4xl font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <label for="profile-photo" class="absolute bottom-0 right-0 bg-white text-[var(--hover-color)] p-2 rounded-full cursor-pointer hover:bg-indigo-100 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" id="profile-photo" class="hidden" accept="image/*">
                    </div>
                    <div class="text-center md:text-left flex-grow">
                        <h1 class="text-[2.6 rem] font-bold text-white mb-2">{{ Auth::user()->name }}</h1>
                        <p class="text-[1.2rem] text-[var(--text-color-index)] mb-2">{{ Auth::user()->email }}</p>
                        <p class="text-[1.2rem] text-[var(--text-color-index)] mb-4">Miembro desde {{ Auth::user()->created_at->format('F Y') }}</p>
                        <button id="edit-profile-btn" class="font-bold text-[1.2rem]  bg-white text-[var(--hover-color)] px-4 py-2 rounded-md hover:bg-indigo-100 transition duration-300">Editar perfil</button>
                    </div>
                </div>
                
            </div>
            @if(session('success'))
            <div class="alert text-center text-[2rem] my-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative ">
                {{ session('success') }}
            </div>
        @endif
        

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="alert text-center text-[2rem] my-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative ">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    @php
        $coursesInProgress = 0;
        $coursesCompleted = 0;
        foreach($courses as $course){
            if ($courseProgress[$course->id] != 100) {
            $coursesInProgress++;
        }else {
            $coursesCompleted++;
        }
        }
            
    @endphp
            <!-- Estadísticas del usuario -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <p class="text-3xl font-bold text-[var(--hover-color)]">{{$coursesInProgress}}</p>
                    <p class="text-gray-600">Cursos en progreso</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <p class="text-3xl font-bold text-[var(--hover-color)]">{{$coursesCompleted}}</p>
                    <p class="text-gray-600">Cursos completados</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <p class="text-3xl font-bold text-[var(--hover-color)]">{{$coursesCreated}}</p>
                    <p class="text-gray-600">Cursos creados</p>
                </div>
            </div>
    
            <!-- Pestañas para diferentes secciones -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex justify-center" aria-label="Tabs">
                        <button class="text-[2rem] hover:text-[var(--hover-color)] text-[var(--hover-color)] hover:border-[var(--hover-color)] border-[var(--hover-color)] whitespace-nowrap py-4 px-1 border-b-2 font-medium" data-tab="learning">
                            Aprendizaje
                        </button>
                    </nav>
                </div>
            </div>
    
            <!-- Contenido de las pestañas -->
            <div id="learning" class="tab-content">
                <section class="mb-8 text-center">
                    <h2 class="text-[2rem] font-bold text-gray-800 mb-10">Cursos en progreso</h2>
                    @if($courses-> isEmpty())
                    <h2 class="mb-10">No tiene cursos en este momento</h2>
                    <div>
                        <a class="px-6 py-2 bg-[var(--highlight-color)] text-[1.5rem] text-[var(--text-color-index)] mt-20 hover:bg-[var(--hover-color)]" href="/explorer">Busca un curso para ti</a>
                    </div>
                        
                        @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        @foreach($courses as $course) 
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                @php
                                    // Obtener la siguiente lección después de omitir las completadas
                                    $nextLesson = $lessonsByCourse[$course->id]
                                        ->sortBy('less_order')
                                        ->skip($completedLessonsCountByCourse[$course->id]+1)
                                        ->first();
                                @endphp

                                <a href="/courses/{{$course->id}}/lessons/{{$nextLesson ? $nextLesson->id : $lessonsByCourse[$course->id]->last()->id}}">
                                    <h3 class="font-bold text-lg text-[var(--hover-color)] mb-2">{{$course->title}}</h3>
                                </a>                                
                              <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Progreso: {{$courseProgress[$course->id]}}%</span>
                                    <span class="text-[var(--hover-color)]">{{$completedLessonsCountByCourse[$course->id]}}/{{$lessonsCountByCourse[$course->id]}} lecciones</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-[var(--hover-color)] h-2.5 rounded-full" style="width: {{$courseProgress[$course->id]}}%"></div>
                                </div>
                            </div>
                        
                        @endforeach
                        @endif
                    </div>
                </section>
            </div>
    
            <div id="teaching" class="tab-content hidden">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Mis cursos creados</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="font-bold text-lg text-[var(--hover-color)] mb-2">Fundamentos de JavaScript</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Estudiantes: 150</span>
                                <span class="text-[var(--hover-color)]">Calificación: 4.8</span>
                            </div>
                            <button class="mt-2 bg-[var(--hover-color)] text-white px-4 py-2 rounded-md hover:bg-[var(--hover-color)] transition duration-300">Editar curso</button>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="font-bold text-lg text-[var(--hover-color)] mb-2">Desarrollo Web Responsivo</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Estudiantes: 120</span>
                                <span class="text-[var(--hover-color)]">Calificación: 4.7</span>
                            </div>
                            <button class="mt-2 bg-[var(--hover-color)] text-white px-4 py-2 rounded-md hover:bg-[var(--hover-color)] transition duration-300">Editar curso</button>
                        </div>
                    </div>
                </section>
            </div>
    
            <div id="purchases" class="tab-content hidden">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Mis compras</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="font-bold text-lg text-[var(--hover-color)] mb-2">Curso Avanzado de Python</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Comprado el: 15/04/2024</span>
                                <span class="text-[var(--hover-color)]">$49.99</span>
                            </div>
                            <button class="mt-2 bg-[var(--hover-color)] text-white px-4 py-2 rounded-md hover:bg-[var(--hover-color)] transition duration-300">Ver curso</button>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="font-bold text-lg text-[var(--hover-color)] mb-2">Marketing Digital para Principiantes</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Comprado el: 02/03/2024</span>
                                <span class="text-[var(--hover-color)]">$39.99</span>
                            </div>
                            <button class="mt-2 bg-[var(--hover-color)] text-white px-4 py-2 rounded-md hover:bg-[var(--hover-color)] transition duration-300">Ver curso</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    
        <!-- Modal para editar perfil -->
        <div id="edit-profile-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50   ">
            <div class="bg-white px-6 py-10 rounded-lg shadow-lg w-full max-w-[500px] [&>form>div]:mb-[24px] [&>form>div]:flex [&>form>div]:flex-col [&>form>div]:gap-5 [&>form>div>label]:uppercase [&>form>div>label]:font-medium [&>form>div>input]:p-[12px] [&>form>div>input]:text-[1.6rem] [&>form>div>label]:text-[1.6rem]">
                <form id="updateForm" method="post" action="{{ route('profile.update') }}">
                    @csrf
                    <h2 class="text-center text-[2.6rem] font-bold text-[var(--text-color)] mb-4">Editar Perfil</h2>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2">Nombre</label>
                        <input class="porfileInput" type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Correo electrónico</label>
                        <input class="porfileInput" type="email" id="email" name="email" value="{{ Auth::user()->email }}" class=" ">
                    </div>
                    <div class="mb-4">
                        <label for="bio" class="block text-gray-700 mb-2">Biografía</label>
                        <textarea class="porfileInput p-[12px]" id="bio" name="bio" rows="3" >{{Auth::user()->bio}}</textarea>
                    </div>
                    <div >
                        <div class="flex justify-between">
                            <button id="newPasswordBtn" class="profileButton">Cambiar Contraseña</button>
                            <button type="submit" class="profileButton">Guardar cambios</button>
                        </div>
                    
                    </div>
                </form>
                <form  id="newPassword" style="display: none;" method="post" action="{{ route('update.password') }}">
                    @csrf
                    <h2 class="text-center text-[2.6rem] font-bold text-[var(--text-color)] mb-4">Cambiar Contraseña</h2>

                    <div>
                        <label for="password">Contraseña</label>
                        <input class="porfileInput" type="password" id="password" name="password" placeholder="*********" required>
                    </div>
                    <div>
                        <label for="newPasswordInput">Nueva Contraseña</label>
                        <input class="porfileInput" type="password" id="newPasswordInput" name="newPassword" placeholder="*********" required>
                        
                    </div>
                    <button class="profileButton" type="submit">Guardar</button>
                </form>

                <div class="w-full text-center mt-6">
                    <button type="button" id="cancel-edit" class="bg-red-500 hover:bg-red-600 rounded-[5px] mr-2 text-[1rem] md:text-[1.6rem] px-[2rem] py-[1.2rem] text-[var(--text-color-index)] uppercase transition duration-300">Cancelar</button>
                    <button type="button" id="open-modal" class="bg-red-500 hover:bg-red-600 rounded-[5px] mr-2 text-[1rem] md:text-[1.6rem] px-[2rem] py-[1.2rem] text-[var(--text-color-index)] uppercase transition duration-300">Eliminar Cuenta</button>
                </div>
                
                <!-- Modal de confirmación -->
                <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-8 border w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl shadow-lg rounded-lg bg-white">
                        <div class="mt-4 text-center">
                            <h3 class="text-[2rem] md:text-[3rem]  font-medium text-gray-900">Confirmar eliminación de cuenta</h3>
                            <div class="mt-4 px-4 py-4">
                                <p class="text-[1.3rem] md:text-[1.7rem] text-gray-500">
                                    ¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.
                                </p>
                            </div>
                            <div class=" px-4 py-4 flex justify-center md:flex-row gap-2 md:gap-10 flex-col place-items-center">
                                <button id="cancelDelete" class="px-6 py-3 bg-gray-500 text-white text-lg md:text-xl font-medium rounded-md w-full md:w-auto mb-3 md:mb-0 ">
                                    Cancelar
                                </button>
                                <form class="w-full md:w-auto" method="post" action="{{route('delete.account')}}">
                                    @csrf
                                    <button id="confirmDelete" class="px-6 py-3 bg-red-500 text-white text-lg md:text-xl font-medium rounded-md w-full md:w-auto mb-3 md:mb-0 ">
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    
        <script>
            
        </script>
    </main>
</x-appLayout>