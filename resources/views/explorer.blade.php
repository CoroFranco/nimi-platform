<x-appLayout>
    
    <main class="max-w-[1200px] w-[90%] m-auto p-6 md:p-8 lg:p-12 bg-white">
        <h1 class="text-4xl font-bold mb-8 text-[var(--text-color)]">Explorar Cursos</h1>

        <div class="mb-8">
            <div class="relative">
                <input type="text" id="search" placeholder="Buscar cursos..." class="text-[1.5rem] w-full px-4 py-3 rounded-lg bg-[var(--input-bg)] text-[var(--text-color)] border border-[var(--border-color)] focus:outline-none focus:ring-2 focus:ring-[var(--hover-color)]">
                <svg class="absolute right-3 top-3 h-6 w-6 text-[var(--text-color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-1/4">
                <div class="bg-[var(--courses-bg)] rounded-lg shadow-lg p-6 text-[1.3rem]">
                    <button id="filter-toggle" class="lg:hidden w-full flex justify-between items-center text-[var(--text-color)] mb-4" aria-expanded="false" aria-controls="filter-content">
                        <span class="text-[2rem] font-semibold">Filtros</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="filter-content" class="hidden lg:block">
                        <form id="filter-form" class="space-y-6">
                            <div>
                                <h3 class="text-[2rem] font-medium mb-2 text-[var(--text-color)]">Categorías</h3>
                                <div class="space-y-2  overflow-y-auto">
                                    @foreach($categories as $category)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-checkbox text-[var(--hover-color)]">
                                            <span class="text-[var(--text-color)]">{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <h3 class="text-[2rem] font-medium mb-2 text-[var(--text-color)]">Nivel</h3>
                                @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="levels[]" value="{{ strtolower($level) }}" class="form-checkbox text-[var(--hover-color)]">
                                        <span class="text-[var(--text-color)]">{{ $level }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                <h3 class="text-[2rem] font-medium mb-2 text-[var(--text-color)]">Precio</h3>
                                <div class="flex items-center space-x-4">
                                    <input type="number" name="min_price" placeholder="Min" class="w-24 px-2 py-1 rounded-md bg-[var(--input-bg)] text-[var(--text-color)] border border-[var(--border-color)]">
                                    <span class="text-[var(--text-color)]">-</span>
                                    <input type="number" name="max_price" placeholder="Max" class="w-24 px-2 py-1 rounded-md bg-[var(--input-bg)] text-[var(--text-color)] border border-[var(--border-color)]">
                                </div>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-[var(--highlight-color)] hover:bg-[var(--hover-color)] text-white rounded-md hover:bg-opacity-90 transition duration-300">
                                Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-[2rem] font-semibold text-[var(--text-color)]">Resultados</h2>
                    <select id="sort" class="text-[1.3rem] px-4 py-2 rounded-md bg-[var(--input-bg)] text-[var(--text-color)] border border-[var(--border-color)]">
                        <option value="newest">Más recientes</option>
                        <option value="popular">Más populares</option>
                        <option value="price_low">Precio: Bajo a Alto</option>
                        <option value="price_high">Precio: Alto a Bajo</option>
                    </select>
                </div>
                <div id="courses-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                </div>
                <div id="pagination" class="mt-8 flex justify-center">
                    <!-- Pagination will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const filterForm = document.getElementById('filter-form');
        const sortSelect = document.getElementById('sort');
        const coursesGrid = document.getElementById('courses-grid');
        const pagination = document.getElementById('pagination');
        const filterToggle = document.getElementById('filter-toggle');
        const filterContent = document.getElementById('filter-content');
    
        let currentPage = 1;
        let debounceTimer;
    

    
        function debounce(func, delay) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        }
    
        function fetchCourses() {
            console.log('Obteniendo cursos...');
            const formData = new FormData(filterForm);
            
            // Convertir los checkboxes de categorías y niveles en strings separados por comas
            const categories = Array.from(formData.getAll('categories[]')).join(',');
            const levels = Array.from(formData.getAll('levels[]')).join(',');
            
            const params = {
                search: searchInput.value,
                categories: categories,
                levels: levels,
                min_price: formData.get('min_price'),
                max_price: formData.get('max_price'),
                sort: sortSelect.value,
                page: currentPage
            };

            axios.get('{{ route("api.courses.search") }}', { params })
                .then(response => {
                    if (response.data.courses.length === 0) {
                        console.log('No se encontraron cursos. Verifica tus criterios de búsqueda.');
                    }
                    updateCoursesGrid(response.data.courses);
                    updatePagination(response.data.pagination);
                })
                .catch(error => {
                    console.error('Error al obtener cursos:', error.response ? error.response.data : error.message);
                });
        }
    
        function updateCoursesGrid(courses) {
            coursesGrid.innerHTML = courses.map(course => `
                    <div class="bg-[var(--courses-bg)] rounded-lg shadow-lg overflow-hidden transition duration-300 hover:shadow-xl p-2">
                        <!-- Contenedor de la imagen para ajustar el tamaño -->
                        <div class="w-full h-48 overflow-hidden">
                            <img src="storage/${course.thumbnail_path}" alt="${course.title}" class="w-full h-full object-contain">
                        </div>
                        <div class="p-6">
                            <div>
                            </div>
                            <h3 class="text-[2rem] font-semibold mb-2 text-[var(--text-color)]">${course.title}</h3>
                            <p class="text-[1.5rem] text-[var(--text-color-secondary)] mb-4 break-words">${course.category.name}</p>
                            <div class="flex justify-between items-center text-[1.5rem]">
                                <span class="text-[var(--hover-color)] font-bold">$${parseFloat(course.price).toFixed(2)}</span>
                                <span class="text-[var(--text-color-secondary)] capitalize">${course.level}</span>
                            </div>
                            <a href="/show/${course.id}" class="text-[1.5rem] mt-4 block w-full text-center px-4 py-2 bg-[var(--highlight-color)] hover:bg-[var(--hover-color)] text-white rounded-md hover:bg-opacity-90 transition duration-300">
                                Ver Curso
                            </a>
                        </div>
                    </div>
            `).join('');
        }
    
        function updatePagination(paginationData) {
            const totalPages = paginationData.last_page;
            let paginationHTML = '';
    
            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `
                    <button class="px-3 py-1 mx-1 rounded ${i === currentPage ? 'bg-[var(--hover-color)] text-white' : 'bg-[var(--courses-bg)] text-[var(--text-color)]'}"
                            onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            }
    
            pagination.innerHTML = paginationHTML;
        }
    
        window.changePage = function(page) {
            currentPage = page;
            fetchCourses();
        }
    
        searchInput.addEventListener('input', () => debounce(fetchCourses, 300));
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            currentPage = 1;
            fetchCourses();
        });
        sortSelect.addEventListener('change', fetchCourses);
    
        // Agregar funcionalidad para el toggle de filtros en dispositivos móviles
        filterToggle.addEventListener('click', function() {
            filterContent.classList.toggle('hidden');
            const isExpanded = !filterContent.classList.contains('hidden');
            this.setAttribute('aria-expanded', isExpanded);
            // Cambiar el ícono cuando se expande/colapsa
            const svg = this.querySelector('svg');
            svg.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        // Función para manejar el cambio de tamaño de la ventana
        function handleResize() {
            if (window.innerWidth >= 1024) { // 1024px es el breakpoint para 'lg' en Tailwind por defecto
                filterContent.classList.remove('hidden');
            } else {
                filterContent.classList.add('hidden');
            }
        }

        // Ejecutar handleResize en la carga inicial y en cada cambio de tamaño de la ventana
        window.addEventListener('resize', handleResize);
        handleResize();
    
        fetchCourses(); // Initial fetch
    });
    </script>
</x-appLayout>