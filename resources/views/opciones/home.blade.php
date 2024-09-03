<x-appLayout>
    <main class="bg-white flex-1 flex flex-col">
        <div class=" pb-4 pt-8 px-20 flex w-full">
            <input type="text" placeholder="Buscar cursos, tutoriales..." class="bg-gray-50 text-[1.6rem] font-semibold w-full rounded-[20px] py-[1.2rem] px-[1.6rem] border-[#c3c6d6] border-solid border-[1px] outline-[var(--hover-color)]">
        </div>

        <div class="">
            <nav class=" flex mb-10 mt-12  justify-center flex-wrap gap-[2rem] md:gap-[4rem] [&>button]:px-3 [&>button]:py-2 [&>button]:text-[1.6rem] [&>button]:font-normal [&>button]:border-transparent [&>button]:text-[var(--text-color)] [&>button]:border-b-[2px] ">
                <button class="tab active">Para ti</button>
                <button class="tab">Siguiendo</button>
                <button class="tab">Cursos</button>
                <button class="tab">Crear</button>
            </nav>
        </div>
        <div class="bg-white flex justify-center p-[3.2rem] flex-grow-1">
            <div class=" w-full max-w-[400px] overflow-hidden shadow-md rounded-[10px] bg-[var(--card-bg)]">
                <div class="bg-black text-white w-full flex justify-center items-center text-[2rem] ratio">
                    [Video Placeholder]
                </div>
                <div class="p-8">
                    <h3 class="text-[#353535] font-semibold text-[1.6rem] mb-2">Aprende a programar en 30 días</h3>
                    <p class="text-[#353535] text-[1.2rem]">Descubre los fundamentos de la programación con este curso intensivo.</p>
                </div>
                <div class="border-t px-8 py-6 flex justify-between">
                    <button class="flex items-center text-gray-800 hover:text-gray-900 text-[1.4rem]">
                        <svg class="w-[2rem] h-[2rem] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                        Me gusta
                    </button>
                    <button class="flex items-center text-gray-800 hover:text-gray-900 text-[1.4rem]">
                        <svg class="w-[2rem] h-[2rem] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Comentar
                    </button>
                    <button class="flex items-center text-gray-800 hover:text-gray-900 text-[1.4rem]">
                        <svg class="w-[2rem] h-[2rem] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Compartir
                    </button>
                </div>
            </div>
        </div>
        


        
    </main>
</x-appLayout>