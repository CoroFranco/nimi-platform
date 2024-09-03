<x-appLayout>
    <div class="py-12 w-full">
        <div class="max-w-[900px] w-[95%] mx-auto sm:px-6 lg:px-8">
            <h1 class="text-5xl font-bold mb-8">Solicitudes de Instructores</h1>

            <div x-data="{ showMessage: false, message: '', isError: false }" 
                 x-show="showMessage" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 :class="isError ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 'bg-green-100 border-l-4 border-green-500 text-green-700'"
                 class="p-6 mb-6 text-xl" role="alert">
                <p x-text="message"></p>
            </div>

            <div class="bg-[var(--background-main)] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 bg-[var(--background-main)] border-b border-[var(--highlight-color)]">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex space-x-6 w-full">
                            <select id="statusFilter" class="form-select rounded-md shadow-sm mt-1 block w-full text-xl p-3" onchange="filterApplications()">
                                <option value="">Todos los estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="approved">Aprobado</option>
                                <option value="rejected">Rechazado</option>
                            </select>
                            <input type="text" id="searchInput" placeholder="Buscar por nombre o email" class="instructorFormInput" onkeyup="filterApplications()">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="">
                                <tr class="">
                                    <th class=" border-b-2 border-gray-200 bg-gray-100 text-left text-lg font-semibold text-[var(--hover-color)] uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th class=" border-b-2 border-gray-200 bg-gray-100 text-left text-lg font-semibold text-[var(--hover-color)] uppercase tracking-wider">
                                        Experiencia
                                    </th>
                                    <th class=" border-b-2 border-gray-200 bg-gray-100 text-left text-lg font-semibold text-[var(--hover-color)] uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 text-left text-lg font-semibold text-[var(--hover-color)] uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="applicationsTable">
                                @foreach($applications as $application)
                                    <tr data-application-id="{{ $application->id }}">
                                        <td class="px-7 py-7 border-b border-gray-200 bg-white text-lg">
                                            <div class="flex items-center">
                                                <div class="ml-3">
                                                    <p class="text-gray-900 whitespace-no-wrap font-semibold text-xl">
                                                        {{ $application->user->name }}
                                                    </p>
                                                    <p class="text-gray-600 whitespace-no-wrap text-lg">
                                                        {{ $application->user->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-7 py-7 border-b border-gray-200 bg-white text-lg">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ Str::limit($application->expertise, 50) }}
                                            </p>
                                        </td>
                                        <td class="px-7 py-7 border-b border-gray-200 bg-white text-lg">
                                            <select 
                                                class="form-select rounded-md shadow-sm mt-1 block w-full text-lg p-2" 
                                                onchange="updateStatus({{ $application->id }}, this.value)"
                                            >
                                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Aprobado</option>
                                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                            </select>
                                        </td>
                                        <td class="px-7 py-7 border-b border-gray-200 bg-white text-lg">
                                            <button onclick="openModal({{ $application->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-xl shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                                                Ver Detalles
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="applicationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-7 pb-6 sm:p-8 sm:pb-6">
                    <h3 class="text-2xl leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                        Detalles de la Solicitud
                    </h3>
                    <div class="mt-4">
                        <div id="modalContent" class="text-lg text-gray-700"></div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-3 bg-white text-xl font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-lg" onclick="closeModal()">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
    function openModal(applicationId) {
        fetch(`/admin/instructor-applications/${applicationId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const socialMedia = JSON.parse(data.social_media);

// Crear la lista de elementos <li>
let socialMediaList = '';
for (const [key, value] of Object.entries(socialMedia)) {
    if (value) { // Verifica si hay un valor
        socialMediaList += `
            <li><strong class="font-semibold">${key.charAt(0).toUpperCase() + key.slice(1)}:</strong> 
            <a href="${value}" target="_blank" class="text-blue-600 hover:underline">${value}</a></li>`;
    }
}

                document.getElementById('modalContent').innerHTML = `
                    <p class="mb-4"><strong class="font-semibold">Nombre:</strong> ${data.user.name}</p>
                    <p class="mb-4"><strong class="font-semibold">Email:</strong> ${data.user.email}</p>
                    <p class="mb-4"><strong class="font-semibold">Biografía:</strong> ${data.bio}</p>
                    <p class="mb-4"><strong class="font-semibold">Experiencia:</strong> ${data.expertise}</p>
                    <p class="mb-4"><strong class="font-semibold">Experiencia en enseñanza:</strong> ${data.teaching_experience}</p>
                    <p class="mb-4"><strong class="font-semibold">Video de muestra:</strong> <a href="${data.sample_video}" target="_blank" class="text-blue-600 hover:underline">Ver video</a></p>
                    <p class="mb-2"><strong class="font-semibold">Redes sociales:</strong></p>
                    <ul >
                         ${socialMediaList}
                    </ul>
                `;
                document.getElementById('applicationModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al cargar los detalles de la solicitud.', true);
            });
    }

    function closeModal() {
        document.getElementById('applicationModal').classList.add('hidden');
    }

    function updateStatus(applicationId, newStatus) {
    if (!confirm('¿Estás seguro de que quieres cambiar el estado de esta solicitud?')) {
        return;
    }

    fetch(`/admin/instructor-applications/${applicationId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, false);
            updateUIAfterStatusChange(applicationId, newStatus);
            // La llamada a updateUserRole se ha eliminado, ya que no es necesaria
        } else {
            showMessage('Error al actualizar el estado: ' + data.message, true);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Ocurrió un error al actualizar el estado.', true);
    });
}


    function showMessage(message, isError) {
        const messageElement = document.querySelector('[x-data]');
        messageElement.__x.$data.showMessage = true;
        messageElement.__x.$data.message = message;
        messageElement.__x.$data.isError = isError;
        setTimeout(() => {
            messageElement.__x.$data.showMessage = false;
        }, 5000);
    }

    function updateUIAfterStatusChange(applicationId, newStatus) {
        const row = document.querySelector(`tr[data-application-id="${applicationId}"]`);
        if (row) {
            const statusSelect = row.querySelector('select');
            statusSelect.value = newStatus;
        }
    }

    function filterApplications() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.getElementById('applicationsTable').getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const statusCell = rows[i].getElementsByTagName('td')[2];
            const nameCell = rows[i].getElementsByTagName('td')[0];
            if (statusCell && nameCell) {
                const statusValue = statusCell.querySelector('select').value;
                const nameValue = nameCell.textContent || nameCell.innerText;
                const showStatus = status === '' || statusValue === status;
                const showSearch = search === '' || nameValue.toLowerCase().indexOf(search) > -1;
                rows[i].style.display = showStatus && showSearch ? '' : 'none';
            }
        }
    }
    </script>

</x-appLayout>