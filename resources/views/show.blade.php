<x-appLayout>
    <div class="min-h-screen w-full bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1 class="text-5xl md:text-6xl font-extrabold text-center text-indigo-900 mb-12">
                        {{ $course->title }}
                    </h1>
                    <div class="m-10 flex justify-center gap-10 text-[1.5rem] " id="enrollmentMessage"></div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                        <!-- Detalles del Curso -->
                        <div class="lg:col-span-2 space-y-12">
                            <div>
                                <p class="text-2xl md:text-left text-center text-gray-700 mb-8 leading-relaxed">{{ $course->description }}</p>
                                <div class="flex md:flex-row flex-col flex-wrap items-center gap-6 mb-8">
                                    <span class="px-6 py-3 bg-indigo-100 text-indigo-800 rounded-full text-lg font-semibold shadow-sm">
                                        {{ $course->category->name }}
                                    </span>
                                    <div class="flex items-center bg-yellow-100 px-6 py-3 rounded-full shadow-sm">
                                        @php
                                            $rating = $course->reviews()->avg('rating') ?? 0;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-8 h-8 {{ $i <= $rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-3 text-lg text-gray-700 font-medium">({{ $course->reviews()->count() }} reseñas)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-8">
                                @php
                                    $courseStats = [
                                        ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => $course->lessons()->sum('duration') . ' horas'],
                                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => ucfirst($course->level)],
                                        ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'label' => 'Certificado'],
                                        ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => $course->enrollments()->count() . ' estudiantes']
                                    ];
                                @endphp
                                @foreach ($courseStats as $stat)
                                    <div class="flex flex-col items-center p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-lg transition-transform hover:scale-105">
                                        <svg class="w-12 h-12 text-indigo-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                                        </svg>
                                        <span class="text-lg font-medium text-gray-700 text-center">{{ $stat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div>
                                <h2 class="text-3xl font-bold mb-8 text-indigo-900 border-b-2 border-indigo-100 pb-4">Contenido del curso</h2>
                                @foreach ($course->modules as $module)
                                    <div class="border-b border-gray-200 module-container">
                                        <button class="px-8 flex justify-between items-center w-full py-6 text-left text-2xl font-semibold hover:bg-indigo-50 focus:outline-none transition duration-300 module-toggle rounded-xl">
                                            <span>{{ $module->title }}</span>
                                            <svg class="w-8 h-8 transform transition-transform duration-300 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div class="py-6 module-content hidden">
                                            <ul class="space-y-4 pl-8">
                                                @foreach ($module->lessons as $lesson)
                                                    <li class="flex items-center p-4 hover:bg-indigo-50 rounded-xl transition duration-300">
                                                        <span class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-4 text-lg font-semibold">
                                                            {{ $loop->iteration }}
                                                        </span>
                                                        <span class="flex-grow text-lg text-gray-700">{{ $lesson->title }}</span>
                                                        <span class="text-gray-500 text-lg">{{ $lesson->duration }} min</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                <h2 class="text-3xl font-bold mb-8 text-indigo-900 border-b-2 border-indigo-100 pb-4">Sobre el instructor</h2>
                                <div class="flex justify-center md:justify-normal items-center space-x-8 bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-2xl shadow-lg">
                                    @if($course->instructor->profile_photo_url)
                                        <img src="{{ $course->instructor->profile_photo_url }}" alt="{{ $course->instructor->name }}" class="w-32 h-32 rounded-full shadow-xl">
                                    @else
                                        <div class="w-32 h-32 rounded-full bg-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-xl">
                                            {{ strtoupper(substr($course->instructor->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-3xl font-semibold text-indigo-900 mb-4">{{ $course->instructor->name }}</h3>
                                        <p class="text-lg text-gray-700 leading-relaxed">{{ $course->instructor->bio }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de Compra -->
                        <div class="lg:col-span-1">
                            <div class="bg-white p-8 rounded-2xl shadow-2xl sticky top-8">
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full py-5 px-2 object-cover rounded-xl mb-8 shadow-lg">
                                <div class="text-5xl font-bold mb-8 text-indigo-900">${{ number_format($course->price, 2) }}</div>
                                <button id="enrollmentBtn" class="w-full bg-indigo-600 text-white rounded-xl py-5 px-8 text-2xl font-semibold hover:bg-indigo-700 transition duration-300 transform hover:scale-105 mb-8 shadow-lg">
                                    Inscribirse Ahora
                                </button>
                                <p class="text-lg text-gray-600 mb-8 text-center">30 días de garantía de devolución de dinero</p>
                                <ul class="space-y-6 text-lg">
                                    @php
                                        $features = [
                                            $course->lessons()->count() . ' lecciones',
                                            'Acceso de por vida',
                                            'Certificado de finalización',
                                            'Recursos descargables'
                                        ];
                                    @endphp
                                    @foreach ($features as $feature)
                                        <li class="flex items-center">
                                            <svg class="w-8 h-8 mr-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            const moduleToggles = document.querySelectorAll('.module-toggle');
            moduleToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const moduleContent = this.nextElementSibling;
                    const chevron = this.querySelector('svg');

                    if (moduleContent.classList.contains('hidden')) {
                        moduleContent.classList.remove('hidden');
                        gsap.from(moduleContent, {height: 0, opacity: 0, duration: 0.5, ease: "power2.out"});
                        gsap.to(chevron, {rotation: 180, duration: 0.5});
                    } else {
                        gsap.to(moduleContent, {
                            height: 0, 
                            opacity: 0, 
                            duration: 0.5, 
                            ease: "power2.in",
                            onComplete: () => {
                                moduleContent.classList.add('hidden');
                                moduleContent.style.height = 'auto';
                                moduleContent.style.opacity = 1;
                            }
                        });
                        gsap.to(chevron, {rotation: 0, duration: 0.5});
                    }
                });
            });

            gsap.from('.module-container', {
                opacity: 0,
                y: 30,
                stagger: 0.2,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.module-container',
                    start: 'top bottom-=100px',
                    toggleActions: 'play none none none'
                }
            });

            gsap.from('.sticky', {
                y: 30,
                opacity: 0,
                duration: 1.2,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.sticky',
                    start: 'top center+=100px',
                    toggleActions: 'play none none none'
                }
            });

            const enrollButton = document.getElementById('enrollmentBtn');
    const enrollmentMessage = document.getElementById('enrollmentMessage');
    const attachmentInput = document.getElementById('attachmentInput'); // Asegúrate de tener este input en tu HTML

    enrollButton.addEventListener('click', function() {
        console.log('desde el boton')
        const courseId = '{{ $course->id }}';
        const formData = new FormData();
        formData.append('course_id', courseId);
        if (confirm('¿Estás seguro de que quieres inscribirte a este curso?')) {
            fetch(`/courses/${courseId}/enroll`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            enrollmentMessage.innerHTML = `<p class='text-green-500 bg-green-50 p-5 rounded-lg'>'Inscripción exitosa!'</p>`;
            enrollmentMessage.classList.remove('hidden');
        } else {
            enrollmentMessage.innerHTML = `<p class='text-red-500 bg-red-50 p-5 rounded-lg'>Ya estás inscrito en este curso.</p>`;
            enrollmentMessage.classList.remove('hidden');
        }
    })
        .catch(error => {
            enrollmentMessage.textContent = 'Hubo un problema con la inscripción. Inténtalo de nuevo.';
                        enrollmentMessage.classList.remove('hidden');
                        enrollmentMessage.classList.add('text-red-600');
        });
    }});
        
        });

        
        
                
    </script>
</x-appLayout>
