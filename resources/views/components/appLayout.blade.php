<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nimi - Cursos, tutoriales, clases y más</title>
        @vite(['resources/js/profile.js'])
        @vite(['resources/css/app.css'])
        @vite(['resources/css/normalize.css'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>
    <body class="bg-white text-[var(--text-color)] flex flex-col min-h-screen">
    <div class="flex flex-col md:flex-row flex-grow">

        <!-- Sidebar -->
        <aside class="w-full md:block md:w-[24rem] items-center flex flex-col md:pl-8 shadowNav z-10">
            <div class="md:p-4 pt-2 ">
                <a class="flex justify-center md:justify-start" href="/"><img src="/img/Logo.png" alt="Nimi logo" class="w-[20%] md:w-[50%] md:my-5"></a>
            </div>
            <nav class="text-[1.2rem] sm:text-[1.4rem] mt-8 grid grid-cols-3 place-items-center md:block [&>a]:mb-4 [&>a]:gap-2 [&>a]:flex-col [&>a]: md:[&>a]:flex-row [&>a]:flex [&>a]:items-center [&>a]:px-4 [&>a]:py-2 [&>a]:text-[var(--text-color)] [&>a:hover]:text-[var(--hover-color)]">
                <a href="/home" >
                    <svg class="w-[24px] h-[24px] md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Inicio
                </a>
                <a href="{{route('explorer')}}" >
                    <svg class="w-[24px] h-[24px] md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Explorar
                </a>
                @if(Auth::user())
                    
                    @php
                        $user = Auth::user();
                        $role = $user->role;
                    @endphp
                    @if($role === 'instructor')
                        <a href="{{route('myCourses')}}" >
                            <svg class="w-[24px] h-[24px] md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Mis Cursos
                        </a>
                        <a href="{{route('create')}}" >
                            <svg class="w-[24px] h-[24px] md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear
                        </a>
                    
                    @endif
                
                @endif
                
                
                @if (Auth::user()) 
                <a href="{{route('profile')}}" >
                    <svg class="w-[24px] h-[24px] md:mr-2 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ Auth::user()->name ?? "Perfil" }} 
                </a>
                    <div class="flex flex-col md:flex-row py-[0.5rem] pl-[1rem] gap-2 hover:text-[var(--hover-color)] mb-[1rem]">
                        <svg class="w-[24px] h-[24px] md:mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" width="800px" height="800px" viewBox="-13.22 0 122.88 122.88" stroke="currentColor">

                            
                            
                           
                            
                            <path class="st0" d="M0,115.27h4.39V1.99V0h1.99h82.93h1.99v1.99v113.28h5.14v7.61H0V115.27L0,115.27z M13.88,8.32H81.8h0.83v0.83 v104.89h4.69V3.97H8.36v111.3h4.69V9.15V8.32H13.88L13.88,8.32z M15.94,114.04H75.1l-0.38-0.15l-27.76-3.79V33.9l32.79-20.66v-2.04 H15.94V114.04L15.94,114.04z M51.7,59.66l4.23-1.21v15.81l-4.23-1.53V59.66L51.7,59.66z"/>
                            
                            
                            
                            </svg>
    
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit">Logout  </button>
                            </form>
                    </div>
                @else
                <a href="/" >
                    <svg class="w-[24px] h-[24px] md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Login
                </a>
                @endif

            </nav>
        </aside>

        {{$slot}}
        <!-- Footer -->
    </div>
    <footer class="bg-[var(--background-index)] text-[var(text-color)] mt-auto text-center">
        <div class="w-full mx-auto py-8 px-4 sm:px-10 lg:py-8 lg:px-24">
            <div class="sm:grid xl:grid-cols-3 place-items-center ">
                <div class="flex md:grid xl:grid-cols-2 gap-8 xl:col-span-2 place-items-center">
                    <div class="md:grid md:grid-cols-2 md:gap-20 [&>div>h3]:text-[1.5rem] [&>div>h3]:font-semibold [&>div>h3]:text-gray-400 [&>div>h3]:tracking-wider [&>div>h3]:uppercase">
                        <div>
                            <h3 class="">Sobre Nimi</h3>
                            <p class="mt-4 text-[1.2rem] text-gray-300">
                                Plataforma de aprendizaje en línea que conecta estudiantes y creadores de contenido educativo.
                            </p>
                        </div>
                        <div class="mt-12 md:mt-0">
                            <h3 class="">Enlaces rápidos</h3>
                            <ul class="mt-4 space-y-2">
                                <li><a href="#" class="text-[1.2rem] text-gray-300 hover:text-white">Cursos populares</a></li>
                                <li><a href="#" class="text-[1.2rem] text-gray-300 hover:text-white">Conviértete en instructor</a></li>
                                <li><a href="#" class="text-[1.2rem] text-gray-300 hover:text-white">Ayuda y soporte</a></li>
                                <li><a href="#" class="text-[1.2rem] text-gray-300 hover:text-white">Términos de servicio</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-8 xl:mt-0 [&>h3]:text-[1.5rem] [&>h3]:font-semibold [&>h3]:text-gray-400 [&>h3]:tracking-wider [&>h3]:uppercase">
                    <h3 class="">Contáctanos</h3>
                    <p class="mt-4 text-[1.2rem] text-gray-300">
                        Email: info@nimi.com<br>
                        Teléfono: +57 234 567 890
                    </p>
                    <div class="mt-4 flex space-x-6 justify-center">
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-[20px] w-[20px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-[20px] w-[20px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-[20px] w-[20px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-white pt-8">
                <p class="text-[1.2rem] text-gray-400 xl:text-center">
                    &copy; 2024 Nimi. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>

        const tabs = document.querySelectorAll('nav button');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });


        const actionButtons = document.querySelectorAll('.border-t button');
        actionButtons.forEach(button => {
            button.addEventListener('click', () => {
                console.log('Acción:', button.textContent.trim());
            });
        });
    </script>
</body>
</html>